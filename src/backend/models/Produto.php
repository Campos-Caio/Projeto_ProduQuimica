<?php
require_once 'config/datase/database.php'; 

class Produto{
    private $connection; 

    public function __construct($db){
        $this->connection = $db;
    }

    public function criar($nome, $categoria, $marca, $codigo_barra, $preco_custo, $preco_venda){
        $sql = "INSERT INTO produtos (nome, categoria, marca, codigo_barra, preco_custo, preco_venda) VALUES (?,?,?,?,?,?)";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("ssssdd", $nome, $categoria, $marca, $codigo_barra, $preco_custo, $preco_venda);
        return $smt->execute(); 
    }

    public function listar(){
        $sql = "SELECT * FROM produtos";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id){
        $sql = "SELECT * FROM produtos where id = ?";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("i", $id);
        $smt->execute();
        return $smt->get_result()->fetch_assoc();
    }

    public function editar($id, $nome, $categoria, $marca, $codigo_barra, $preco_custo, $preco_venda){
        $sql = "UPDATE produtos SET nome = ?, categoria = ?, marca = ?, codigo_barra = ?, preco_custo = ?, preco_venda = ? WHERE id = ?";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("ssssddi", $nome, $categoria, $marca, $codigo_barra, $preco_custo, $preco_venda,$id);
        return $smt->execute();
    }

    public function excluir($id){
        $sql = "DELETE FROM produtos WHERE id = ?";
        $smt = $this->connection->prepare($sql);
        $smt->bind_param("i", $id);
        return $smt->execute();
    }
}