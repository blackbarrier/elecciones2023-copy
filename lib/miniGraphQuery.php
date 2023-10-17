<?php
require_once("conexion.php");

$idSeccion="063";


try{
    
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
// var_dump($candidato_seccion[3]["TOTAL"]);
$res1=intval($candidato_seccion[1]["TOTAL"]);
$res2=intval($candidato_seccion[2]["TOTAL"]);
$res3=intval($candidato_seccion[3]["TOTAL"]);
$res4=intval($candidato_seccion[4]["TOTAL"]);

var_dump($res1);
var_dump($res2);
var_dump($res3);
var_dump($res4);
