<?php

use system\Control\Helpers;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$_POST = json_decode(file_get_contents('php://input'), true);

$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->addRoute('GET', '/' , 'system\WebControl\SiteControlador@index');

    $r->addRoute('POST', '/updateOrder/', 'system\WebControl\SiteControlador@updateOrder');
    
    $r->addRoute('GET', '/delete/{id:\d+}', 'system\WebControl\SiteControlador@deletar');
    
    $r->addRoute('POST', '/editar/', 'system\WebControl\SiteControlador@editar');
    
    $r->addRoute('POST', '/salvar/', 'system\WebControl\SiteControlador@salvar');
    
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Rota não encontrada ou método não permitido']);
        http_response_code(404);
        exit;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Rota não encontrada ou método não permitido']);
        http_response_code(404);
        exit;
    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = ($httpMethod == 'POST') ? $_POST : $routeInfo[2];
        
        $handlerParts = explode('@', $handler);
        $controllerClass = $handlerParts[0];
        $controllerMethod = $handlerParts[1];

        $siteController = new $controllerClass();
        $siteController->$controllerMethod($vars); 
        break;

}
