<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'sistema_valentina');
define('DB_PASS', 'cvjpU9AhABAnZYy8RfFg');
define('DB_NAME', 'sistema_valentina');

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("set names utf8");
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}


?>
