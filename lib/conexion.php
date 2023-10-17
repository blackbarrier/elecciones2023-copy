<?php

$SERVER="apprpp-desa.gob.gba.gov.ar";
$USUARIO="dev";
$CLAVE="12345.a";
$BASE="paso_2023";


$SERVER2="test-cluster1.gob.gba.gob.ar";
$USUARIO2="lbarrera";
$CLAVE2="lb4rr3r4_PaS*";
$BASE2="ResultadosPaso2021";

    




$conexion = mysqli_connect($SERVER,$USUARIO,$CLAVE,$BASE);

if (!$conexion) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
else
{
	//echo "conecta";
}


$conexion2 = mysqli_connect($SERVER2,$USUARIO2,$CLAVE2,$BASE2);

if (!$conexion2) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
else
{
	//echo "conecta";
}

?>
