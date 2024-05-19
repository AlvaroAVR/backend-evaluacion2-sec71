<?php

class Controlador
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAll()
    {
        $con = new Conexion();
        $sql = "SELECT id, pregunta, respuesta, activo FROM pregunta_frecuente;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                $tupla['activo'] = $tupla['activo'] == 1 ? true : false;
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
    }

    public function postNuevo($_nuevoObjeto)
    {
        $con = new Conexion();
        $ids = array_column($this->getAll(), 'id');
        $id = $ids ? max($ids) + 1 : 1;
        $sql = "INSERT INTO pregunta_frecuente (id, pregunta, respuesta, activo) VALUES ($id, '$_nuevoObjeto->pregunta', '$_nuevoObjeto->respuesta', true);";
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function patchEncenderApagar($_id, $_accion)
    {
        $con = new Conexion();
        $sql = "UPDATE pregunta_frecuente SET activo = $_accion WHERE id = $_id;";
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putPreguntaById($_pregunta, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE pregunta_frecuente SET pregunta = '$_pregunta' WHERE id = $_id;";
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putRespuestaById($_respuesta, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE pregunta_frecuente SET respuesta = '$_respuesta' WHERE id = $_id;";
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putAll($_pregunta, $_respuesta, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE pregunta_frecuente SET pregunta = '$_pregunta', respuesta = '$_respuesta' WHERE id = $_id;";
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function deleteById($_id)
    {
        $con = new Conexion();
        $sql = "DELETE FROM pregunta_frecuente WHERE id = $_id;";
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }
}
