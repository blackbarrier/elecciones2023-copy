<?php

$SERVER="apprpp-desa.gob.gba.gov.ar";
$USUARIO="dev";
$CLAVE="12345.a";
$BASE="paso_2023";

    




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
?>
