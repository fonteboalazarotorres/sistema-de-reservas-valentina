<<<<<<< HEAD
<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once 'config.php';

// Definir data e hora atuais (fuso local)
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');
$agora = date('H:i:s');

// Buscar todas as salas
$salas = $pdo->query("SELECT id, nome, capacidade FROM salas ORDER BY nome")->fetchAll();

$salasStatus = [];

foreach ($salas as $sala) {
    // Verificar reserva ativa na sala para agora
    $stmt = $pdo->prepare("SELECT hora_saida FROM reservas 
        WHERE sala_id = ? 
        AND data_reserva = ? 
        AND status IN ('confirmada','pendente') 
        AND hora_entrada <= ? AND hora_saida > ? 
        ORDER BY hora_saida DESC LIMIT 1");
    $stmt->execute([$sala['id'], $hoje, $agora, $agora]);
    $reservaAtiva = $stmt->fetch();

    if ($reservaAtiva) {
        $status = 'ocupada';
        $liberacao = substr($reservaAtiva['hora_saida'], 0, 5);
    } else {
        $status = 'disponivel';
        $liberacao = null;
    }
    $salasStatus[] = [
        'id' => $sala['id'],
        'nome' => $sala['nome'],
        'capacidade' => $sala['capacidade'],
        'status' => $status,
        'liberacao' => $liberacao
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Sistema de Reservas VALENTINA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<link rel="stylesheet" href="css/index.css">
<link rel="icon" href="bcrp-logo.png" type="image/x-icon">

</head>
    
<body>
    
      <script src="https://cdn.jsdelivr.net/gh/ifrederico/accessible-web-widget@latest/dist/accessible-web-widget.min.js"></script>
    <!--<script src="https://dash.accessiblyapp.com/widget/0199deb3-a725-711f-ab35-cd64fdec2b22/autoload.js"></script>-->

<div class="container py-4">
    
            <h1 class="text-center">Sistema de Reservas VALENTINA</h1>
            <p class="text-center">Reserve uma sala hoje <span id="data_atual"><?=date('d/m/Y')?></span> para estudo ou uma reunião</span></p>


    <!--<h2 class="mb-4 text-center text-white">Disponibilidade das Salas - <span id="data_atual"><?=date('d/m/Y')?></span></h2>-->
    
  <div class="row g-3" id="salas_container">
    <!-- Cards das salas serão inseridos aqui -->
  </div>
</div>

<div class="d-flex justify-content-center mb-4 flex-wrap" style="gap:18px;">
    <a href="/admin/login.php" class="btn btn-warning px-4 py-2 fw-bold rounded-pill shadow-sm">
        <i class="fas fa-sign-in-alt me-2"></i>Login Administrativo
    </a>
    <a href="sobre.php" class="btn btn-info px-4 py-2 fw-bold rounded-pill shadow-sm text-white">
        <i class="fas fa-info-circle me-2"></i>Sobre o Desenvolvedor
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function carregarSalas() {
  $.ajax({
    url: 'ajax_salas_status.php',
    type: 'GET',
    dataType: 'json',
    success: function(salas) {
      let html = '';
      salas.forEach(sala => {
        html += `
        <div class="col-md-4 col-lg-3">
          <div class="card shadow-sm h-100 d-flex flex-column">
            <h5 class="card-title">${sala.nome}</h5>
            <p class="card-text">Capacidade: <strong>${sala.capacidade} pessoa(s)</strong></p>
            ${sala.status === 'disponivel' ? `
                <span class="badge badge-success">DISPONÍVEL</span>
                <a href="nova_reserva.php?sala_id=${encodeURIComponent(sala.id)}" class="btn btn-primary mt-auto">
                    <i class="fas fa-calendar-check me-2"></i>Reservar
                </a>` : `
                <span class="badge badge-danger">OCUPADA até ${sala.liberacao}</span>
                <button class="btn btn-secondary mt-auto" disabled>
                    <i class="fas fa-lock me-2"></i>Indisponível
                </button>`}
          </div>
        </div>`;
      });
      $('#salas_container').html(html);

      // Atualizar data atual visível
      $('#data_atual').text(new Date().toLocaleDateString('pt-BR'));
    },
    error: function() {
      $('#salas_container').html('<p class="text-danger text-center">Erro ao carregar as salas. Tente novamente.</p>');
    }
  });
}

// Atualiza ao carregar a página
$(document).ready(function() {
  carregarSalas();
  // Atualiza a cada 3 segundos (3000 ms)
  setInterval(carregarSalas, 3000);
});
</script>

    
     <footer class="footer text-center">
        <p>Sistema de Reservas VALENTINA &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
=======
<?php
require_once 'config.php';

// Verificar se existe uma mensagem de sucesso ou erro
$mensagem = '';
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}

// Buscar todas as salas
$stmt = $pdo->prepare("SELECT * FROM salas ORDER BY nome");
$stmt->execute();
$salas = $stmt->fetchAll();

// Buscar todos os equipamentos
$stmt = $pdo->prepare("SELECT * FROM equipamentos");
$stmt->execute();
$equipamentos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reserva de Salas BCRP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-4">
        <header class="mb-4">
            <h1 class="text-center">Sistema de Reserva de Salas BCRP</h1>
            <p class="text-center">Reserve uma sala para estudo ou reunião</p>
            <div class="text-end">
                <!--<a href="admin/login.php" class="btn btn-sm btn-outline-primary">Entrar como administrador</a>-->
            </div>
        </header>

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $mensagem; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Formulário para reserva de sala</h3>
                    </div>
                    <div class="card-body">
                        <form action="processar_reserva.php" method="post" id="formReserva">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="nome_completo" class="form-label">Nome completo</label>
                                    <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="numero_usp" class="form-label">Número USP</label>
                                    <input type="text" class="form-control" id="numero_usp" name="numero_usp" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="vinculo" class="form-label">Vínculo</label>
                                    <select class="form-select" id="vinculo" name="vinculo" required>
                                        <option value="">Selecione...</option>
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
                                    <input type="date" class="form-control" id="data_reserva" name="data_reserva" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quantidade_pessoas" class="form-label">Quantidade de pessoas</label>
                                    <input type="number" class="form-control" id="quantidade_pessoas" name="quantidade_pessoas" min="1" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="hora_entrada" class="form-label">Hora de entrada</label>
                                    <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="hora_saida" class="form-label">Hora de saída</label>
                                    <input type="time" class="form-control" id="hora_saida" name="hora_saida" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Necessidade de equipamentos</label> 
                                <div class="row">
                                    <?php foreach ($equipamentos as $equipamento): ?>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="equipamentos[]" value="<?php echo $equipamento['id']; ?>" id="equip_<?php echo $equipamento['id']; ?>">
                                            <label class="form-check-label" for="equip_<?php echo $equipamento['id']; ?>">
                                                <?php echo $equipamento['nome']; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Salas Disponíveis</label>
                                <div class="row" id="salas_container">
                                    <!-- As salas disponíveis serão carregadas via AJAX -->
                                    <div class="col-12 text-center">
                                        <p>Selecione a data e horário para ver as salas disponíveis</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="btnReservar">Reservar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 p-3 bg-light text-center">
        <p>Sistema de Reserva de Salas - BCRP-USP &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido por <a href="https://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
>>>>>>> fc66e454e6b4da9575f2252dfdc7bf544e9b1f52
</html>