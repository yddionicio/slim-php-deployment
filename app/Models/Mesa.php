<?php

require_once './models/Empleado.php';
class Mesa
{
    public $idMesa;
    public $estado;
    public $legajoMozo;

    public function AltaMesa()  
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta ("UPDATE mesas 
        SET legajoMozo = :legajoMozo,
        estado = 'cliente esperando mozo'
        WHERE idMesa = :idMesa AND estado = 'cerrado'");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':legajoMozo', $this->legajoMozo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }

}
