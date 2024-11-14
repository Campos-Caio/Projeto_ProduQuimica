<?php
require_once __DIR__ ."../../controllers/ClienteController.php";
require_once __DIR__ . "../../controllers/ProdutoController.php";
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: * ");

class Router{

    private $connection; 

    public function __construct($db){
        $this->connection = $db;
    }

    public function route($url){
        $parsedUrl = parse_url($url);
        $path = trim($parsedUrl["path"],"/");
        
        if(strpos($path,"produto") !== false){
            require_once 'controllers/ProdutoController.php'; 
            $controller = new ProdutoController($this->connection);
        }elseif(strpos($path,'cliente') !== false){
            require_once 'controllers/ClienteController.php'; 
            $controller = new ClienteController($this->connection);
        }else{
            echo 'Rota nao encontrada!'; 
            return; 
        }

        switch($path){
            case 'produto/listar':
            case 'cliente/listar':
                $controller->listar(); 
                break; 
            case 'produto/criar'; 
            case 'cliente/criar':
                $controller->criar();
                break;
            case 'produto/editar'; 
            case 'cliente/editar':
                $id = $_GET['id'] ?? null;
                $controller->editar($id);
                break;
            case 'produto/excluir'; 
            case 'cliente/excluir':
                $id = $_GET['id'] ?? null;
                $controller->excluir($id);
                break;  
            default:
                echo 'Rota nao encontrada!';    
                break;
        }
    }    
}