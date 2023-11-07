<?php
require_once './models/Empleado.php';
//require_once './interfaces/IApiUsable.php';

class EmpleadoController extends Empleado
{
    public $tipoPerfiles = ["bartender","cervecero","cocinero","mozo","socio"];
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $perfilEmpleado = $parametros["perfilEmpleado"];
        $clave = $parametros["clave"];
        $legajo = $parametros["legajo"];

        // Creamos el usuario
        try{
            if(in_array($perfilEmpleado, $this->tipoPerfiles))
            {
                $empleado = new Empleado();
                $empleado->legajo=$legajo;
                $empleado->nombre = $nombre;
                $empleado->clave = $clave;
                $empleado->perfilEmpleado = $perfilEmpleado;
                $empleado->AltaUsuario();
                $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Perfil de empleado ingresado invalido"));
            }
        }catch(\Throwable $ex)
        {
            $payload=json_encode(array("mensaje" => $ex->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   /* public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::obtenerTodos();
        $payload = json_encode(array("listaDeEmpleados" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }*/

}