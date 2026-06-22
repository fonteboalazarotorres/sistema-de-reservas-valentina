<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once '../config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acesso negado.');
}

// Lista única dos destinatários para evitar envios duplicados
$destinatarios = array_unique([
    'bcrp@usp.br',
    'conga@usp.br',
    'cassiamagalhaes@usp.br',
    'marciasantos@usp.br',
    'nairsol@usp.br',
    'robsonpa@usp.br',
    'fonteboa@usp.br'
]);

// Recebendo dados do POST
$mesAno = $_POST['mesano'] ?? date('Y-m');
list($ano, $mes) = explode('-', $mesAno);
$inicioMes = "{$ano}-{$mes}-01";
$fimMes = date('Y-m-t', strtotime($inicioMes));

// Imagens base64 dos gráficos
$imgReservasPorDiaBase64 = $_POST['imgReservasPorDia'] ?? '';
$imgSalasTop5Base64 = $_POST['imgSalasTop5'] ?? '';

// Buscar dados estatísticos principais
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservas WHERE data_reserva BETWEEN ? AND ?");
$stmt->execute([$inicioMes, $fimMes]);
$totalReservasMes = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(quantidade_pessoas) FROM reservas WHERE data_reserva BETWEEN ? AND ?");
$stmt->execute([$inicioMes, $fimMes]);
$totalPessoasMes = (int)$stmt->fetchColumn();

$dataSemanaInicio = date('Y-m-d', strtotime('monday this week'));
$dataSemanaFim = date('Y-m-d', strtotime('sunday this week'));
if ($dataSemanaInicio < $inicioMes) $dataSemanaInicio = $inicioMes;
if ($dataSemanaFim > $fimMes) $dataSemanaFim = $fimMes;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservas WHERE data_reserva BETWEEN ? AND ?");
$stmt->execute([$dataSemanaInicio, $dataSemanaFim]);
$totalReservasSemana = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT s.nome, COUNT(*) as total_reservas, SUM(r.quantidade_pessoas) as total_pessoas
    FROM reservas r
    JOIN salas s ON r.sala_id = s.id
    WHERE r.data_reserva BETWEEN ? AND ?
    GROUP BY r.sala_id, s.nome
    ORDER BY total_reservas DESC
    LIMIT 1
");
$stmt->execute([$inicioMes, $fimMes]);
$salaMaisUsada = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta pessoas e reservas por data
$stmt = $pdo->prepare("
    SELECT data_reserva, SUM(quantidade_pessoas) AS total_pessoas, COUNT(*) AS total_reservas
    FROM reservas
    WHERE data_reserva BETWEEN ? AND ?
    GROUP BY data_reserva
    ORDER BY data_reserva ASC
");
$stmt->execute([$inicioMes, $fimMes]);
$pessoasReservasPorData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Soma totals para rodapé
$somaPessoas = 0;
$somaReservas = 0;
foreach ($pessoasReservasPorData as $linha) {
    $somaPessoas += (int)$linha['total_pessoas'];
    $somaReservas += (int)$linha['total_reservas'];
}

// Dados detalhados: Salas 1 a 15 por data (pessoas e reservas)
$salasIds = range(1, 15);
$stmtSalas = $pdo->prepare("SELECT id, nome FROM salas WHERE id BETWEEN 1 AND 15 ORDER BY id ASC");
$stmtSalas->execute();
$salasNomes = $stmtSalas->fetchAll(PDO::FETCH_KEY_PAIR);
foreach ($salasIds as $sid) {
    if (!isset($salasNomes[$sid])) {
        $salasNomes[$sid] = "Sala $sid";
    }
}
$stmtDatas = $pdo->prepare("SELECT DISTINCT data_reserva FROM reservas WHERE data_reserva BETWEEN ? AND ? ORDER BY data_reserva ASC");
$stmtDatas->execute([$inicioMes, $fimMes]);
$datasMesReservas = $stmtDatas->fetchAll(PDO::FETCH_COLUMN);

$stmtDados = $pdo->prepare("
    SELECT sala_id, data_reserva, SUM(quantidade_pessoas) AS total_pessoas, COUNT(*) AS total_reservas
    FROM reservas
    WHERE data_reserva BETWEEN ? AND ? AND sala_id BETWEEN 1 AND 15
    GROUP BY sala_id, data_reserva
");
$stmtDados->execute([$inicioMes, $fimMes]);
$dadosReservas = $stmtDados->fetchAll(PDO::FETCH_ASSOC);

$matriz = [];
$totaisPorDataPessoas = [];
$totaisPorDataReservas = [];
foreach ($datasMesReservas as $data) {
    $totaisPorDataPessoas[$data] = 0;
    $totaisPorDataReservas[$data] = 0;
}
foreach ($dadosReservas as $row) {
    $sid = (int)$row['sala_id'];
    $data = $row['data_reserva'];
    $matriz[$sid][$data]['pessoas'] = (int)$row['total_pessoas'];
    $matriz[$sid][$data]['reservas'] = (int)$row['total_reservas'];
    $totaisPorDataPessoas[$data] += (int)$row['total_pessoas'];
    $totaisPorDataReservas[$data] += (int)$row['total_reservas'];
}

$totaisPorSala = [];
foreach ($salasIds as $sid) {
    $totaisPorSala[$sid]['pessoas'] = 0;
    $totaisPorSala[$sid]['reservas'] = 0;
    if (isset($matriz[$sid])) {
        foreach ($matriz[$sid] as $info) {
            $totaisPorSala[$sid]['pessoas'] += $info['pessoas'] ?? 0;
            $totaisPorSala[$sid]['reservas'] += $info['reservas'] ?? 0;
        }
    }
}

// Montar corpo do email com estilos inline
$corpo = '
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
  body { font-family: Arial, sans-serif; margin:0; padding:20px; background:#f7f9fc; color:#333; }
  .container { max-width: 700px; margin: auto; background:#fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
  h1, h2 { color: #2563eb; }
  .section { margin-bottom: 25px; }
  .card { background: #e4eaf6; padding: 15px; border-radius: 8px; margin-top: 10px; }
  table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size:14px;}
  th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
  th { background: #dbe4f9; }
  tfoot td { background: #e1f0ea; font-weight:bold; }
  img { max-width: 100%; height: auto; margin-top: 15px; border-radius: 8px; }
  small { font-size: 0.8rem; color:#555; }
  @media only screen and (max-width: 600px) {
      body, .container { padding: 10px; }
      table, th, td { font-size: 12px; }
  }
</style>
</head>
<body>
<div class="container">
<h1>Relatório de Reservas - ' . htmlspecialchars(date('m/Y', strtotime($mesAno . '-01'))) . '</h1>

<div class="section card">
  <h2>Estatísticas Principais</h2>
  <p><strong>Total de Reservas no Mês:</strong> ' . $totalReservasMes . '</p>
  <p><strong>Total de Pessoas no Mês:</strong> ' . $totalPessoasMes . '</p>
  <p><strong>Total de Reservas na Semana Atual:</strong> ' . $totalReservasSemana . '</p>
  <p><strong>Sala Mais Reservada:</strong> ' . htmlspecialchars($salaMaisUsada['nome'] ?? '-') . '</p>
  <p><strong>Reservas na Sala:</strong> ' . (int)($salaMaisUsada['total_reservas'] ?? 0) . '</p>
  <p><strong>Total de Pessoas na Sala:</strong> ' . (int)($salaMaisUsada['total_pessoas'] ?? 0) . '</p>
</div>

<div class="section">
  <h2>Gráficos</h2>' .
  ($imgReservasPorDiaBase64 ? '<img src="' . htmlspecialchars($imgReservasPorDiaBase64) . '" alt="Gráfico Reservas por Dia" />' : '') .
  ($imgSalasTop5Base64 ? '<img src="' . htmlspecialchars($imgSalasTop5Base64) . '" alt="Gráfico Top 5 Salas" />' : '') .
'</div>

<div class="section card">
  <h2>Quantidade Total de Pessoas por Data</h2>
  <table>
    <thead>
      <tr>
        <th>Data</th>
        <th>Total de Pessoas</th>
        <th>Total de Reservas</th>
      </tr>
    </thead>
    <tbody>';

foreach ($pessoasReservasPorData as $linha) {
    $corpo .= '<tr>
      <td>' . date('d/m/Y', strtotime($linha['data_reserva'])) . '</td>
      <td>' . (int)$linha['total_pessoas'] . '</td>
      <td>' . (int)$linha['total_reservas'] . '</td>
    </tr>';
}

$corpo .= '</tbody>
    <tfoot>
      <tr>
        <td>Total no mês</td>
        <td>' . $somaPessoas . '</td>
        <td>' . $somaReservas . '</td>
      </tr>
    </tfoot>
  </table>
</div>

<div class="section card">
  <h2>Dados detalhados das reservas (Salas vs Datas)</h2>
  <table>
    <thead>
      <tr>
        <th>Sala</th>';

foreach ($datasMesReservas as $data) {
    $corpo .= '<th>' . date('d/m', strtotime($data)) . '<br><small>' . date('D', strtotime($data)) . '</small></th>';
}

$corpo .= '<th>Total Pessoas</th><th>Total Reservas</th></tr></thead><tbody>';

foreach ($salasIds as $sid) {
    $corpo .= '<tr><td>' . htmlspecialchars($salasNomes[$sid]) . '</td>';
    foreach ($datasMesReservas as $data) {
        $p = $matriz[$sid][$data]['pessoas'] ?? 0;
        $r = $matriz[$sid][$data]['reservas'] ?? 0;
        $corpo .= '<td>' . $p . '<br><small>(' . $r . ')</small></td>';
    }
    $corpo .= '<td>' . (int)$totaisPorSala[$sid]['pessoas'] . '</td><td>' . (int)$totaisPorSala[$sid]['reservas'] . '</td></tr>';
}

$corpo .= '</tbody><tfoot><tr><td>Total por data</td>';

foreach ($datasMesReservas as $data) {
    $corpo .= '<td>' . (int)$totaisPorDataPessoas[$data] . '<br><small>(' . (int)$totaisPorDataReservas[$data] . ')</small></td>';
}

$corpo .= '<td>' . array_sum(array_column($totaisPorSala, 'pessoas')) . '</td><td>' . array_sum(array_column($totaisPorSala, 'reservas')) . '</td></tr></tfoot></table></div>

</div>
<footer style="text-align:center; padding:12px 0; font-size:0.9em; color:#666;">
  Sistema de Reserva de Salas &copy; 2025<br/>
  Desenvolvido por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> e
  idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a>
</footer>
</body>
</html>';

// Cabeçalhos do email
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: Sistema de Reservas <bcrp@usp.br>\r\n";

// Enviar para todos destinatários
foreach ($destinatarios as $email) {
    mail($email, "Relatório de Reservas - " . date('m/Y', strtotime($mesAno . '-01')), $corpo, $headers);
}

echo 'ok';
exit;