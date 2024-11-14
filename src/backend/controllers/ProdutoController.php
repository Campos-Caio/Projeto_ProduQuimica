<?php

require_once 'models/produto.php'; 

class ProdutoController {
    private $produto;
    private $db; 

    public function __construct($db){
        $this->produto = new produto($db);
        $this->db = $db;
    }

    public function listar(){
        $produtos = $this->produto->listar();
        echo json_encode($produtos);
    }

    public function criar(){
        $data = json_decode(file_get_contents("php://input"));

        $validacao = produtoValidator::validar($data, $this->db);

        if($validacao === true){
            try{
                $this->produto->criar($data->nome, $data->categoria, $data->marca, $data->codigo_barra, $data->preco_custo, $data->preco_venda);
                http_response_code(201); 
                echo json_encode(["message" => "produto cadastrado com sucesso!"]);
            }catch(\Throwable $th){
                http_response_code(500); 
                echo json_encode(["message"=> "Erro ao criar produto!"]);
            }
        }else{
            http_response_code(400); 
            echo json_encode(["message"=> "$validacao"]);
        }
    }


    public function editar($id){
        $data = json_decode(file_get_contents("php://input"));

        $validacao = produtoValidator::validar($data, $this->db);
        if($validacao === true){
            try{
                $this->produto->editar($id, $data->nome, $data->categoria, $data->marca, $data->codigo_barra, $data->preco_custo, $data->preco_venda);
                http_response_code(200); 
                echo json_encode(["message" => "Cadastro de produto atualizado com sucesso!"]);
            }catch(\Throwable $th){
                http_response_code(500); 
                echo json_encode(["message"=> "Erro ao atualizar cadastro!"]);
            }
        }else{
            http_response_code(400); 
            echo json_encode(["message"=> "$validacao"]);
        }
    }


    public function excluir($id){
        if(isset($id)){
            try{
                $this->produto->excluir($id);
                http_response_code(200); 
                echo json_encode(["message"=> "Cadastro de produto excluido com sucesso!"]);
            }catch(\Throwable $th){
                http_response_code(500);
                echo json_encode(["message"=> "Erro ao excluir cadastro!"]);
            }
        }else{
            http_response_code(500);
            echo json_encode(["message"=> "ID invalido!"]);
        }
    }
}