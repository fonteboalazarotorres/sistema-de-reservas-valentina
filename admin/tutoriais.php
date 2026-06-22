<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tutoriais - Sistema de Reservas</title>
  <link rel="stylesheet" href="../css/admin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
<link rel="icon" href="../bcrp-logo.png" type="image/x-icon">
  <?php
// menu_lateral.php
// Assegure que session já foi iniciada antes de incluir este arquivo
$currentPage = basename($_SERVER['PHP_SELF']);
?>



<style>
  .sidebar .nav-link {
    font-weight: 600;
    padding: 10px 15px;
    transition: background-color 0.3s, color 0.3s;
  }
  .sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.2);
    color: #fff!important;
  }
  .sidebar .nav-link.active {
    font-weight: 700;
    background-color: #fff!important;
    color: #2563eb!important;
  }
</style>


  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content {
      margin-left: 260px;
      padding: 2rem 1rem;
      max-width: 1080px;
    }

    @media (max-width: 767px) {
      .main-content {
        margin-left: 0;
      }
    }

    h1 {
      color: #2563eb;
      margin-bottom: 2rem;
      font-weight: 700;
      text-align: center;
    }

.video-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit,minmax(340px,1fr));
  gap: 2rem;
}

.video-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
  padding: 18px 14px 14px 14px;
  transition: box-shadow 0.2s;
}

.video-card:hover {
  box-shadow: 0 8px 24px rgba(32, 98, 228, .13);
}

.video-title {
  color: #2563eb;
  font-size: 1.15rem;
  font-weight: 700;
  margin-bottom: 10px;
  text-align: center;
}

.video-wrapper {
  position: relative;
  padding-bottom: 56.25%;
  height: 0;
  border-radius: 10px;
  overflow: hidden;
  background: #000;
}

.video-wrapper iframe {
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  border: 0;
  border-radius: 10px;
}


    .manuals-section {
      background: #fff;
      border-radius: 10px;
      padding: 2rem 3rem;
      box-shadow: 0 6px 18px rgba(0,0,0,0.07);
      max-width: 720px;
      margin: 0 auto 4rem;
    }

    .manuals-section h3 {
      color: #2563eb;
      font-weight: 700;
      margin-bottom: 1.2rem;
      text-align: center;
    }

    .manuals-list a {
      display: block;
      font-weight: 600;
      color: #2563eb;
      margin-bottom: 0.7rem;
      text-decoration: none;
      font-size: 1.05rem;
      padding: 0.3rem 0.2rem;
      border-radius: 5px;
      transition: background-color 0.25s ease;
      text-align: center;
    }

    .manuals-list a:hover {
      background-color: #e5f0ff;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar do menu lateral (cole aqui) -->
      <div class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse">
        <div class="position-sticky pt-3">
          <div class="text-center mb-4">
            <h3 class="text-white">
              <i class="fas fa-bars"></i>
              Menu
            </h3>
          </div>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="fas fa-tachometer-alt"></i>
                Painel
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="reservas.php">
                <i class="fas fa-calendar-check"></i>
                Reservas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="estatisticas.php">
                <i class="fas fa-chart-bar"></i>
                Estatísticas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="salas.php">
                <i class="fas fa-door-open"></i>
                Salas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="usuarios.php">
                <i class="fas fa-user-group"></i>
                Gerenciar usuários
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="tutoriais.php">
                <i class="fa fa-question"></i>
                Tutorial
              </a>
            </li>
        <li class="nav-item">
            <a class="nav-link" href="../sobre.php">
                <i class="fa fa-info-circle"></i> Sobre o Sistema
            </a>
        </li>  
            <li class="nav-item mt-5">
              <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                Sair
              </a>
            </li>
          </ul>
        </div>
      </div>

<main class="main-content" style="margin: 0 auto; max-width: 1200px; padding: 2rem 1rem;">
  <h1 style="text-align: center;"><i class="fas fa-video me-2"></i>Tutoriais em Vídeo</h1>

  <!-- SEÇÃO DE VÍDEOS -->
  <section class="video-grid" style="margin-bottom: 3rem;">
    <!-- Video 1 -->
    <div class="video-card">
      <h5 class="video-title">FORMULÁRIO DE RESERVA - USUÁRIO</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/GMHYJBpxPW8" title="FORMULÁRIO DE RESERVA - USUÁRIO" allowfullscreen></iframe>
      </div>
    </div>
    <!-- Video 2 -->
    <div class="video-card">
      <h5 class="video-title">COMO FAZER O LOGIN NO SISTEMA DE RESERVAS?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/QWLNXCiWYO0" title="COMO FAZER O LOGIN NO SISTEMA DE RESERVAS?" allowfullscreen></iframe>
      </div>
    </div>
    <!-- Demais vídeos igual exemplo acima -->
    <div class="video-card">
      <h5 class="video-title">COMO VISUALIZAR DADOS DE UMA RESERVA?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/IdGS0iXyKF0" title="COMO VISUALIZAR DADOS DE UMA RESERVA?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">COMO EDITAR OU ATUALIZAR UMA RESERVA?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/_xKttf8F7wk" title="COMO EDITAR OU ATUALIZAR UMA RESERVA?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">COMO EXCLUIR UMA RESERVA?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/1GJRrcfpEmE" title="COMO EXCLUIR UMA RESERVA?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">COMO SABER SE A SALA ESTÁ RESERVADA OU NÃO?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/KH0tMGjP4rw" title="COMO SABER SE A SALA ESTÁ RESERVADA OU NÃO?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">Biblioteca Central Campus USP Ribeirão Preto</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/HqG_qV4T-tc" title="Biblioteca Central Campus USP Ribeirão Preto" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">COMO ACESSAR E COMPARTILHAR AS ESTATÍSTICAS?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/dDbYLSSmRbc" title="COMO ACESSAR E COMPARTILHAR AS ESTATÍSTICAS?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">RECEBENDO AS ESTATÍSTICAS COMPARTILHADAS POR EMAIL</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/4DuZZUcPOZI" title="RECEBENDO AS ESTATÍSTICAS COMPARTILHADAS POR EMAIL" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">COMO LIBERAR SALA E CONCLUIR RESERVA?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/5LSuwIWjX0k" title="COMO LIBERAR SALA E CONCLUIR RESERVA?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">COMO ADICIONAR RESERVA SENDO ADMINISTRADOR?</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/uJ0PQTmdQNA" title="COMO ADICIONAR RESERVA SENDO ADMINISTRADOR?" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">SALAS</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/loMaIDklB7g" title="SALAS" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">ACESSIBLIDADE</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/SqMiAdSU-HU" title="ACESSIBLIDADE" allowfullscreen></iframe>
      </div>
    </div>
    <div class="video-card">
      <h5 class="video-title">GERENCIAMENTO DE USUÁRIOS</h5>
      <div class="video-wrapper">
        <iframe src="https://www.youtube.com/embed/SqMiAdSU-HU" title="GERENCIAMENTO DE USUÁRIOS" allowfullscreen></iframe>
      </div>
    </div>
  </section>
  <!-- FIM DOS VÍDEOS -->

  <!-- SEÇÃO DE MANUAIS CENTRALIZADA -->
  <section class="manuals-section" style="margin-left: auto; margin-right: auto;">
    <h3>Manual</h3>
    <div class="manuals-list">
      <a href="MANUAL DO SISTEMA VALENTINA.pdf" target="_blank" rel="noopener noreferrer">
        Manual do Usuário e administrador (PDF)
      </a>
    </div>
  </section>

</main>
    <footer class="mt-5 p-3 bg-light text-center">
        <p>Sistema de Reservas VALENTINA &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>