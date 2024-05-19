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
        $conn = $con->getConnection();
        $sql = "SELECT 
                p.id AS parcela_id, 
                p.nombre AS parcela_nombre, 
                p.parcela_lote_id, 
                p.parcela_tipo_id, 
                p.numeracion_interna, 
                p.terreno_ancho, 
                p.terreno_largo, 
                p.terreno_despejado_arboles, 
                p.ubicacion_latitud_gm, 
                p.ubicacion_longitud_gm, 
                p.pie, 
                p.valor, 
                p.activo AS parcela_activo,
                pl.nombre AS lote_nombre,
                pt.nombre AS tipo_nombre,
                ps.id AS servicio_id, 
                ps.nombre AS servicio_nombre
            FROM parcela p
            LEFT JOIN parcela_lote pl ON p.parcela_lote_id = pl.id
            LEFT JOIN parcela_tipo pt ON p.parcela_tipo_id = pt.id
            LEFT JOIN parcela_servicio_parcela psp ON p.id = psp.parcela_id
            LEFT JOIN parcela_servicio ps ON psp.parcela_servicio_id = ps.id;";

        $result = mysqli_query($conn, $sql);
        $lista = [];

        if ($result) {
            $parcelas = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $parcela_id = $row['parcela_id'];
                if (!isset($parcelas[$parcela_id])) {
                    $parcelas[$parcela_id] = [
                        "id" => $row["parcela_id"],
                        "nombre" => $row["parcela_nombre"],
                        "parcela_lote" => [
                            "id" => $row["parcela_lote_id"],
                            "nombre" => $row["lote_nombre"]
                        ],
                        "parcela_tipo" => [
                            "id" => $row["parcela_tipo_id"],
                            "nombre" => $row["tipo_nombre"]
                        ],
                        "servicios" => [],
                        "numeracion_interna" => $row["numeracion_interna"],
                        "terreno_ancho" => $row["terreno_ancho"],
                        "terreno_largo" => $row["terreno_largo"],
                        "terreno_despejado_arboles" => $row["terreno_despejado_arboles"] == 1 ? true : false,
                        "ubicacion_latitud_gm" => $row["ubicacion_latitud_gm"],
                        "ubicacion_longitud_gm" => $row["ubicacion_longitud_gm"],
                        "pie" => $row["pie"],
                        "valor" => $row["valor"],
                        "activo" => $row["parcela_activo"] == 1 ? true : false
                    ];
                }
                if (!is_null($row['servicio_id'])) {
                    $parcelas[$parcela_id]["servicios"][] = [
                        "id" => $row["servicio_id"],
                        "nombre" => $row["servicio_nombre"]
                    ];
                }
            }
            $lista = array_values($parcelas);
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
        $sql = "INSERT INTO parcela (id, nombre, parcela_lote_id, parcela_tipo_id, numeracion_interna, terreno_ancho, terreno_largo, terreno_despejado_arboles, ubicacion_latitud_gm, ubicacion_longitud_gm, pie, valor, activo) VALUES ($id, '$_nuevoObjeto->nombre', $_nuevoObjeto->parcela_lote_id, $_nuevoObjeto->parcela_tipo_id, '$_nuevoObjeto->numeracion_interna', $_nuevoObjeto->terreno_ancho, $_nuevoObjeto->terreno_largo, $_nuevoObjeto->terreno_despejado_arboles, $_nuevoObjeto->ubicacion_latitud_gm, $_nuevoObjeto->ubicacion_longitud_gm, $_nuevoObjeto->pie, $_nuevoObjeto->valor, TRUE);";
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
        $sql = "UPDATE parcela SET activo = $_accion WHERE id = $_id;";
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

    public function putNombreById($_nombre, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET nombre = '$_nombre' WHERE id = $_id;";
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

    public function putParcelaLoteIdById($_parcelaLoteId, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET parcela_lote_id = $_parcelaLoteId WHERE id = $_id;";
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

    public function putParcelaTipoIdById($_parcelaTipoId, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET parcela_tipo_id = $_parcelaTipoId WHERE id = $_id;";
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

    public function putNumeracionInternaById($_numeracionInterna, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET numeracion_interna = '$_numeracionInterna' WHERE id = $_id;";
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

    public function putTerrenoAnchoById($_terrenoAncho, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET terreno_ancho = $_terrenoAncho WHERE id = $_id;";
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

    public function putTerrenoLargoById($_terrenoLargo, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET terreno_largo = $_terrenoLargo WHERE id = $_id;";
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

    public function putTerrenoDespejadoArbolesById($_terrenoDespejadoArboles, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET terreno_despejado_arboles = $_terrenoDespejadoArboles WHERE id = $_id;";
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

    public function putUbicacionLatitudGmById($_ubicacionLatitudGm, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET ubicacion_latitud_gm = $_ubicacionLatitudGm WHERE id = $_id;";
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

    public function putUbicacionLongitudGmById($_ubicacionLongitudGm, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET ubicacion_longitud_gm = $_ubicacionLongitudGm WHERE id = $_id;";
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

    public function putPieById($_pie, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET pie = $_pie WHERE id = $_id;";
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

    public function putValorById($_valor, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET valor = $_valor WHERE id = $_id;";
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

    public function putAll( $_nombre, $_parcelaLoteId, $_parcelaTipoId, $_numeracionInterna, $_terrenoAncho, $_terrenoLargo, $_terrenoDespejadoArboles, $_ubicacionLatitudGm, $_ubicacionLongitudGm,$_pie,$_valor, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela SET nombre = '$_nombre', parcela_lote_id = $_parcelaLoteId, parcela_tipo_id = $_parcelaTipoId, numeracion_interna = '$_numeracionInterna', terreno_ancho = $_terrenoAncho, terreno_largo = $_terrenoLargo, terreno_despejado_arboles = $_terrenoDespejadoArboles, ubicacion_latitud_gm = $_ubicacionLatitudGm, ubicacion_longitud_gm = $_ubicacionLongitudGm, pie = $_pie, valor = $_valor WHERE id = $_id;";
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
        $sql = "DELETE FROM parcela WHERE id = $_id;";
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
