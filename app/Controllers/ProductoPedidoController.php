<?php
require_once './models/ProductoPedido.php';
require_once './models/Mesa.php';
require_once './models/Comanda.php';
require_once './models/Producto.php';

class ProductoPedidoController 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        try{
          
          $pendiente = new ProductoPedido();
          
          $comandaAuxiliar = Comanda::obtenerComandasPorIdPendientes($parametros["idComanda"]);
         
          $productoAuxiliar = Producto::obtenerProductoPorId($parametros["idPlato"]);
          if($comandaAuxiliar!= null && $productoAuxiliar != null)
          {
            $pendiente->idComanda = $parametros["idComanda"];
            $pendiente->idMesa = $comandaAuxiliar->idMesa;
            $pendiente->legajoEmpleado = 0;
            $pendiente->idPlato= $parametros["idPlato"];
            $pendiente->minutosDemora= 0;
            $pendiente->estado = "Pendiente";
            $pendiente->crearPendiente();
            $payload=json_encode(array("mensaje" => "Se creó el pendiente correctamente"));
          }else{
            $payload=json_encode(array("mensaje" => "Revisar comanda y producto"));
          }
        }catch(\Throwable $ex)
        {
            $payload=json_encode(array("mensaje" => "$ex->getMessage()"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    
}