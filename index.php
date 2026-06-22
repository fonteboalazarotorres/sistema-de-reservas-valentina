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
</html>