<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once 'config.php';

// Lista de salas e equipamentos do banco
$salas = $pdo->query("SELECT id, nome, capacidade FROM salas ORDER BY nome")->fetchAll();
$equipamentos = $pdo->query("SELECT id, nome FROM equipamentos ORDER BY nome")->fetchAll();

// Sala selecionada fixa, pega da URL (ex: nova_reserva.php?sala_id=3)
$salaSelecionada = isset($_GET['sala_id']) ? intval($_GET['sala_id']) : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nova Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="css/novareserva.css">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:700px;">
    <!--<h2 class="mb-4 text-primary text-center">
      <i class="fas fa-calendar-plus me-2"></i>Nova Reserva
    </h2>-->

    <form id="formNovaReserva" method="post" action="processar_reserva.php" novalidate>
        
      <h2 class="mb-4 text-primary text-center">
      	<i class="fas fa-calendar-plus me-2"></i>Nova Reserva
     </h2>
        
        <div class="mb-3">
            <label for="nome_completo" class="form-label">Nome completo</label>
            <input type="text" class="form-control" id="nome_completo" name="nome_completo" required />
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="numero_usp" class="form-label">Número USP</label>
                <input type="text" class="form-control" id="numero_usp" name="numero_usp" required />
            </div>
            <div class="col-md-6">
                <label for="vinculo" class="form-label">Vínculo</label>
                <select class="form-select" id="vinculo" name="vinculo" required>
                    <option value="" selected disabled>Selecione...</option>
                    <option value="Graduação">Graduação</option>
                    <option value="Pós-graduação">Pós-graduação</option>
                    <option value="Docente">Docente</option>
                    <option value="Servidor">Servidor</option>
                    <option value="Externo">Externo</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="data_reserva" class="form-label">Data da reserva</label>
                <input type="date" class="form-control" id="data_reserva" name="data_reserva" min="<?= date('Y-m-d') ?>" required />
            </div>
            <div class="col-md-6">
                <label for="quantidade_pessoas" class="form-label">Quantidade de pessoas</label>
                <input type="number" class="form-control" id="quantidade_pessoas" name="quantidade_pessoas" min="1" required />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="hora_entrada" class="form-label">Hora de entrada</label>
                <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" required />
            </div>
            <div class="col-md-6">
                <label for="hora_saida" class="form-label">Hora de saída</label>
                <input type="time" class="form-control" id="hora_saida" name="hora_saida" required readonly />
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Necessidade de equipamentos</label>
            <div class="row">
                <?php foreach ($equipamentos as $equipamento): ?>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="equipamentos[]" value="<?= $equipamento['id'] ?>" id="equip_<?= $equipamento['id'] ?>">
                            <label class="form-check-label" for="equip_<?= $equipamento['id'] ?>">
                                <?= htmlspecialchars($equipamento['nome']) ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-4">
            <label for="sala_id" class="form-label">Sala</label>
            <input type="text" class="form-control" value="<?php
                // Mostrar o nome da sala selecionada (buscar no array)
                $nomeSala = 'Sala não encontrada';
                foreach($salas as $s) {
                    if ($s['id'] === $salaSelecionada) {
                        $nomeSala = $s['nome'];
                        break;
                    }
                }
                echo htmlspecialchars($nomeSala);
            ?>" readonly />
            <input type="hidden" name="sala_id" value="<?= $salaSelecionada ?>" />
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fas fa-calendar-check me-1"></i>Reservar
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Ao preencher hora de entrada, preenche hora de saída automaticamente adicionando 2 horas
document.getElementById('hora_entrada').addEventListener('change', function() {
    let entrada = this.value;
    if (!entrada) {
        document.getElementById('hora_saida').value = '';
        return;
    }
    let [h, m] = entrada.split(':').map(Number);
    h += 2; // adiciona 2 horas
    if (h >= 24) {
        h -= 24; // volta ao dia seguinte (não trata data, só hora)
    }
    // Formatar para HH:MM
    const hStr = h.toString().padStart(2, '0');
    const mStr = m.toString().padStart(2, '0');
    document.getElementById('hora_saida').value = `${hStr}:${mStr}`;
});
</script>
    
         <footer class="footer text-center">
        <p>Sistema de Reservas VALENTINA &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido originalmente por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>

</body>
    
      <script src="https://cdn.jsdelivr.net/gh/ifrederico/accessible-web-widget@latest/dist/accessible-web-widget.min.js"></script>
</html>