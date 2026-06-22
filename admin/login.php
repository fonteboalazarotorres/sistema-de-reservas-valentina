<<<<<<< HEAD
<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once '../config.php';
session_start();

// Verificar se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

// Processar o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                header('Location: index.php');
                exit;
            } else {
                $erro = 'E-mail ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao processar o login: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Área Administrativa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="icon" href="../bcrp-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/login.css">    
</head>
<body>
    
    <script src="https://cdn.jsdelivr.net/gh/ifrederico/accessible-web-widget@latest/dist/accessible-web-widget.min.js"></script>
    
    <div class="main-wrapper">
    <div class="flex-center">
        
            <div class="login-container">
                <div class="avatar">
                    <img src="user-244.png" alt="Avatar">
                </div>
                <h2>Área Administrativa</h2>
                <form method="post" action="">
                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $erro; ?></div>
                    <?php endif; ?>
                    <div class="input-group">
                        <span class="icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" placeholder="seu.email@usp.br" required>
                    </div>
                    <div class="input-group">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="senha" placeholder="Senha" required>
                    </div>
                    <button type="submit" class="login-btn">Entrar</button>
                </form>
                <a class="forgot-link" href="../index.php">
                    <i class="fas fa-arrow-left"></i> Voltar para a página inicial
                </a>
            </div>
        
    <footer class="footer">
        <p>Sistema de Reservas VALENTINA &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>
        
        <!--<p class="versao">Versão 2.0</p>-->
        
    </footer>
</div>

</body>
=======
<?php
require_once '../config.php';
session_start();

// Verificar se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

// Processar o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                header('Location: index.php');
                exit;
            } else {
                $erro = 'E-mail ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao processar o login: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Área Administrativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background-color: #0066cc;
            border-color: #0066cc;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3>Área Administrativa</h3>
                <p class="mb-0">Sistema de Reserva de Salas</p>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $erro; ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="seu.email@usp.br" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <a href="../index.php" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Voltar para a página inicial
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="mt-5 p-3 bg-light text-center">
        <p>Sistema de Reserva de Salas - BCRP-USP &copy; <?php echo date('Y'); ?></p>
        <p>Desenvolvido por <a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Fonte-Boa Lázaro Torres</a> idealizado por <a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Robson de Paula Araujo</a></p>
    </footer>

</body>
>>>>>>> fc66e454e6b4da9575f2252dfdc7bf544e9b1f52
</html>