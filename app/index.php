<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
include_once './Controllers/EmpleadoController.php';
include_once './Controllers/AutentificadorController.php';
include_once './Middlewares/CheckSocioMiddleware.php';
include_once './Middlewares/CheckTokenMiddleware.php';
//require_once "./Controllers/EmpleadoController.php";


// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->get('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array('method' => 'GET', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/test', function (Request $request, Response $response) {
    $payload = json_encode(array('method' => 'GET', 'msg' => "Bienvenido a SlimFramework 2023 test"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array('method' => 'POST', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/test', function (Request $request, Response $response) {
    $payload = json_encode(array('method' => 'POST', 'msg' => "Bienvenido a SlimFramework 2023"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->group('/usuarios', function (RouteCollectorProxy $group){
    //Alta
    $group->post('/altaUsuario', \EmpleadoController::class . ':CargarUno')->add(new CheckSocioMiddleware); // verifica que el token pertenezca a un socio 
    $group->delete('/eliminarUsuario', \EmpleadoController::class . ':BorrarUno');
})->add(new CheckTokenMiddleware); //token generico valido

$app->post('/CrearToken', \AutentificadorController::class . ':CrearTokenLogin');



//$app->post('/altaUsuario', \EmpleadoController::class . ':CargarUno');

$app->run();
