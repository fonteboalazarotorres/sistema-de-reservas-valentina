<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

require_once 'config.php';

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');
$agora = date('H:i:s');

$salas = $pdo->query("SELECT id, nome, capacidade FROM salas ORDER BY nome")->fetchAll();

$resultado = [];

foreach ($salas as $sala) {
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

    $resultado[] = [
        'id' => $sala['id'],
        'nome' => $sala['nome'],
        'capacidade' => $sala['capacidade'],
        'status' => $status,
        'liberacao' => $liberacao,
    ];
}

header('Content-Type: application/json');
echo json_encode($resultado);