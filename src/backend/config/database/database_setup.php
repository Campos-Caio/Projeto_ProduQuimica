<?php

$host = '127.0.0.1:3306'; 
$user = 'root'; 
$password = 'root';
$dbname = 'produquimica';

try {
    // Conexao ao MySQL sem especificar o banco de dados
    $conn = new mysqli($host, $user, $password);

    if ($conn->connect_error) {
        throw new Exception('Erro ao conectar ao Banco de dados! ' . $conn->connect_error);
    }

    // Criação do banco de dados
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql)) {
        echo 'Banco de dados criado com sucesso!<br>';
    } else {
        throw new Exception('Erro ao criar banco de dados: ' . $conn->error);
    }

    // Seleciona o banco de dados criado
    $conn->select_db($dbname);

    // Criação da tabela clientes
    $sql = "CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE,
        telefone VARCHAR(15),
        endereco VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo 'Tabela "clientes" criada com sucesso!<br>';
    } else {
        throw new Exception('Erro ao criar tabela "clientes": ' . $conn->error);
    }

    // Criação da tabela produtos
    $sql = "CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        quantidade INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo 'Tabela "produtos" criada com sucesso!<br>';
    } else {
        throw new Exception('Erro ao criar tabela "produtos": ' . $conn->error);
    }

    $conn->close(); 
} catch (Exception $e) {
    echo "Erro ao instanciar banco de dados! " . $e->getMessage();
}

