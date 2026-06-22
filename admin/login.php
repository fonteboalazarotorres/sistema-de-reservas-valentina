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
</html>