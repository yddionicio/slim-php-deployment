<?php

class Producto
{
    public $idProducto;
    public $nombre;
    public $precio;
    public $tipo;

    public function AltaProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO menu (idProducto,nombre,precio,tipo) 
        VALUES (:idProducto,:nombre, :precio, :tipo) ON DUPLICATE KEY UPDATE nombre = :nombreDos, precio=:precioDos,tipo=:tipoDos");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(":idProducto", $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':nombreDos', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precioDos', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDos', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerProductoPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu WHERE idProducto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }


}
