--
-- Copyright (c) 2026 Fonte-Boa Lázaro Torres
-- Licensed under the Apache License, Version 2.0
-- See: https://www.apache.org/licenses/LICENSE-2.0
--

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS sistema_valentina;
USE sistema_valentina;

-- Tabela de salas
CREATE TABLE salas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    capacidade INT NOT NULL,
    descricao VARCHAR(255),
    status ENUM('disponivel', 'manutencao') DEFAULT 'disponivel'
);

-- Tabela de equipamentos
CREATE TABLE equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de usuários (administradores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'gestor') NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de reservas
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    numero_usp VARCHAR(20) NOT NULL,
    vinculo ENUM('Graduação', 'Pós-graduação', 'Docente', 'Servidor', 'Externo') NOT NULL,
    sala_id INT NOT NULL,
    quantidade_pessoas INT NOT NULL,
    data_reserva DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_saida TIME NOT NULL,
    status ENUM('pendente', 'confirmada', 'cancelada', 'concluida') DEFAULT 'confirmada',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sala_id) REFERENCES salas(id)
);

-- Tabela de relação entre reservas e equipamentos
CREATE TABLE reserva_equipamentos (
    reserva_id INT NOT NULL,
    equipamento_id INT NOT NULL,
    PRIMARY KEY (reserva_id, equipamento_id),
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id)
);

-- Inserir equipamentos padrão
INSERT INTO equipamentos (nome) VALUES 
('Cabo HDMI'),
('Caneta/Apagador'),
('Controle remoto');

-- Inserir salas padrão
INSERT INTO salas (nome, capacidade, descricao) VALUES 
('Sala 1', 3, 'Sala para até 03 pessoas'),
('Sala 2', 3, 'Sala para até 03 pessoas'),
('Sala 3', 3, 'Sala para até 03 pessoas'),
('Sala 4', 3, 'Sala para até 03 pessoas'),
('Sala 5', 3, 'Sala para até 03 pessoas'),
('Sala 6', 3, 'Sala para até 03 pessoas'),
('Sala 7', 3, 'Sala para até 03 pessoas'),
('Sala 8', 3, 'Sala para até 03 pessoas'),
('Sala 9', 3, 'Sala para até 03 pessoas'),
('Sala 10', 4, 'Sala acima de 04 pessoas'),
('Sala 11', 4, 'Sala acima de 04 pessoas'),
('Sala 12', 4, 'Sala acima de 04 pessoas'),
('Sala 13', 4, 'Sala acima de 04 pessoas'),
('Sala 14', 4, 'Sala acima de 04 pessoas'),
('Sala 15', 4, 'Sala acima de 04 pessoas');

-- Inserir usuário admin padrão
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@usp.br', '$2y$10$zSxRnXcI7moTME02VhCpwuuBUu.NreCr8RMrPe5b0Q/kZHZNOg/XO', 'admin');
-- Senha: admin123
