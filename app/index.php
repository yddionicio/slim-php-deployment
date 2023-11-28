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
include_once './Controllers/ProductoController.php';
include_once './Controllers/MesaController.php';
include_once './Controllers/AutentificadorController.php';
include_once './Middlewares/CheckSocioMiddleware.php';
include_once './Middlewares/CheckTokenMiddleware.php';
include_once './Middlewares/CheckMozoMiddleware.php';

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
    
    //Usuario
    $group->post('/altaUsuario', \EmpleadoController::class . ':CargarUno')->add(new CheckSocioMiddleware); // verifica que el token pertenezca a un socio 
    $group->get('/traerUsuarios', \EmpleadoController::class . ':TraerTodos');
    $group->delete('/eliminarUsuario', \EmpleadoController::class . ':BorrarUno');
    
    //Producto
    $group->post('/altaProducto', \ProductoController::class . ':CargarUno')->add(new CheckSocioMiddleware);
    $group->get('/traerProductos', \ProductoController::class . ':TraerTodos')->add(new CheckMozoMiddleware);

    //Mesa
    $group->post('/altaMesa', \MesaController::class . ':CargarUno')->add(new CheckSocioMiddleware); //falta request
    $group->get('/traerMesas', \MesaController::class . ':TraerTodos');

    //Comanda
    $group->post('/altaComanda', \ComandaController::class . ':CargarUno')->add(new CheckSocioMiddleware); //falta request

    //ProductoPedido
    $group->post('/altaProductoPedido', \ProductoPedidoController::class . ':CargarUno')->add(new CheckSocioMiddleware); //falta request

    
    //CSV
    //$group->delete('/borrarEmpleado', \EmpleadoController::class . ':BorrarUno')->add(new CheckSocioMiddleware());
    $group->post('/exportarCSV', \ProductoController::class . ':ExportarTabla')->add(new CheckSocioMiddleware());     //falta request
    $group->post('/cargarCSV', \ProductoController::class . ':ImportarTabla')->add(new CheckSocioMiddleware());


})->add(new CheckTokenMiddleware); //token generico valido



$app->post('/CrearToken', \AutentificadorController::class . ':CrearTokenLogin');



//$app->post('/altaUsuario', \EmpleadoController::class . ':CargarUno');

$app->run();
