<?php
use ppil\util\AppContainer;
use ppil\view\ViewRendering;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/vendor/autoload.php';

// Creation de l'application slim
$app = AppContainer::getInstance();
$app->addRoutingMiddleware();

// Affichage des erreur (Dev Only)
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Creation de l'index (page pricipale)
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write(ViewRendering::render("Hello, world!", "Home page"));
    return $response;
})->setName('root');

$app->run();