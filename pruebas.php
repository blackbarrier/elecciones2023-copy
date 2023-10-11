
<?php

$SERVER="test-cluster1.gob.gba.gob.ar";
$USUARIO="lbarrera";
$CLAVE="lb4rr3r4_PaS*";
$BASE="ResultadosPaso2021";

$conexion = mysqli_connect($SERVER,$USUARIO,$CLAVE,$BASE);

if (!$conexion) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
else
{
	echo "conecta";
}

////////////////////////

$idMunicipio = 063;

try{

  $candidatos=[$candidato1,$]
  
  $candidato1 = [ 'AGRUPACION' => "" ,'EXTRANJEROS' => 0 , 'NACIONALES' => 0 , 'TOTAL' => 0];
  
  mysqli_set_charset($conexion, 'UTF8');
  
  $sql =
  "SELECT  seccion_nombre,  agrupacion_nombre AS agrupacion,  mesa_tipo,	  SUM(votos_cantidad) AS cant_votos
  FROM
  pba_gobernador_2023 AS t1
  INNER JOIN  (
      SELECT  
      agrupacion_nombre AS agrupaciones,
      SUM(votos_cantidad) AS votos_positivos
    FROM
      pba_gobernador_2023
    WHERE
      seccion_id = {$idMunicipio} and votos_tipo = 'POSITIVO'   	 		 
    GROUP BY
      agrupacion_nombre
  ORDER BY
    votos_positivos DESC LIMIT 4
  ) AS t2

  ON t1.agrupacion_nombre = t2.agrupaciones

  WHERE
  seccion_id = {$idMunicipio} and
  votos_tipo = 'POSITIVO' 

  GROUP BY
  agrupacion_nombre, mesa_tipo
  ORDER BY
  cant_votos DESC ";


  $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

  while($fila = mysqli_fetch_assoc($res))
  {

      $candidato1['AGRUPACION'] = $fila['agrupacion'];
      if($fila['padron'] == 'EXTRANJEROS')
      {
          $candidato1['EXTRANJEROS'] = $fila['cant_votos'];
      }
      if($fila['padron'] == 'NACIONALES')
      {
          $candidato1['NACIONALES'] = $fila['cant_votos'];
      }
      // $array_establecimientos_por_partido['TOTAL'] += $fila['cant_votos'];
      $candidato1['TOTAL'] = $fila['cant_votos'];

    }
    var_dump($candidato1);
  


}
catch (Exception $e) {
  return $e->getMessage();
}