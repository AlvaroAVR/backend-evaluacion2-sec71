<?php
include_once '../version1.php';

$existeId = false;
$valorId = 0;
$existeAccion = false;
$valorAccion = 0;

if (count($_parametros) > 0) {
    foreach ($_parametros as $p) {
        if (strpos($p, 'id') !== false) {
            $existeId = true;
            $valorId = explode('=', $p)[1];
        }
        if (strpos($p, 'accion') !== false) {
            $existeAccion = true;
            $valorAccion = explode('=', $p)[1];
        }
    }
}

if ($_version == 'v1') {
    if ($_mantenedor == 'parcela') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $lista = $control->getAll();

                    http_response_code(200);
                    echo json_encode(["data" => $lista]);
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorizacion GET"]);
                }
                break;

            case 'POST':
                if ($_header == $_token_post) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input", true));
                    $respuesta  = $control->postNuevo($body);
                    if ($respuesta) {
                        http_response_code(201);
                        echo json_encode(["data" => $respuesta]);
                    } else {
                        http_response_code(409);
                        echo json_encode(["data" => "error: conflicto con el nombre ingresado, ya existe"]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorizacion POST"]);
                }
                break;

            case 'PATCH':
                if ($_header == $_token_patch) {
                    if ($existeId && $existeAccion) {
                        include_once 'controller.php';
                        include_once '../conexion.php';
                        $control = new Controlador();
                        if ($valorAccion == 'encender') {
                            $respuesta = $control->patchEncenderApagar($valorId, 'true');
                            http_response_code(200);
                            echo json_encode(["data" => $respuesta]);
                        } else if ($valorAccion == 'apagar') {
                            $respuesta = $control->patchEncenderApagar($valorId, 'false');
                            http_response_code(200);
                            echo json_encode(["data" => $respuesta]);
                        } else {
                            echo 'error con acciones...';
                        }
                    } else {
                        echo 'error...';
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorizacion PATCH"]);
                }
                break;

            case 'PUT':
                if ($_header == $_token_put) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input", true));
                    if ($body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) { //si nombre tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putNombreById($body->nombre, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && $body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si parcela_lote_id tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putParcelaLoteIdById($body->parcela_lote_id, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && $body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si parcela_tipo_id tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putParcelaTipoIdById($body->parcela_tipo_id, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && $body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si numeracion_interna tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putNumeracionInternaById($body->numeracion_interna, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && $body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si terreno_ancho tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putTerrenoAnchoById($body->terreno_ancho, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && $body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si terreno_largo tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putTerrenoLargoById($body->terreno_largo, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && $body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si terreno_despejado_arboles tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putTerrenoDespejadoArbolesById($body->terreno_despejado_arboles, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && $body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si ubicacion_latitud_gm tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putUbicacionLatitudGmById($body->ubicacion_latitud_gm, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && $body->ubicacion_longitud_gm && !$body->pie && !$body->valor) {//si ubicacion_longitud_gm tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putUbicacionLongitudGmById($body->ubicacion_longitud_gm, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && $body->pie && !$body->valor) {//si pie tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putPieById($body->pie, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if (!$body->nombre && !$body->parcela_lote_id && !$body->parcela_tipo_id && !$body->numeracion_interna && !$body->terreno_ancho && !$body->terreno_largo && !$body->terreno_despejado_arboles && !$body->ubicacion_latitud_gm && !$body->ubicacion_longitud_gm && !$body->pie && $body->valor) {//si valor tiene datos y los demas campos no, entonces lo actualizamos
                        $respuesta = $control->putValorById($body->valor, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else if ($body->nombre && $body->parcela_lote_id && $body->parcela_tipo_id && $body->numeracion_interna && $body->terreno_ancho && $body->terreno_largo && $body->terreno_despejado_arboles && $body->ubicacion_latitud_gm && $body->ubicacion_longitud_gm && $body->pie && $body->valor) {//si todos los datos tienen datos, entonces los actualizamos
                        $respuesta = $control->putAll($body->nombre, $body->parcela_lote_id, $body->parcela_tipo_id, $body->numeracion_interna, $body->terreno_ancho, $body->terreno_largo, $body->terreno_despejado_arboles, $body->ubicacion_latitud_gm, $body->ubicacion_longitud_gm, $body->pie, $body->valor, $body->id);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else {
                        echo "Error: Parametros no validos";
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorizacion PUT"]);
                }
                break;

            case 'DELETE':
                if ($_header == $_token_delete) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    if ($existeId) {
                        $respuesta = $control->deleteById($valorId);
                        http_response_code(200);
                        echo json_encode(["data" => $respuesta]);
                    } else {
                        echo 'error faltan parametros';
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorizacion DELETE"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["Error" => "No implementado"]);
                break;
        }
    }
}
