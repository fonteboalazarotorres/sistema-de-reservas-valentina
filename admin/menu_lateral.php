<?php
/**
 * Copyright (c) 2026 Fonte-Boa Lázaro Torres
 * Licensed under the Apache License, Version 2.0
 * See: https://www.apache.org/licenses/LICENSE-2.0
 */
?>

<div class="position-sticky pt-3">
    <div class="text-center mb-4">
        <h3 class="text-white">
            <i class="fas fa-bars"></i> Menu
        </h3>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo ' active'; ?>" href="index.php">
                <i class="fas fa-tachometer-alt"></i> Painel
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='reservas.php') echo ' active'; ?>" href="reservas.php">
                <i class="fas fa-calendar-check"></i> Reservas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='estatisticas.php') echo ' active'; ?>" href="estatisticas.php">
                <i class="fas fa-chart-bar"></i> Estatísticas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='salas.php') echo ' active'; ?>" href="salas.php">
                <i class="fas fa-door-open"></i> Salas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='usuarios.php') echo ' active'; ?>" href="usuarios.php">
                <i class="fas fa-user-group"></i> Gerenciar usuários
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="tutoriais.php">
                <i class="fa fa-question"></i> Tutorial
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../sobre.php">
                <i class="fa fa-info-circle"></i> Sobre o Sistema
            </a>
        </li>        
        <li class="nav-item mt-5">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </li>
    </ul>
</div>