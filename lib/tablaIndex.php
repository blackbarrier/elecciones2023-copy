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



    $candidatos_intendentes=array();
    $candidatos_consejales=array();
    //-- CANDIDATOS
    $sql_candidatos=" SELECT * FROM `candidato_municipal` WHERE seccion='{$nombreConsulta}' order by intendente ";

    $res_candidatos = mysqli_query($conexion, $sql_candidatos) or die(mysqli_error($conexion));
    while($fila_candidatos = mysqli_fetch_assoc($res_candidatos))
    {
         $candidatos_intendentes[] = "<b>".$fila_candidatos['intendente']."</b> (".$fila_candidatos['fuerza_politica']." - ".$fila_candidatos['linea'].")";
         $candidatos_consejales[] = "<b>".$fila_candidatos['primer_concejal']."</b>  (".$fila_candidatos['fuerza_politica']." - ".$fila_candidatos['linea'].")";
    }   
   


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
            Datos por partido: <?php echo $nombreHeader; ?>
            </button>
            </h2>
                <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <h3 style="color: #009aae">Datos por Agrupacion</h3>

                            <table style="width: 100%" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">
                            <tr>   
                                <td>&nbsp;</td>
                                <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                                <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                                <td align="center"><b style="color:#009aae;">Total</b></td>                
                                <td align="center"><b style="color:#009aae;">Blanco</b></td>                
                            </tr>


                            <tr>
                                <td><b style="color:#009aae;">&nbsp;Primer candidato</b></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_seccion['NACIONALES'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_seccion['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_establecimientos_por_seccion['TOTAL'], 0, '.', '.'); ?></td>
                            
                            </tr>
                        
                            <tr>
                                <td><b style="color:#009aae;">&nbsp;Segundo candidato</b></td>
                                <td align="center"><?php echo number_format($array_mesas_por_seccion['NACIONALES'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_mesas_por_seccion['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($array_mesas_por_seccion['TOTAL'], 0, '.', '.');  ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;">&nbsp;Tercer candidato</b></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['NACIONALES'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['TOTAL'], 0, '.', '.'); ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#009aae;">&nbsp;Cuarto candidato</b></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['NACIONALES'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($array_electores_por_seccion['TOTAL'], 0, '.', '.'); ?></td>
                        
                            </tr>
                           
                           
                           
                        </table>
                        <br>
                        <br>
                        <img src="img\grafico.png" alt="">

                        
                        <!--
                        
                        <div id="top_x_div" style="width: 900px; height: 500px;"></div> 
                    -->                        

                        <br>


                        
                </div>
                </div>
            </div>

        <!--  Modulo 2  -->
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            Datos por Seccion:  <?php echo " - Sección Electoral : ".$idConsulta; ?>
            </button>
            </h2>
                <div id="collapseTwo" class="accordion-collapse collapse " aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
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



        <!--  Modulo 3   -->
        <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
            Total de datos en Provincia.
        </button>
        </h2>
            <div id="collapseThree" class="accordion-collapse collapse " aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body"> 
                    
                </div>
            </div>
        </div>
    
    </div>
    <br> 

</div>



<div class="modal-footer">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>    
