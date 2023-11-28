<?php
//include_once "./DB/AccesoDatos.php";
include './DB/AccesoDatos.php';
class Empleado
{
    public $legajo;
    public $nombre;
    public $perfilEmpleado;
    public $clave;
    public $fechaAlta;
    public $horaAlta;
    public $fechaBaja;


    public function AltaUsuario(){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleado (legajo,nombre,perfilEmpleado,clave,fechaAlta,horaAlta) 
        VALUES (:legajo,:nombre, :perfilEmpleado, :clave, :fechaAlta, :horaAlta)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $fecha = new DateTime(date("d-m-Y"));
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':horaAlta', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':legajo', $this->legajo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':perfilEmpleado', $this->perfilEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        //return $objAccesoDatos->obtenerUltimoId();
    }

    public static function borrarEmpleado($legajo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE empleado SET fechaBaja = :fechaBaja WHERE legajo = :legajo");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        return $consulta->rowCount();
    }


    public static function obtenerEmpleadoPorLegajo($legajo) // busca el empleado en kla db
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleado WHERE legajo = :legajo");
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Empleado');
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleado");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }


    public static function obtenerEmpleadosPorTipo($idProducto)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores 
        WHERE perfilEmpleado = (SELECT tipo from menu WHERE idProducto = :idProducto)");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

}