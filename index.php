<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Origin: *");
    exit();
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");

// Obtém a rota solicitada
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define os caminhos para o frontend e backend
$frontendPath = __DIR__ . "/src/frontend/pages";
$backendRouter = __DIR__ . "/src/backend/routes/router.php";

// Roteamento básico
switch ($path) {
    // Página inicial
    case "/projeto-prodQuimica/":
    case "/projeto-prodQuimica/index.php":
        header("Location: /projeto-prodQuimica/src/frontend/pages/home.html");
        exit();

    // Redirecionar para o roteador do backend
    case (preg_match("/^\/projeto-prodQuimica\/src\/backend\/routes\/.*/", $path) ? true : false):
        // Aqui estamos verificando se a URL corresponde a algo no backend, como o router.php
        require_once $backendRouter;
        break;

    // Rota para páginas HTML do frontend
    case "/projeto-prodQuimica/cadastroClientes":
        require_once "$frontendPath/cadastroClientes.html";
        break;

    case "/projeto-prodQuimica/cadastroProdutos":
        require_once "$frontendPath/cadastroProdutos.html";
        break;

    // Caso não encontre a rota
    default:
        http_response_code(404);
        echo "Página não encontrada.";
        break;
}
