<?php

class Empleado
{
    public $legajo;
    public $nombre;
    public $perfilEmpleado;
    public $clave;
    public $fechaAlta;
    public $horaAlta;
    public $fechaBaja;


    /*public function __construct($legajo, $nombre, $perfilEmpleado, $clave, $fechaAlta, $horaAlta, $fechaBaja) {
        
        $this->legajo = $legajo;
        $this->nombre = $nombre;
        $this->perfilEmpleado = $perfilEmpleado;
        $this->clave = $clave;
        $this->fechaAlta = $fechaAlta;
        $this->horaAlta = $horaAlta;
        $this->fechaBaja = $fechaBaja;
    }*/


    public function AltaUsuario(){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Ussuario (legajo,nombre,perfilEmpleado,clave,fechaAlta,horaAlta) 
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

        return $objAccesoDatos->obtenerUltimoId();
    }


}