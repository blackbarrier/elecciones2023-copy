
  <?php

  $SERVER="test-cluster1.gob.gba.gob.ar";
  $USUARIO="lbarrera";
  $CLAVE="lb4rr3r4_PaS*";
  $BASE="ResultadosPaso2021";

  $conexion2 = mysqli_connect($SERVER,$USUARIO,$CLAVE,$BASE);

  if (!$conexion2) {
      echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
      echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;  
      echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
      exit;
  }
  else
  {
    echo "conecta";
  }


  
  $idMunicipio = "063";
  $idConsulta = 1;
  

  //Add by Lean Barrera

try{
    
  mysqli_set_charset($conexion2, 'UTF8');

  //CONSULTA POR PARTIDO
  
  $sql = "SELECT seccion_nombre, agrupacion_nombre,
  SUM(case when mesa_tipo='NATIVOS' then votos_cantidad ELSE 0 end ) AS votos_nativos,
  SUM(case when mesa_tipo='EXTRANJEROS' then votos_cantidad ELSE 0 end ) AS votos_extranjeros,
  SUM(votos_cantidad) AS votos_total
  FROM
  pba_gobernador_2023
  WHERE
  seccion_id = {$idMunicipio} and
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
  
  $sql = "SELECT seccionprovincial_nombre, agrupacion_nombre,
  SUM(case when mesa_tipo='NATIVOS' then votos_cantidad ELSE 0 end ) AS votos_nativos,
  SUM(case when mesa_tipo='EXTRANJEROS' then votos_cantidad ELSE 0 end ) AS votos_extranjeros,
  SUM(votos_cantidad) AS votos_total
  FROM
  pba_gobernador_2023
  WHERE
  seccionprovincial_id = 7 and
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
    var_dump($candidato_seccion[$i]);
    $i++;
  }

  ////////////////////////
} catch (Exception $e) {
  return $e->getMessage();
}
  