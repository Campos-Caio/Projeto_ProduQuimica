<?php

require_once 'config/db.php';
require_once 'controllers/ProdutoController.php';
require_once 'controllers/ClienteController.php';
require_once 'Router.php';

header("Content-type: application/json; charset=UTF-8");

$router = new Router(); 

$produtoController = new ProdutoController($pdo);
$clienteController = new ClienteController($pdo);

//Rotas dos protudos
$router->add('GET', '/produtos', [$produtoController, 'listar']);
$router->add('GET', '/produtos/{id}', [$produtoController, 'getById']);
$router->add('POST', '/produtos', [$produtoController, 'criar']);
$router->add('DELETE', '/produtos/{id}', [$produtoController, 'excluir']);
$router->add('PUT', '/produtos/{id}', [$produtoController, 'editar']);

//Rotas de clientes 
$router->add('GET', '/clientes', [$clienteController, 'listar']);
$router->add('GET', '/clientes/{id}', [$clienteController, 'getById']);
$router->add('POST', '/clientes', [$clienteController, 'criar']);
$router->add('DELETE', '/clientes/{id}', [$clienteController, 'excluir']);
$router->add('PUT', '/clientes/{id}', [$clienteController, 'editar']);


$requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$pathItems = explode("/", $requestedPath);
$requestedPath = "/" . $pathItems[3] . ($pathItems[4] ? "/" . $pathItems[4] : "");

$router->dispatch($requestedPath);
