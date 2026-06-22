<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once '../config.php';
session_start();
if (!isset($_SESSION['usuario_id'])) header('Location: login.php');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM reservas WHERE id=?");
    $stmt->execute([$id]);
}
header('Location: reservas.php');
exit;