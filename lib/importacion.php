<?php

    /**
     * Importacion de layer de municipios desde formato json a tablas.
     * /layers/municipios.json es la version de /layers/municipios.js sin la syntaxis 
     * javascript.
     * La tabla municipios_layer contiene el atributo 'features' (que es la lista de elementos -los municipios- ver /layers/municipios.json)
     * donde las 'properties' es un diccionario clave/valor con datos convenientes para el dominio de la aplicacion y geometry es el poligono definido como una serie de
     * puntos que conforman la representacion visual del municipio.
     * 
     * Para que la libreria leaflet interprete el layer, debe recibir el mismo formato, que aparece en /layers/municipios.json.
     * 
     * 
     */
    require_once("conexion.php");
    
    try {
        $txt = file_get_contents('../layers/municipios.json', true);        
        $json = json_decode( $txt, true, 100000, 0);
        $features = $json['features'];
        mysqli_set_charset($conexion, 'utf8');
        mb_internal_encoding('UTF-8');
        foreach($features as $ft) {
            $type = $ft['type'];            
            $municipio = $ft['properties']['nombre'];            
            $properties = json_encode($ft['properties'],JSON_UNESCAPED_UNICODE);
            $geometry = json_encode($ft['geometry']);

            $sql = "INSERT INTO municipios (nombre) VALUES ('$municipio')";
            $conexion->query($sql);
            $municipio_id = $conexion->insert_id;

            

            $sql = "INSERT INTO municipios_layer (type, properties, geometry, municipio_id) VALUES ('$type', '$properties', '$geometry', $municipio_id)";

            if ($conexion->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conexion->error;
                echo "<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br><br>";
            }
        }        
        echo "========================================================================================================";        
    } catch(Exception $ex) {
        echo $ex->getMessage();
    }
    
    

?>