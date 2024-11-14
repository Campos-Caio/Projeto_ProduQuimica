<?php
require_once 'config/datase/database.php'; 

class Cliente{
    private $connection; 

    public function __construct($db){
        $this->connection = $db;
    }

    public function criar($nome, $email, $telefone, $endereco){
        $sql = "INSERT INTO clientes (nome, email, telefone, endereco) VALUES (?,?,?,?)";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("ssss", $nome, $email, $telefone, $endereco);
        return $smt->execute(); 
    }

    public function listar(){
        $sql = "SELECT * FROM clientes";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id){
        $sql = "SELECT * FROM clientes where id = ?";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("i", $id);
        $smt->execute();
        return $smt->get_result()->fetch_assoc();
    }

    public function editar($id, $nome, $email, $telefone, $endereco){
        $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("ssssi", $nome, $email, $telefone, $endereco, $id);
        return $smt->execute();
    }

    public function excluir($id){
        $sql = "DELETE FROM clientes WHERE id = ?";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("i", $id);
        return $smt->execute();
    }
}