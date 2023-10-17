<?php

/**
 * Retorna el layer de municipios con datos enriquecidos en formato GeoJson.
 * Este formato es consumido por LeafLet
 */
  
 //error_reporting(E_ALL);
// ini_set("display_errors", 1);
require_once("conexion.php");

$mesasYEstablecimientos = conteoDeMesasYEstablecimientos($conexion);

    
///////////////////////////
try{    
    $idSeccion = "063";
            mysqli_set_charset($conexion2, 'UTF8');
            
            $sql = "SELECT agrupacion_nombre,
            SUM(votos_cantidad) AS votos_total
            FROM
            pba_gobernador_2023
            WHERE
            seccion_id = $idSeccion and
            distrito_id= 2 and
            votos_tipo = 'POSITIVO'
            GROUP BY agrupacion_nombre            
            ORDER BY votos_total DESC LIMIT 4
            ";
            
            $res = mysqli_query($conexion2, $sql) or die(mysqli_error($conexion2));
            
            $i=1;
            while($fila = mysqli_fetch_assoc($res)){      
            $candidato_seccion[$i] = [
                'AGRUPACION' => $fila['agrupacion_nombre'],    
                'TOTAL' => $fila['votos_total']
            ];            
            $i++;
            }
        }
        catch (Exception $e) {
        return $e->getMessage();
        }     

///////////////////////////

try {
    /** 
     * Arma el header del json tal cual aparece en municipio.json
     */
    $crsProps = new stdClass();
    $crsProps->name = "urn:ogc:def:crs:OGC:1.3:CRS84";
    $crs = new stdClass();
    $crs->type = "name";
    $crs->properties = $crsProps;
    $layer = new stdClass();
    $layer->type = "FeatureCollection";
    $layer->name = "limite_partidos";
    $layer->crs = $crs;




    $features = array();        
    mysqli_set_charset($conexion, 'UTF8');
    $sql = "SELECT ml.*, m.id as municipio_id, m.nombre as nombre_municipio, m.codigo_municipio as codigo_municipio, se.seccion, se.nombre as nombre_seccion, se.color as color_seccion 
            FROM municipios_layer ml
            INNER JOIN municipios m ON m.id = ml.municipio_id
            INNER JOIN seccion_electoral se ON m.seccion_electoral = se.seccion";
         
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));   
    while($fila = mysqli_fetch_assoc($res)) {

        /**
         * Arma las propiedades de cada municipio,
         * aqui se podria traer informacion deseada desde otras tablas
         * y agregarlo al diccionario (properties) clave/valor de cada feature. 
         */
        $props = new stdClass();
        $props->id = $fila['municipio_id'];
        $props->nombre = $fila['nombre_municipio'];
        $props->codigoMunicipio = $fila['codigo_municipio'];
        $props->seccion = $fila['seccion'];
        $props->nombreSeccion = $fila['nombre_seccion'];
        $props->colorSeccion = $fila['color_seccion'];
        $props = datosMesaYEstablecimientoPara($props, $mesasYEstablecimientos);

        ///////////////////////////////////////////////////////////////////////////
        
       

        ///////////////////////////////////////////////////////////////////////////


        $props->res1 = intval($candidato_seccion[1]["TOTAL"]);
        $props->res2 = intval($candidato_seccion[2]["TOTAL"]);
        $props->res3 = intval($candidato_seccion[3]["TOTAL"]);
        $props->res4 = intval($candidato_seccion[4]["TOTAL"]);

        // $props->res1 =10;
        // $props->res2 = 2;
        // $props->res3 = 3; ////Esta info sale de la consulta de arriba
        // $props->res4 = 4;
        

        /** Agrega datos de establecimientos y escuelas a las propiedades de cada municipio */
        
        $unFeature = new stdClass();
        $unFeature->type = $fila['type'];
        $unFeature->properties = $props;
        $geometry = json_decode($fila['geometry']);
        $unFeature->geometry = $geometry;

        $features[] = $unFeature;
        
    }
    $layer->features = $features;     
    echo json_encode($layer, JSON_UNESCAPED_UNICODE);

} 
catch (Exception $e) {
    $layer->error = $e->getMessage();
    echo json_encode($layer);
    exit;
}




/**
 * Agrega datos de mesas y estableimcientos
 * para las propidades actuales
 *
 * @param {object} $props - stdClass
 * @param {array} $mesasYEstablecimientos
 * @return {object} $props
 */
function datosMesaYEstablecimientoPara($props, $mesasYEstablecimientos) {

    $idSeccion = $props->codigoMunicipio;
    foreach ($mesasYEstablecimientos as $data) {  
   
        if($data['id_seccion'] == $idSeccion) {
            if($data['tipo'] == 'EXTRANJEROS') {
                $props->cantidadMesasExtranjeros = $data['cantidad_mesas_partido'];
                $props->cantidadEstablecimientosExtranjeros = $data['cantidad_establecimientos_partido'];
            }
            if($data['tipo'] == 'NACIONALES') {
                $props->cantidadMesas = $data['cantidad_mesas_partido'];
                $props->cantidadEstablecimientos = $data['cantidad_establecimientos_partido'];
            }
        } 
    }
    return $props;
}

/**
 * Obtiene datos de mesas y establecimientos
 *
 * @param {mysqli} $conexion
 * @return {array}
 */
function conteoDeMesasYEstablecimientos($conexion) {
    $datos = array();
    $sql = "SELECT 'EXTRANJEROS' AS padron , 
                    a.codigo_seccion AS codigo_seccion, 
                    a.codigo_seccion AS id_seccion, 
                    a.seccion AS nombre_partido, 
                    a.numero_seccion AS seccion_electoral, 
                    COUNT(a.mesa) AS cantidad_mesas_partido, 
                    COUNT(a.id) AS cantidad_establecimientos_partido
                    
                FROM lugar_votacion_extranjero a
                GROUP BY a.codigo_seccion, a.codigo_seccion, a.seccion, a.numero_seccion
        UNION ALL
        SELECT 'NACIONALES' AS padron, 
                    a.codigo_seccion AS codigo_seccion, 
                    a.codigo_seccion AS id_seccion, 
                    a.seccion AS nombre_partido, 
                    a.numero_seccion AS seccion_electoral,                 
                    SUM(a.cantidad_mesas) AS cantidad_mesas_partido, 
                    COUNT(a.id) AS cantidad_establecimientos_partido
                FROM lugar_votacion a
                GROUP BY a.codigo_seccion, a.codigo_seccion, a.seccion, a.numero_seccion ";
        $res = mysqli_query($conexion, $sql);
        if (mysqli_errno($conexion) != 0) 
        {
                echo 'ERROR: '. mysqli_errno($conexion).' - '.mysqli_error($conexion); 
                
        }

        while($row = mysqli_fetch_assoc($res)){            
            $datos[] = array(
                'tipo' => $row['padron'],
                'codigo_seccion' => $row['codigo_seccion'],
                'id_seccion' => $row['id_seccion'],
                'nombre_partido' => utf8_encode($row['nombre_partido']),
                'seccion_electoral' => $row['seccion_electoral'],
                'cantidad_mesas_partido' => $row['cantidad_mesas_partido'],
                'cantidad_establecimientos_partido' => $row['cantidad_establecimientos_partido']
            );            
        }
    return $datos;
}

