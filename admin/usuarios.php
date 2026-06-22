<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once '../config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar se usuário logado é admin
$stmt = $pdo->prepare("SELECT tipo FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario_atual = $stmt->fetch();
if (!$usuario_atual || $usuario_atual['tipo'] !== 'admin') {
    echo '<div class="alert alert-danger">Acesso negado. Área restrita a administradores. <a href="index.php">Voltar</a></div>';
    exit;
}

// Tratar ações de criação, edição e exclusão
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
        $id = intval($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tipo = $_POST['tipo'] ?? 'aluno'; // padrão aluno
        $senha = trim($_POST['senha'] ?? '');

        if ($acao === 'cadastrar') {
            if ($nome && $email && $tipo && $senha) {
                // Verifica se email já existe
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
                $stmtCheck->execute([$email]);
                if ($stmtCheck->fetchColumn() > 0) {
                    $msg = "Email já cadastrado.";
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmtIns = $pdo->prepare("INSERT INTO usuarios (nome, email, tipo, senha) VALUES (?, ?, ?, ?)");
                    $stmtIns->execute([$nome, $email, $tipo, $senha_hash]);
                    $msg = "Usuário cadastrado com sucesso.";
                }
            } else {
                $msg = "Preencha todos os campos para cadastro.";
            }
        } elseif ($acao === 'editar' && $id > 0) {
            if ($nome && $email && $tipo) {
                // Atualiza usuário, senha opcional
                if ($senha) {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmtUp = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, tipo=?, senha=? WHERE id=?");
                    $stmtUp->execute([$nome, $email, $tipo, $senha_hash, $id]);
                } else {
                    $stmtUp = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, tipo=? WHERE id=?");
                    $stmtUp->execute([$nome, $email, $tipo, $id]);
                }
                $msg = "Usuário atualizado com sucesso.";
            } else {
                $msg = "Preencha todos os campos para atualização.";
            }
        } elseif ($acao === 'excluir' && $id > 0) {
            // Não permite excluir ele mesmo
            if ($id == $_SESSION['usuario_id']) {
                $msg = "Você não pode excluir seu próprio usuário.";
            } else {
                $stmtDel = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmtDel->execute([$id]);
                $msg = "Usuário excluído com sucesso.";
            }
        }
    }
}

// Buscar todos os usuários
$usuarios = $pdo->query("SELECT id, nome, email, tipo FROM usuarios ORDER BY nome")->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Gerenciamento de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" href="../bcrp-logo.png" type="image/x-icon">
</head>
<body class="bg-light p-3">
<div class="container">
    <h1 class="mb-4 text-primary">Gerenciamento de Usuários</h1>

<p>Você pode retornar ao <a href="login.php" class="button">Início</a> ou <a href="#" onclick="window.print(); return false;">Imprimir página</a></p>

    <?php if($msg): ?>
        <div class="alert alert-info"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <!-- Formulário para novo usuário -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold bg-primary text-white">Cadastrar Novo Usuário</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="acao" value="cadastrar" />
                <div class="col-md-4">
                    <label for="nome" class="form-label">Nome completo</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="user">Funcionário</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-user-plus"></i> Cadastrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de usuários -->
    <div class="card shadow-sm">
        <div class="card-header fw-bold bg-secondary text-white">Usuários Cadastrados</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th>Último Login</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): ?>
                    <tr>
                        <td><?=htmlspecialchars($u['nome'])?></td>
                        <td><?=htmlspecialchars($u['email'])?></td>
                        <td><?=htmlspecialchars(ucfirst($u['tipo']))?></td>
                        <td><?=!$u['ultimo_login'] ? '-' : date('d/m/Y H:i', strtotime($u['ultimo_login']))?></td>
                        <td>
                            <!-- Botão Editar abre um modal ou formulário embutido -->
                            <button class="btn btn-sm btn-primary" onclick="abrirEditUsuario(<?= $u['id']?>)"><i class="fas fa-edit"></i></button>
                            <!-- Botão Excluir com confirmação -->
                            <form method="post" style="display:inline-block" onsubmit="return confirm('Confirma exclusão do usuário?');">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>" />
                                <input type="hidden" name="acao" value="excluir" />
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(!$usuarios): ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhum usuário encontrado.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de edição simplificado -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditarUsuario" method="post">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuário</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id" />
          <input type="hidden" name="acao" value="editar" />
          <div class="mb-3">
            <label for="edit_nome" class="form-label">Nome completo</label>
            <input type="text" id="edit_nome" name="nome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_email" class="form-label">E-mail</label>
            <input type="email" id="edit_email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_tipo" class="form-label">Tipo</label>
            <select id="edit_tipo" name="tipo" class="form-select" required>
              <option value="user">Funcionário</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="edit_senha" class="form-label">Nova senha (deixe em branco para manter)</label>
            <input type="password" id="edit_senha" name="senha" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const modalEditarUsuario = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));

function abrirEditUsuario(id) {
    // buscar os dados do usuário via AJAX para preencher o formulário
    $.getJSON('buscar_usuario.php', { id }, function(data) {
        if(data.success) {
            $('#edit_id').val(data.usuario.id);
            $('#edit_nome').val(data.usuario.nome);
            $('#edit_email').val(data.usuario.email);
            $('#edit_tipo').val(data.usuario.tipo);
            $('#edit_senha').val('');
            modalEditarUsuario.show();
        } else {
            alert('Usuário não encontrado');
        }
    });
}

$('#formEditarUsuario').submit(function(e) {
    e.preventDefault();
    const formData = $(this).serialize();
    $.post('', formData, function(resp) {
        if(resp.includes('sucesso') || resp == 'Usuário atualizado com sucesso.') {
            alert(resp);
            modalEditarUsuario.hide();
            location.reload();
        } else {
            alert('Erro ao atualizar: ' + resp);
        }
    });
});
</script>