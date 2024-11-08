<?php

require_once 'models/Produto.php';
require_once 'validators/ProdutoValidator.php';

class ProdutoController {
    private $produto;
    private $db;

    public function __construct($db) {
        $this->produto = new Produto($db);
        $this->db = $db;
    }

    public function listar() {
        $produtos = $this->produto->listar();
        echo json_encode($produtos);
    }

    public function criar() {
        $data = json_decode(file_get_contents("php://input"));

        $validacao = ProdutoValidator::validar($data, $this->db);

        if ($validacao === true) {
            try {
                $this->produto->criar($data->nome, $data->preco, $data->descricao, $data->quantidade);
                http_response_code(201);
                echo json_encode(["message" => "Produto cadastrado com sucesso!"]);
            } catch (\Throwable $th) {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao criar produto!"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["errors" => $validacao]);
        }
    }

    public function editar($id) {
        $data = json_decode(file_get_contents("php://input"));

        $validacao = ProdutoValidator::validar($data, $this->db);
        if ($validacao === true) {
            try {
                $this->produto->editar($id, $data->nome, $data->preco, $data->descricao, $data->quantidade);
                http_response_code(200);
                echo json_encode(["message" => "Produto atualizado com sucesso!"]);
            } catch (\Throwable $th) {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao atualizar produto!"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["errors" => $validacao]);
        }
    }

    public function excluir($id) {
        if (isset($id)) {
            try {
                $this->produto->excluir($id);
                http_response_code(200);
                echo json_encode(["message" => "Produto excluído com sucesso!"]);
            } catch (\Throwable $th) {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao excluir produto!"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID inválido!"]);
        }
    }
}
