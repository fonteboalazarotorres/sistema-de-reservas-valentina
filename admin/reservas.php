<<<<<<< HEAD
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

$buscaNome = isset($_GET['buscaNome']) ? trim($_GET['buscaNome']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$porPagina = 20;
$offset = ($page - 1) * $porPagina;

$where = [];
$params = [];

if ($buscaNome !== '') {
    $where[] = "r.nome_completo LIKE :buscaNome";
    $params[':buscaNome'] = "%{$buscaNome}%";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Conta total para paginação
$sqlCount = "
    SELECT COUNT(*) 
    FROM reservas r
    INNER JOIN salas s ON r.sala_id = s.id
    $whereSql
";
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute($params);
$totalRegistros = (int) $stmtCount->fetchColumn();
$totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

// Busca apenas a página atual
$sql = "
    SELECT 
        r.id,
        r.nome_completo,
        r.vinculo,
        r.data_reserva,
        r.hora_entrada,
        r.hora_saida,
        r.status,
        s.nome AS sala_nome
    FROM reservas r
    INNER JOIN salas s ON r.sala_id = s.id
    $whereSql
    ORDER BY r.data_reserva DESC, r.hora_entrada DESC, r.id DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

function montarQuery(array $extra = []) {
    $query = $_GET;
    foreach ($extra as $k => $v) {
        $query[$k] = $v;
    }
    return http_build_query($query);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas | Administração</title>
    <link rel="icon" href="../bcrp-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/gh/ifrederico/accessible-web-widget@latest/dist/accessible-web-widget.min.js"></script>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse">
                <?php include 'menu_lateral.php'; ?>
            </aside>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <form method="get" class="row g-2 mb-3" id="form-busca-nome">
                    <div class="col-md-6">
                        <input
                            type="text"
                            name="buscaNome"
                            id="buscaNome"
                            class="form-control"
                            placeholder="Pesquisar pelo nome"
                            value="<?= htmlspecialchars($buscaNome) ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                    <?php if ($buscaNome !== ''): ?>
                    <div class="col-auto">
                        <a href="?" class="btn btn-outline-secondary">Limpar</a>
                    </div>
                    <?php endif; ?>
                </form>

                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            <h5 class="mb-0">Reservas</h5>
                        </div>
                        <small class="text-muted">
                            <?= $totalRegistros ?> registro(s)
                        </small>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Sala</th>
                                        <th>Data</th>
                                        <th>Entrada</th>
                                        <th>Saída</th>
                                        <th>Status</th>
                                        <th style="width: 160px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($reservas)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                Nenhuma reserva encontrada.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($reservas as $r): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($r['nome_completo']) ?></td>
                                                <td><?= htmlspecialchars($r['sala_nome']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($r['data_reserva'])) ?></td>
                                                <td><?= htmlspecialchars(substr($r['hora_entrada'], 0, 5)) ?></td>
                                                <td><?= htmlspecialchars(substr($r['hora_saida'], 0, 5)) ?></td>
                                                <td>
                                                    <span class="badge <?=
                                                        $r['status'] === 'confirmada' ? 'bg-success' :
                                                        ($r['status'] === 'pendente' ? 'bg-warning text-dark' :
                                                        ($r['status'] === 'concluida' ? 'bg-primary' : 'bg-danger'))
                                                    ?>">
                                                        <?= htmlspecialchars(ucfirst($r['status'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-info text-white"
                                                            data-url="ver_reserva.php?id=<?= (int)$r['id'] ?>"
                                                            data-title="Detalhes da Reserva"
                                                            onclick="abrirModalReserva(this)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <a
                                                            href="editar_reserva.php?id=<?= (int)$r['id'] ?>"
                                                            class="btn btn-sm btn-primary"
                                                            target="_blank" rel="noopener">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <a
                                                            href="excluir_reserva.php?id=<?= (int)$r['id'] ?>"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Deseja excluir esta reserva?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($totalPaginas > 1): ?>
                            <nav aria-label="Paginação das reservas">
                                <ul class="pagination justify-content-center mt-4 mb-0">
                                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?<?= montarQuery(['page' => $page - 1]) ?>">Anterior</a>
                                    </li>

                                    <?php
                                    $inicio = max(1, $page - 2);
                                    $fim = min($totalPaginas, $page + 2);
                                    for ($i = $inicio; $i <= $fim; $i++):
                                    ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?<?= montarQuery(['page' => $i]) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <li class="page-item <?= $page >= $totalPaginas ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?<?= montarQuery(['page' => $page + 1]) ?>">Próxima</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="reservaModalLabel">Reserva</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4" id="modalBodyContent">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 p-3 bg-light text-center">
        <p>Sistema de Reservas VALENTINA &copy; <?= date('Y') ?></p>
        <p>
            Desenvolvido por
            <a href="http://lattes.cnpq.br/4623045728159220" target="_blank" rel="noopener noreferrer">Fonte-Boa Lázaro Torres</a>
            idealizado por
            <a href="https://lattes.cnpq.br/1572390366115265" target="_blank" rel="noopener noreferrer">Robson de Paula Araujo</a>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const reservaModalElement = document.getElementById('reservaModal');
        const reservaModal = new bootstrap.Modal(reservaModalElement);
        const modalTitle = document.getElementById('reservaModalLabel');
        const modalBody = document.getElementById('modalBodyContent');

        async function abrirModalReserva(button) {
            const url = button.getAttribute('data-url');
            const title = button.getAttribute('data-title') || 'Reserva';

            modalTitle.textContent = title;
            modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>';
            reservaModal.show();

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) throw new Error('Erro na resposta');
                const html = await response.text();
                modalBody.innerHTML = html;
            } catch (e) {
                modalBody.innerHTML = '<div class="alert alert-danger">Erro ao carregar dados.</div>';
            }
        }
    </script>
</body>
=======
<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$buscaNome = isset($_GET['buscaNome']) ? trim($_GET['buscaNome']) : '';

$sql = "
    SELECT r.id, r.nome_completo, r.vinculo, r.data_reserva, r.hora_entrada, r.hora_saida, r.status, s.nome as sala_nome
    FROM reservas r
    JOIN salas s ON r.sala_id = s.id
    WHERE 1
";

$params = [];

if ($buscaNome !== '') {
    $sql .= " AND r.nome_completo LIKE ?";
    $params[] = "%$buscaNome%";
}

$sql .= " ORDER BY r.data_reserva DESC, r.hora_entrada DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas | Administração</title>
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


                <form method="get" class="d-flex mb-3" onsubmit="return false;" id="form-busca-nome">
                <input 
                    type="text" 
                    name="buscaNome" 
                    id="buscaNome" 
                    class="form-control me-2" 
                    placeholder="Pesquisar pelo nome"
                    value="<?= htmlspecialchars($buscaNome) ?>">
                <button type="submit" class="btn btn-primary" onclick="pesquisarPorNome()">
                    <i class="fas fa-search"></i> Buscar
                </button>
                </form>

                <table>
                <!-- cabeçalho & etc -->
                <tbody>
                    <?php foreach ($reservas as $r): ?>
                    <tr>
                      
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>

                <script>
                function pesquisarPorNome() {
                const nome = document.getElementById('buscaNome').value.trim();
                const url = new URL(window.location.href);
                if (nome.length > 0) {
                    url.searchParams.set('buscaNome', nome);
                } else {
                    url.searchParams.delete('buscaNome');
                }
                window.location.href = url.toString();
                }
                </script>


                <div class="card border-0 shadow mb-4 mt-4">
                    <div class="card-header bg-white d-flex align-items-center">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        <h5 class="mb-0">Reservas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Sala</th>
                                        <th>Data</th>
                                        <th>Entrada</th>
                                        <th>Saída</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas as $r): ?>
                                    <tr>
                                        <td><?=htmlspecialchars($r['nome_completo'])?></td>
                                        <td><?=htmlspecialchars($r['sala_nome'])?></td>
                                        <td><?=date('d/m/Y', strtotime($r['data_reserva']))?></td>
                                        <td><?=substr($r['hora_entrada'],0,5)?></td>
                                        <td><?=substr($r['hora_saida'],0,5)?></td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                    if($r['status']=='confirmada') echo 'bg-success';
                                                    elseif($r['status']=='pendente') echo 'bg-warning text-dark';
                                                    elseif($r['status']=='concluida') echo 'bg-primary';
                                                    else echo 'bg-danger';
                                                ?>">
                                                <?=ucfirst($r['status'])?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info text-white"
                                                        onclick="abrirModalReserva('ver_reserva.php?id=<?=$r['id']?>', 'Detalhes da Reserva')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary"
                                                       onclick="abrirLink(<?= $r['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="excluir_reserva.php?id=<?=$r['id']?>" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Deseja excluir esta reserva?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
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

    <!-- Modal para visualização/edição -->
    <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="reservaModalLabel">Reserva</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body p-4" id="modalBodyContent">
            <!-- Conteúdo será carregado via AJAX -->
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

    function abrirLink(id) {
    // Monta o link com o ID
    const url = `editar_reserva.php?id=${id}`;
    
    // Abre o link em uma nova aba (ou use window.location.href para redirecionar na mesma)
    window.open(url, '_blank');
}

    function abrirModalReserva(url, titulo) {
        const modal = new bootstrap.Modal(document.getElementById('reservaModal'));
        document.getElementById('reservaModalLabel').textContent = titulo;
        document.getElementById('modalBodyContent').innerHTML =
            '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>';
        fetch(url)
          .then(response => response.text())
          .then(html => {
              document.getElementById('modalBodyContent').innerHTML = html;
              modal.show();
          })
          .catch(() => {
              document.getElementById('modalBodyContent').innerHTML =
                  '<div class="alert alert-danger">Erro ao carregar dados.</div>';
              modal.show();
          });
    }

    function pesquisarPorNome() {
    const nome = document.getElementById('buscaNome').value.trim();
    const url = new URL(window.location.href);
    if (nome.length > 0) {
        url.searchParams.set('buscaNome', nome);
    } else {
        url.searchParams.delete('buscaNome');
    }
    window.location.href = url.toString();
    }


    </script>
</body>
>>>>>>> fc66e454e6b4da9575f2252dfdc7bf544e9b1f52
</html>