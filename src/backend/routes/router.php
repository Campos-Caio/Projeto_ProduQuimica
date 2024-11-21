<?php
require_once __DIR__ . "/../config/database/database.php";
require_once __DIR__ . "../../controllers/ClienteController.php";
require_once __DIR__ . "../../controllers/ProdutoController.php";

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");

class Router {
    private $connection;

    public function __construct($db) {
        $this->connection = $db;
    }

    public function route() {
        // Obtendo parâmetros da query string
        $resource = $_GET['resource'] ?? null;
        $action = $_GET['action'] ?? null;
        $id = $_GET['id'] ?? null;

        if (!$resource || !$action) {
            echo json_encode(['error' => 'Parâmetros "resource" e "action" são obrigatórios.']);
            return;
        }

        // Determinando o controller com base no recurso
        $controller = null;
        if ($resource === 'clientes') {
            require_once 'controllers/ClienteController.php';
            $controller = new ClienteController($this->connection);
        } elseif ($resource === 'produtos') {
            require_once 'controllers/ProdutoController.php';
            $controller = new ProdutoController($this->connection);
        } else {
            echo json_encode(['error' => 'Recurso não encontrado.']);
            return;
        }

        // Chamando a ação correspondente
        switch ($action) {
            case 'listar':
                $controller->listar();
                break;
            case 'cadastrar':
                $controller->criar();
                break;
            case 'editar':
                if ($id) {
                    $controller->editar($id);
                } else {
                    echo json_encode(['error' => 'Parâmetro "id" é obrigatório para editar.']);
                }
                break;
            case 'excluir':
                if ($id) {
                    $controller->excluir($id);
                } else {
                    echo json_encode(['error' => 'Parâmetro "id" é obrigatório para excluir.']);
                }
                break;
            default:
                echo json_encode(['error' => 'Ação não reconhecida.']);
                break;
        }
    }
}
