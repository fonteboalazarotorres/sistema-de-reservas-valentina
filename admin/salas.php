<<<<<<< HEAD
<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once '../config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) header('Location: login.php');

// Verificar se usuário logado é admin
//$stmt = $pdo->prepare("SELECT tipo FROM usuarios WHERE id = ?");
//$stmt->execute([$_SESSION['usuario_id']]);
//$usuario_atual = $stmt->fetch();
//if (!$usuario_atual || $usuario_atual['tipo'] !== 'admin') {
//    echo '<div class="alert alert-danger">Acesso negado. Área restrita para administradores. <a href="index.php">Voltar</a></div>';
//    exit;
//}

$stmt = $pdo->query('SELECT * FROM salas ORDER BY nome');
$salas = $stmt->fetchAll();

// Pega o horário atual
$horaAtual = date('H:i');
$dataHoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="icon" href="../bcrp-logo.png" type="image/x-icon">
</head>
<body>

    <script src="https://cdn.jsdelivr.net/gh/ifrederico/accessible-web-widget@latest/dist/accessible-web-widget.min.js"></script>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse">
                <?php include('menu_lateral.php'); ?>
            </div>
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="card border-0 shadow mb-4 mt-4">
                    <div class="card-header bg-white d-flex align-items-center">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        <h5 class="mb-0">Salas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Capacidade</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($salas as $s): 
                                    $statusExibido = ucfirst($s['status']);
                                    $badge = 'bg-secondary';

                                    // Se manutenção/inativa, mostra original
                                    if ($s['status'] == 'disponivel') {
                                        // Consulta p/ ver se existe reserva vigente agora
                                        $stmtReserva = $pdo->prepare("
                                            SELECT COUNT(*) FROM reservas 
                                            WHERE sala_id=? 
                                            AND data_reserva=? 
                                            AND hora_entrada <= ? 
                                            AND hora_saida > ? 
                                            AND status IN ('confirmada','pendente')
                                        ");
                                        $stmtReserva->execute([$s['id'], $dataHoje, $horaAtual, $horaAtual]);
                                        $emUso = $stmtReserva->fetchColumn() > 0;
                                        if ($emUso) {
                                            $statusExibido = 'Em uso';
                                            $badge = 'bg-info text-white';
                                        } else {
                                            $statusExibido = 'Disponível';
                                            $badge = 'bg-success';
                                        }
                                    } elseif ($s['status'] == 'manutencao') {
                                        $statusExibido = 'Em manutenção';
                                        $badge = 'bg-warning text-dark';
                                    } else {
                                        $badge = 'bg-secondary';
                                    }
                                ?>
                                <tr>
                                    <td><?=htmlspecialchars($s['nome'])?></td>
                                    <td><?= $s['capacidade'] ?></td>
                                    <td>
                                        <span class="badge <?= $badge ?>">
                                            <?= $statusExibido ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                        onclick="window.open('editar_sala.php?id=<?= $s['id'] ?>', '_blank')">
                                        <i class="fas fa-edit"></i>
                                       </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </main>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="mt-5 p-3 bg-light text-center">
        <p>Sistema de Reservas VALENTINA &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>
    </footer>


</body>
=======
<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) header('Location: login.php');

$stmt = $pdo->query('SELECT * FROM salas ORDER BY nome');
$salas = $stmt->fetchAll();

// Pega o horário atual
$horaAtual = date('H:i');
$dataHoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse">
                <?php include('menu_lateral.php'); ?>
            </div>
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="card border-0 shadow mb-4 mt-4">
                    <div class="card-header bg-white d-flex align-items-center">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        <h5 class="mb-0">Salas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Capacidade</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($salas as $s): 
                                    $statusExibido = ucfirst($s['status']);
                                    $badge = 'bg-secondary';

                                    // Se manutenção/inativa, mostra original
                                    if ($s['status'] == 'disponivel') {
                                        // Consulta p/ ver se existe reserva vigente agora
                                        $stmtReserva = $pdo->prepare("
                                            SELECT COUNT(*) FROM reservas 
                                            WHERE sala_id=? 
                                            AND data_reserva=? 
                                            AND hora_entrada <= ? 
                                            AND hora_saida > ? 
                                            AND status IN ('confirmada','pendente')
                                        ");
                                        $stmtReserva->execute([$s['id'], $dataHoje, $horaAtual, $horaAtual]);
                                        $emUso = $stmtReserva->fetchColumn() > 0;
                                        if ($emUso) {
                                            $statusExibido = 'Em uso';
                                            $badge = 'bg-info text-white';
                                        } else {
                                            $statusExibido = 'Disponível';
                                            $badge = 'bg-success';
                                        }
                                    } elseif ($s['status'] == 'manutencao') {
                                        $statusExibido = 'Em manutenção';
                                        $badge = 'bg-warning text-dark';
                                    } else {
                                        $badge = 'bg-secondary';
                                    }
                                ?>
                                <tr>
                                    <td><?=htmlspecialchars($s['nome'])?></td>
                                    <td><?= $s['capacidade'] ?></td>
                                    <td>
                                        <span class="badge <?= $badge ?>">
                                            <?= $statusExibido ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                        onclick="window.open('editar_sala.php?id=<?= $s['id'] ?>', '_blank')">
                                        <i class="fas fa-edit"></i>
                                       </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </main>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
>>>>>>> fc66e454e6b4da9575f2252dfdc7bf544e9b1f52
</html>