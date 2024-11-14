<?php

require_once 'models/Cliente.php'; 

class ClienteController {
    private $cliente;
    private $db; 

    public function __construct($db){
        $this->cliente = new Cliente($db);
        $this->db = $db;
    }

    public function listar(){
        $clientes = $this->cliente->listar();
        echo json_encode($clientes);
    }

    public function criar(){
        $data = json_decode(file_get_contents("php://input"));

        $validacao = ClienteValidator::validar($data, $this->db);

        if($validacao === true){
            try{
                $this->cliente->criar($data->nome, $data->email, $data->telefone, $data->endereco);
                http_response_code(201); 
                echo json_encode(["message" => "Cliente cadastrado com sucesso!"]);
            }catch(\Throwable $th){
                http_response_code(500); 
                echo json_encode(["message"=> "Erro ao criar Cliente!"]);
            }
        }else{
            http_response_code(400); 
            echo json_encode(["message"=> "$validacao"]);
        }
    }


    public function editar($id){
        $data = json_decode(file_get_contents("php://input"));

        $validacao = ClienteValidator::validar($data, $this->db);
        if($validacao === true){
            try{
                $this->cliente->editar($id, $data->nome, $data->email, $data->telefone, $data->endereco);
                http_response_code(200); 
                echo json_encode(["message" => "Cadastro de cliente atualizado com sucesso!"]);
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
                $this->cliente->excluir($id);
                http_response_code(200); 
                echo json_encode(["message"=> "Cadastro de cliente excluido com sucesso!"]);
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