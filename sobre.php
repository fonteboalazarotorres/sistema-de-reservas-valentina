<?php
/**
* Copyright (c) 2026 Fonte-Boa Lázaro Torres
* Licensed under the Apache License, Version 2.0
* See: https://www.apache.org/licenses/LICENSE-2.0
*/
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sobre o Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="bcrp-logo.png" type="image/x-icon">
    <style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #38b6ff 0%, #2776e1 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
    }
    .container {
        max-width: 600px;
        margin: 48px auto 0 auto;
        background: rgba(255,255,255,0.16);
        backdrop-filter: blur(8px);
        border-radius: 18px;
        box-shadow: 0 10px 32px rgba(0,0,0,0.18);
        padding: 38px 28px;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #fff;
        font-weight: 800;
    }
    .sobre-texto {
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 30px;
    }
    .creditos {
        margin-top: 24px;
        text-align: center;
        font-size: 1rem;
        color: #fff;
        font-weight: 600;
    }
    .autores {
        display: flex;
        gap: 32px;
        justify-content: center;
        align-items: center;
        margin-top: 28px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .autor-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 160px;
    }
    .autor-foto {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.13);
        margin-bottom: 12px;
    }
    .autor-foto img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .autor-nome {
        color: #ffd700;
        font-weight: 650;
        font-size: 1rem;
        text-align: center;
        margin-bottom: 2px;
    }
    .autor-lattes a {
        color: #fff;
        font-size: 0.95rem;
        text-decoration: underline;
        transition: color 0.2s;
    }
    .autor-lattes a:hover {
        color: #ffd700;
        text-decoration: none;
    }
    @media(max-width: 600px) {
        .container { padding: 18px 6px; }
        .autores { gap: 12px; }
        .autor-card { width: 100px; }
        .autor-foto { width: 58px; height: 58px; }
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sobre o Sistema de Reservas VALENTINA</h2>
        <div class="sobre-texto">
            Este sistema foi desenvolvido para facilitar o controle de reservas de salas em ambientes institucionais, trazendo eficiência e praticidade à gestão de espaços.<br><br>
            Com interface moderna e processos automatizados, permite consultar disponibilidade em tempo real, criar e gerenciar reservas, inserir recursos e gerar relatórios.<br><br>
            O desenvolvimento seguiu princípios de usabilidade, segurança, acessibilidade e integração, beneficiando tanto usuários quanto administradores.
        </div>
        <div class="autores">
            <div class="autor-card">
                <div class="autor-foto">
                    <img src="https://servicosweb.cnpq.br/wspessoa/servletrecuperafoto?tipo=1&id=K1598500Y3" alt="Foto Fonte-Boa Lázaro Torres">
                </div>
                <div class="autor-nome">Fonte-Boa Lázaro Torres</div>
                <div class="autor-lattes"><a href="http://lattes.cnpq.br/4623045728159220" target="_blank">Desenvolvedor</a></div>
            </div>
            <div class="autor-card">
                <div class="autor-foto">
                    <img src="https://servicosweb.cnpq.br/wspessoa/servletrecuperafoto?tipo=1&id=K4217604Z6" alt="Foto Robson de Paula Araújo">
                </div>
                <div class="autor-nome">Robson de Paula Araújo</div>
                <div class="autor-lattes"><a href="https://lattes.cnpq.br/1572390366115265" target="_blank">Idealizador</a></div>
            </div>
        </div>
        <div class="creditos">
            <hr>
            Sistema desenvolvido por <span style="color:#ffd700;">Fonte-Boa Lázaro Torres</span><br>
            Projeto idealizado por <span style="color:#ffd700;">Robson de Paula Araújo</span>
        </div>
    </div>
</body>
</html>
