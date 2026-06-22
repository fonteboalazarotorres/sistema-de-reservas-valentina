<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once 'config.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit;
}

$data_reserva = $_POST['data_reserva'] ?? null;
$hora_entrada = $_POST['hora_entrada'] ?? null;
$hora_saida = $_POST['hora_saida'] ?? null;
$quantidade_pessoas = intval($_POST['quantidade_pessoas'] ?? 0);

if (!$data_reserva || !$hora_entrada || !$hora_saida || $quantidade_pessoas <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Parâmetros inválidos']);
    exit;
}

// Buscar salas que suportam a quantidade de pessoas
$stmtSalas = $pdo->prepare("SELECT id, nome, capacidade FROM salas WHERE capacidade >= ?");
$stmtSalas->execute([$quantidade_pessoas]);
$salas = $stmtSalas->fetchAll();

$salas_disponiveis = [];

// Verificar para cada sala se não há conflito
$stmtConflito = $pdo->prepare("
    SELECT COUNT(*) FROM reservas
    WHERE sala_id = ? 
      AND data_reserva = ? 
      AND status IN ('pendente','confirmada')
      AND (
        (hora_entrada < ? AND hora_saida > ?) OR
        (hora_entrada < ? AND hora_saida > ?) OR
        (hora_entrada >= ? AND hora_saida <= ?)
      )
");

foreach ($salas as $sala) {
    $stmtConflito->execute([
        $sala['id'],
        $data_reserva,
        $hora_saida, $hora_entrada,
        $hora_saida, $hora_entrada,
        $hora_entrada, $hora_saida
    ]);
    $conflitos = $stmtConflito->fetchColumn();
    if ($conflitos == 0) {
        $salas_disponiveis[] = $sala;
    }
}

echo json_encode(['status' => 'success', 'salas' => $salas_disponiveis]);