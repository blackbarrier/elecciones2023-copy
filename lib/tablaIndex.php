<?php
 //error_reporting(E_ALL);
// ini_set("display_errors", 1);
require_once("conexion.php");

//post vars
$idConsulta = $_POST["seccion_id"];
$nombreConsulta = $_POST["nombreConsulta"];
$idMunicipio = $_POST["municipio_id"];
$nombreHeader = $nombreConsulta;
if($nombreConsulta == 'CANUELAS')
{
	$nombreHeader = 'CAÑUELAS';
}


try {
    
    $array_establecimientos_por_partido = [ 'EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];
    $array_electores_por_partido = ['EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];
    $array_mesas_por_partido = [ 'EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];
    
    $array_establecimientos_por_seccion = [ 'EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];
    $array_electores_por_seccion = [ 'EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];
    $array_mesas_por_seccion = [ 'EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];

    
    mysqli_set_charset($conexion, 'UTF8');
    
    // TOTAL ESTABLECIMIENTOS POR PARTIDO
        $sql = "SELECT 'EXTRANJEROS' AS padron , 
                    COUNT(a.establecimiento) AS cantidad
                    FROM lugar_votacion_extranjero a
                    /*WHERE seccion = '{$nombreConsulta}'*/
                    WHERE codigo_seccion = {$idMunicipio}   
                UNION ALL
                SELECT 'NACIONALES' AS padron, 
                        COUNT(a.establecimiento) AS cantidad
                    FROM lugar_votacion a
                    WHERE codigo_seccion = {$idMunicipio}"
                    /*WHERE seccion = '{$nombreConsulta}'"*/;
         
        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));


        
        while($fila = mysqli_fetch_assoc($res))
        {
            if($fila['padron'] == 'EXTRANJEROS')
            {
                $array_establecimientos_por_partido['EXTRANJEROS'] = $fila['cantidad'];
            }
            if($fila['padron'] == 'NACIONALES')
            {
                $array_establecimientos_por_partido['NACIONALES'] = $fila['cantidad'];
            }
            $array_establecimientos_por_partido['TOTAL'] += $fila['cantidad'];
        }
    
    
    
    
    // TOTAL ELECTORES POR PARTIDO
        $sql = "SELECT	  
                    codigo_seccion,		
                    SUM(nativos) AS total_nativos,
                    SUM(extranjeros) AS total_extranjeros,
                    SUM(total) AS total
                FROM
                    electores
                /*WHERE seccion = '{$nombreConsulta}'*/
                WHERE codigo_seccion = {$idMunicipio}
                ";
        $res = mysqli_query($conexion, $sql) or die( mysqli_error($conexion));
    
        $fila = mysqli_fetch_assoc($res);
        $array_electores_por_partido['EXTRANJEROS'] = $fila['total_extranjeros'];
        $array_electores_por_partido['NACIONALES'] = $fila['total_nativos'];
        $array_electores_por_partido['TOTAL'] = $fila['total'];
      


    //TOTAL MESAS POR PARTIDO
        mysqli_set_charset($conexion, 'UTF8');
        $sql = "SELECT 'EXTRANJEROS' AS padron , 
                    COUNT(a.mesa) AS cantidad_mesas_partido
                    FROM lugar_votacion_extranjero a
                    /* WHERE seccion = '{$nombreConsulta}' */
                    WHERE codigo_seccion = {$idMunicipio} 
                UNION ALL
                SELECT 'NACIONALES' AS padron, 
                    SUM(a.cantidad_mesas) AS cantidad_mesas_partido
                    FROM lugar_votacion a
                    /*WHERE seccion = '{$nombreConsulta}'*/
                    WHERE codigo_seccion = {$idMunicipio}";
        
        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
        
        while($fila = mysqli_fetch_assoc($res))
        {
            
            if($fila['padron'] == 'EXTRANJEROS')
            {
                $array_mesas_por_partido['EXTRANJEROS'] = $fila['cantidad_mesas_partido'];
            }
            if($fila['padron'] == 'NACIONALES')
            {
                $array_mesas_por_partido['NACIONALES'] = $fila['cantidad_mesas_partido'];
            }
            $array_mesas_por_partido['TOTAL'] += $fila['cantidad_mesas_partido'];
        }        
   
    

    
    
    
  
    // -- *TOTAL MESAS POR SECCION*
        $sql="SELECT 'EXTRANJEROS' AS padron , 

                        COUNT(a.mesa) AS cantidad_mesas
                        FROM lugar_votacion_extranjero a
                        WHERE numero_seccion = {$idConsulta}
                UNION ALL
                SELECT 'NACIONALES' AS padron, 

                        SUM(a.cantidad_mesas) AS cantidad_mesas
                FROM lugar_votacion a
                WHERE numero_seccion = {$idConsulta}
                ";
        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
        while($fila = mysqli_fetch_assoc($res))
        {
            if($fila['padron'] == 'EXTRANJEROS')
            {
                $array_mesas_por_seccion['EXTRANJEROS'] = $fila['cantidad_mesas'];
            }
            if($fila['padron'] == 'NACIONALES')
            {
                $array_mesas_por_seccion['NACIONALES'] = $fila['cantidad_mesas'];
            }
            $array_mesas_por_seccion['TOTAL'] += $fila['cantidad_mesas'];
        }   
    
    
    
    
    //-- TOTAL ELECTORES X SECCION
        $sql="SELECT	  
            SUM(nativos) AS nacionales,
            SUM(extranjeros) AS extranjeros,
            SUM(total) AS total
            FROM
                electores
            WHERE numero_seccion = {$idConsulta}";
        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
        $fila = mysqli_fetch_assoc($res);
        $array_electores_por_seccion['EXTRANJEROS'] = $fila['extranjeros'];
        $array_electores_por_seccion['NACIONALES'] = $fila['nacionales'];
        $array_electores_por_seccion['TOTAL'] += $fila['total'];
       
    
    
    //-- TOTAL ESTABLECIMIENTOS X SECCION
        $sql="SELECT 'EXTRANJEROS' AS padron , 

                        COUNT(a.establecimiento) AS cantidad_mesas
                        FROM lugar_votacion_extranjero a
                        WHERE numero_seccion = {$idConsulta}
                UNION ALL
                SELECT 'NACIONALES' AS padron, 

                        COUNT(a.establecimiento) AS cantidad_mesas
                FROM lugar_votacion a
                WHERE numero_seccion = {$idConsulta}
            ";
        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
        while($fila = mysqli_fetch_assoc($res))
        {
            if($fila['padron'] == 'EXTRANJEROS')
            {
                $array_establecimientos_por_seccion['EXTRANJEROS'] = $fila['cantidad_mesas'];

            }
            if($fila['padron'] == 'NACIONALES')
            {
                $array_establecimientos_por_seccion['NACIONALES'] = $fila['cantidad_mesas'];
            }

            $array_establecimientos_por_seccion['TOTAL'] += $fila['cantidad_mesas'];
            
        }   



        // $candidatos_intendentes=array();
        // $candidatos_consejales=array();
         // //-- CANDIDATOS
        // $sql_candidatos=" SELECT * FROM `candidato_municipal` WHERE seccion='{$nombreConsulta}' order by intendente ";

        // $res_candidatos = mysqli_query($conexion, $sql_candidatos) or die(mysqli_error($conexion));
        // while($fila_candidatos = mysqli_fetch_assoc($res_candidatos))
        // {
        //      $candidatos_intendentes[] = "<b>".$fila_candidatos['intendente']."</b> (".$fila_candidatos['fuerza_politica']." - ".$fila_candidatos['linea'].")";
        //      $candidatos_consejales[] = "<b>".$fila_candidatos['primer_concejal']."</b>  (".$fila_candidatos['fuerza_politica']." - ".$fila_candidatos['linea'].")";
        // }   
    


        $presos=array();
        //-- CANDIDATOS
        $sql_presos="SELECT SUM(cantidad_mesas) AS cantidad_mesas,SUM(cantidad_electores) AS cantidad_electores FROM  `lugar_votacion_carcel` WHERE numero_seccion={$idConsulta} GROUP BY numero_seccion";

        $res_presos = mysqli_query($conexion, $sql_presos) or die(mysqli_error($conexion));
        while($fila_presos = mysqli_fetch_assoc($res_presos))
        {
            $presos['cantidad_mesas'] =$fila_presos['cantidad_mesas'];
            $presos['cantidad_electores'] =$fila_presos['cantidad_electores'];
        } 
    
} 
catch (Exception $e) {
    return $e->getMessage();
}


//$number = 1000000;
//$formattedNumber = number_format($number, 0, '.', '.');
//echo $formattedNumber; // resultado: 1.000.000



//Add by Lean Barrera

try{
    
    mysqli_set_charset($conexion2, 'UTF8');

    //CONSULTA POR PARTIDO  
    
        $sql = "SELECT agrupacion_nombre,
        SUM(case when mesa_tipo='NATIVOS' then votos_cantidad ELSE 0 end ) AS votos_nativos,
        SUM(case when mesa_tipo='EXTRANJEROS' then votos_cantidad ELSE 0 end ) AS votos_extranjeros,
        SUM(votos_cantidad) AS votos_total
        FROM
        pba_gobernador_2023
        WHERE
        seccion_id = {$idMunicipio} and
        distrito_id= 2 and
        votos_tipo = 'POSITIVO'
        GROUP BY agrupacion_nombre            
        ORDER BY votos_total DESC LIMIT 4";
        
        $res = mysqli_query($conexion2, $sql) or die(mysqli_error($conexion2));
    
        $i = 1;    
        while($fila = mysqli_fetch_assoc($res))
        {      
        $candidato_partido[$i] = [
            'AGRUPACION' => $fila['agrupacion_nombre'],
            'EXTRANJEROS' => $fila['votos_extranjeros'],
            'NATIVOS' => $fila['votos_nativos'],
            'TOTAL' => $fila['votos_total'],
        ];
        $i++;
        }


    //CONSULTA POR SECCION
    
        $sql = "SELECT agrupacion_nombre,
        SUM(case when mesa_tipo='NATIVOS' then votos_cantidad ELSE 0 end ) AS votos_nativos,
        SUM(case when mesa_tipo='EXTRANJEROS' then votos_cantidad ELSE 0 end ) AS votos_extranjeros,
        SUM(votos_cantidad) AS votos_total
        FROM
        pba_gobernador_2023
        WHERE
        seccionprovincial_id = {$idConsulta} and
        votos_tipo = 'POSITIVO'
        GROUP BY agrupacion_nombre
        ORDER BY votos_total DESC LIMIT 4";
        
    
        $res = mysqli_query($conexion2, $sql) or die(mysqli_error($conexion2));
    
        $i = 1;
        while($fila = mysqli_fetch_assoc($res))
        {      
        $candidato_seccion[$i] = [
            'AGRUPACION' => $fila['agrupacion_nombre'],
            'EXTRANJEROS' => $fila['votos_extranjeros'],
            'NATIVOS' => $fila['votos_nativos'],
            'TOTAL' => $fila['votos_total'],
        ];
        $i++;
        }


    //CONSULTA PROVINCIA
            $sql = "SELECT agrupacion_nombre,
            SUM(case when mesa_tipo='NATIVOS' then votos_cantidad ELSE 0 end ) AS votos_nativos,
            SUM(case when mesa_tipo='EXTRANJEROS' then votos_cantidad ELSE 0 end ) AS votos_extranjeros,
            SUM(votos_cantidad) AS votos_total
            FROM
            pba_gobernador_2023
            WHERE
            distrito_id = 02 and
            votos_tipo = 'POSITIVO'	
            GROUP BY agrupacion_nombre
            ORDER BY votos_total DESC LIMIT 4";
        
    
        $res = mysqli_query($conexion2, $sql) or die(mysqli_error($conexion2));
    
        $i = 1;
        while($fila = mysqli_fetch_assoc($res))
        {      
        $candidato_provincia[$i] = [
            'AGRUPACION' => $fila['agrupacion_nombre'],
            'EXTRANJEROS' => $fila['votos_extranjeros'],
            'NATIVOS' => $fila['votos_nativos'],
            'TOTAL' => $fila['votos_total'],
        ];
        $i++;
        }



  
}
catch (Exception $e) {
return $e->getMessage();
}
?>







<div class="modal-header" style="background-color: #009aae">
    <h2 class="modal-title text-white">
    <?php echo   "Partido: ". $nombreHeader. " - Sección Electoral : ".$idConsulta; ?>
    </h2>
<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background-color: #009aae">
    <span aria-hidden="true">&times;</span>
</button>-->
</div>


<div class="modal-body">
    <br> 
    
    <div class="accordion" id="accordionExample">

        <!--  Modulo 1   -->
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Resultados por partido: <?php echo $nombreHeader; ?>
            </button>
            </h2>
                <div id="collapseOne" class="accordion-collapse collapse    " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: center">
                    
                <div id="top_x_div" style="width: 90%; height: auto; margin:auto"></div> 
                <br>
                <br>
                
                <h3 style="color: #009aae">Datos por Agrupacion</h3>
                            <table class=".table-striped" style="width: 100%" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">
                            <tr>   
                                <td>&nbsp;</td>
                                <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                                <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                                <td align="center"><b style="color:#009aae;">Total</b></td>       
                            </tr>


                            <tr>
                                <td><b style="color:#009aae;" id="partido_candidato_1">&nbsp;<?php echo $candidato_partido[1]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[1]['NATIVOS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[1]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center" id="vot_1_partido"><?php echo number_format($candidato_partido[1]['TOTAL'], 0, '.', '.'); ?></td>
                            
                            </tr>
                        
                            <tr>
                                <td><b style="color:#009aae;" id="partido_candidato_2">&nbsp;<?php echo $candidato_partido[2]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[2]['NATIVOS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[2]['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center" id="vot_2_partido"><?php echo number_format($candidato_partido[2]['TOTAL'], 0, '.', '.');  ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;" id="partido_candidato_3">&nbsp;<?php echo $candidato_partido[3]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[3]['NATIVOS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[3]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center" id="vot_3_partido"><?php echo number_format($candidato_partido[3]['TOTAL'], 0, '.', '.'); ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;" id="partido_candidato_4">&nbsp;<?php echo $candidato_partido[4]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[4]['NATIVOS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[4]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center" id="vot_4_partido"><?php echo number_format($candidato_partido[4]['TOTAL'], 0, '.', '.'); ?></td>                        
                            </tr>  
                        </table>             
                    <br>
                    <br> 
                    <br>
                   
                </div>
                </div>
            </div>

        <!--  Modulo 2   -->
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            Resultados por Seccion:  <?php echo " - Sección Electoral ".$idConsulta; ?>
            </button>
            </h2>
                <div id="collapseTwo" class="accordion-collapse collapse " aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: center">

                <div id="top_x_div2" style="width: 90%; height: 500px; margin:auto"></div> 
                <br>
                <br>
                
                <h3 style="color: #009aae">Datos por Agrupacion</h3>
                    <table style="width: 100%" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">
                    <tr>   
                        <td>&nbsp;</td>
                        <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                        <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                        <td align="center"><b style="color:#009aae;">Total</b></td>                
                       <!--  <td align="center"><b style="color:#009aae;">Blanco</b></td>           -->     
                    </tr>

                    <tr>
                        <td><b style="color:#009aae;" id="seccion_candidato_1">&nbsp;<?php echo $candidato_seccion[1]['AGRUPACION'];?></b></td>
                        <td align="center"><?php echo number_format($candidato_seccion[1]['NATIVOS'], 0, '.', '.'); ?></td>
                        <td align="center"><?php echo number_format($candidato_seccion[1]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                        <td align="center" id="vot_1_seccion"><?php echo number_format($candidato_seccion[1]['TOTAL'], 0, '.', '.'); ?></td>

                    </tr>

                    <tr>
                        <td><b style="color:#009aae;" id="seccion_candidato_2">&nbsp;<?php echo $candidato_seccion[2]['AGRUPACION'];?></b></td>
                        <td align="center"><?php echo number_format($candidato_seccion[2]['NATIVOS'], 0, '.', '.');  ?></td>
                        <td align="center"><?php echo number_format($candidato_seccion[2]['EXTRANJEROS'], 0, '.', '.');  ?></td>
                        <td align="center" id="vot_2_seccion"><?php echo number_format($candidato_seccion[2]['TOTAL'], 0, '.', '.');  ?></td>

                    </tr>
                    <tr>
                        <td><b style="color:#009aae;" id="seccion_candidato_3">&nbsp;<?php echo $candidato_seccion[3]['AGRUPACION'];?></b></td>
                        <td align="center"><?php echo number_format($candidato_seccion[3]['NATIVOS'], 0, '.', '.'); ?></td>
                        <td align="center"><?php echo number_format($candidato_seccion[3]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                        <td align="center" id="vot_3_seccion"><?php echo number_format($candidato_seccion[3]['TOTAL'], 0, '.', '.'); ?></td>

                    </tr>
                    <tr>
                        <td><b style="color:#009aae;" id="seccion_candidato_4">&nbsp;<?php echo $candidato_seccion[4]['AGRUPACION'];?></b></td>
                        <td align="center"><?php echo number_format($candidato_seccion[4]['NATIVOS'], 0, '.', '.'); ?></td>
                        <td align="center"><?php echo number_format($candidato_seccion[4]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                        <td align="center" id="vot_4_seccion"><?php echo number_format($candidato_seccion[4]['TOTAL'], 0, '.', '.'); ?></td>

                    </tr>
                    <tr></tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    
                    
                    
                </div>
                </div>
            </div>


        <!--  Modulo 3   -->
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                Total resultados en Provincia.
            </button>
            </h2>
                <div id="collapseThree" class="accordion-collapse collapse " aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: center">                   
                    <div id="top_x_div3" style="width: 90%; height: 500px; margin:auto"></div>
                    <br>
                <br> 
                    <h3 style="color: #009aae">Datos por Agrupacion</h3>
                    <table style="width: 100%" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">
                        <tr>   
                            <td>&nbsp;</td>
                            <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                            <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                            <td align="center"><b style="color:#009aae;">Total</b></td>                
                            <!--    <td align="center"><b style="color:#009aae;">Blanco</b></td>      -->          
                        </tr>

                        <tr>
                            <td><b style="color:#009aae;" id="provincia_candidato_1">&nbsp;<?php echo $candidato_provincia[1]['AGRUPACION'];?></b></td>
                            <td align="center"><?php echo number_format($candidato_provincia[1]['NATIVOS'], 0, '.', '.'); ?></td>
                            <td align="center"><?php echo number_format($candidato_provincia[1]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                            <td align="center" id="vot_1_provincia"><?php echo number_format($candidato_provincia[1]['TOTAL'], 0, '.', '.'); ?></td>

                        </tr>

                        <tr>
                            <td><b style="color:#009aae;" id="provincia_candidato_2">&nbsp;<?php echo $candidato_provincia[2]['AGRUPACION'];?></b></td>
                            <td align="center"><?php echo number_format($candidato_provincia[2]['NATIVOS'], 0, '.', '.');  ?></td>
                            <td align="center"><?php echo number_format($candidato_provincia[2]['EXTRANJEROS'], 0, '.', '.');  ?></td>
                            <td align="center" id="vot_2_provincia"><?php echo number_format($candidato_provincia[2]['TOTAL'], 0, '.', '.');  ?></td>

                        </tr>
                        <tr>
                            <td><b style="color:#009aae;" id="provincia_candidato_3">&nbsp;<?php echo $candidato_provincia[3]['AGRUPACION'];?></b></td>
                            <td align="center"><?php echo number_format($candidato_provincia[3]['NATIVOS'], 0, '.', '.'); ?></td>
                            <td align="center"><?php echo number_format($candidato_provincia[3]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                            <td align="center" id="vot_3_provincia"><?php echo number_format($candidato_provincia[3]['TOTAL'], 0, '.', '.'); ?></td>

                        </tr>
                        <tr>
                            <td><b style="color:#009aae;" id="provincia_candidato_4">&nbsp;<?php echo $candidato_provincia[4]['AGRUPACION'];?></b></td>
                            <td align="center"><?php echo number_format($candidato_provincia[4]['NATIVOS'], 0, '.', '.'); ?></td>
                            <td align="center"><?php echo number_format($candidato_provincia[4]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                            <td align="center" id="vot_4_provincia"><?php echo number_format($candidato_provincia[4]['TOTAL'], 0, '.', '.'); ?></td>

                        </tr>
                        <tr></tr>
                        </table>
                        <br>
                        <br>
                        <br>
                       
                </div>
                </div>
            </div>


        <!--  Modulo 4   -->
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                Establecimientos y mesas
            </button>
            </h2>
                <div id="collapseFour" class="accordion-collapse collapse  " aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: left"> 
                    <h3 style="color: #009aae">Datos por Sección Electoral</h3>
                            <table style="width: 100%" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">

                            <tr>
                                <td>&nbsp;</td>
                                <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                                <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                                <td align="center"><b style="color:#009aae;">Total</b></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-sharp fa-school-flag"></i>&nbsp;Establecimientos</b></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_seccion['NACIONALES'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_seccion['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_seccion['TOTAL'], 0, '.', '.'); ?></td>
                            
                            </tr>
                        
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-solid fa-boxes-packing"></i>&nbsp;Mesas</b></td>
                                <td align="center"><?php echo number_format($array_mesas_por_seccion['NACIONALES'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_mesas_por_seccion['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_mesas_por_seccion['TOTAL'], 0, '.', '.');  ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-solid fa-person"></i>&nbsp;Electores</b></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['NACIONALES'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['TOTAL'], 0, '.', '.'); ?></td>
                        
                            </tr>

                        </table>
                        <br>
                        <h3 style="color: #009aae">Datos Unidades Carcelarias</h3>
                        <table bgcolor="#FFFFFF" border="3"style="width: 100%"   bordercolor="#009aae" cellpadding="5" cellspacing="0">

                            <tr>
                                <td>&nbsp;</td>
                                <td align="center"><b style="color:#009aae;">Mesas</b></td>
                                <td align="center"><b style="color:#009aae;">Electores</b></td>
                            
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-solid fa-handcuffs"></i>&nbsp;Unidades Carcelarias</b></td>
                                <td align="center"><?php echo  $presos['cantidad_mesas']; ?></td>
                                <td align="center"><?php echo $presos['cantidad_electores']; ?></td>
                                
                            </tr>
                            <tr></tr>
                        

                        </table>


                        <br>
                        <h3 style="color: #009aae">Datos por Partido</h3>
                        <table style="width: 100%" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">

                            <tr>
                                <td>&nbsp;</td>
                                <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                                <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                                <td align="center"><b style="color:#009aae;">Total </b></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-sharp fa-school-flag"></i>&nbsp;Establecimientos</b></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_partido['NACIONALES'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_partido['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_partido['TOTAL'], 0, '.', '.');  ?></td>
                                
                            </tr>
                        
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-solid fa-boxes-packing"></i>&nbsp;Mesas</b></td>
                                <td align="center"><?php echo number_format($array_mesas_por_partido['NACIONALES'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_mesas_por_partido['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_mesas_por_partido['TOTAL'], 0, '.', '.'); ?></td>
                                
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;"><i class="fa-solid fa-person"></i>&nbsp;Electores</b></td>
                                <td align="center"><?php echo number_format($array_electores_por_partido['NACIONALES'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_partido['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_partido['TOTAL'], 0, '.', '.');  ?></td>
                                
                            </tr>

                        </table> 
                        
                        


                    </div>
                </div>
            </div>

       



    
    
        </div>
    <br> 
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>    

<script>
    
    document.addEventListener("DOMContentLoaded", function () {
        // Obtén el ID del contenedor del gráfico
        var chartContainerId = 'top_x_div';
        
        // Busca el acordeón por su ID
        var acordeon = document.getElementById('collapseOne');
        console.log('>>',   acordeon);
        // Escucha el evento de Bootstrap cuando el acordeón se está mostrando
        acordeon.addEventListener('shown.bs.collapse', function () {
            // Llama a la función para cargar el gráfico con el ID del  contenedor
            cargarGrafico(chartContainerId);
            alert("¡Se mostró el acordeón!");
        });
    });
</script>