<?php

$host = '127.0.0.1:3306'; 
$user = 'root'; 
$password = 'root';
$dbname = 'produquimica';

try {
    // Conexão ao MySQL
    $conn = new mysqli($host, $user, $password);

    if ($conn->connect_error) {
        throw new Exception('Erro ao conectar ao Banco de Dados: ' . $conn->connect_error);
    }

    // Criação do banco de dados
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
    if (!$conn->query($sql)) {
        throw new Exception('Erro ao criar banco de dados: ' . $conn->error);
    }

    // Seleciona o banco de dados
    $conn->select_db($dbname);

    // Criação da tabela clientes
    $sql = "CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE,
        telefone VARCHAR(20),
        endereco VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if (!$conn->query($sql)) {
        throw new Exception('Erro ao criar tabela "clientes": ' . $conn->error);
    }

    // Criação da tabela produtos
    $sql = "CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        quantidade INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    if (!$conn->query($sql)) {
        throw new Exception('Erro ao criar tabela "produtos": ' . $conn->error);
    }

    error_log("Banco de dados e tabelas criados com sucesso!");

} catch (Exception $e) {
    error_log("Erro ao configurar banco de dados: " . $e->getMessage());
    die("Erro crítico no sistema. Contate o suporte técnico.");
} finally {
    $conn->close();
}
