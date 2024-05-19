<?php

class Controlador{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAll() {
        $con = new Conexion();
        $conn = $con->getConnection();
        $sql = "SELECT 
                    h.id AS historia_id, 
                    h.tipo, 
                    h.texto, 
                    h.activo AS historia_activo,
                    i.id AS imagen_id, 
                    i.nombre AS imagen_nombre, 
                    i.imagen AS imagen_url, 
                    i.activo AS imagen_activo
                FROM historia h
                LEFT JOIN historia_imagen hi ON h.id = hi.historia_id
                LEFT JOIN imagen i ON hi.imagen_id = i.id;";
    
        $result = mysqli_query($conn, $sql);
        $lista = [];
    
        if ($result) {
            $historias = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $historia_id = $row['historia_id'];
                if (!isset($historias[$historia_id])) {
                    $historias[$historia_id] = [
                        "id" => $row["historia_id"],
                        "tipo" => $row["tipo"],
                        "texto" => $row["texto"],
                        "activo" => $row["historia_activo"] == 1 ? true : false,
                        "imagenes" => []
                    ];
                }
                if (!is_null($row['imagen_id'])) {
                    $historias[$historia_id]["imagenes"][] = [
                        "id" => $row["imagen_id"],
                        "nombre" => $row["imagen_nombre"],
                        "url" => $row["imagen_url"],
                        "activo" => $row["imagen_activo"] == 1 ? true : false
                    ];
                }
            }
            $lista = array_values($historias);
    
            mysqli_free_result($result);
        }
        $con->closeConnection();
        return $lista;
    }

    public function postNuevo($_nuevoObjeto)
    {
        $con = new Conexion();
        $ids = array_column($this->getAll(), 'id');
        $id = $ids ? max($ids) + 1 : 1;
        $sql = "INSERT INTO historia (id, tipo, texto, activo) VALUES ($id, '$_nuevoObjeto->tipo', '$_nuevoObjeto->texto', true);";
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
        $sql = "UPDATE historia SET activo = $_accion WHERE id = $_id;";
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

    public function putTipoById($_tipo, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE historia SET tipo = '$_tipo' WHERE id = $_id;";
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

    public function putTextoById($_texto, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE historia SET texto = '$_texto' WHERE id = $_id;";
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

    public function putAll($_tipo ,$_texto, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE historia SET tipo = '$_tipo', texto = '$_texto' WHERE id = $_id;";
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
        $sql = "DELETE FROM historia WHERE id = $_id;";
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
