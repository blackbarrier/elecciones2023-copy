<?PHP





$idConsulta = 8;
$nombreConsulta = "Seccion 8";
$idMunicipio = 063;
$nombreHeader = $nombreConsulta;
if($nombreConsulta == 'CANUELAS')
{
	$nombreHeader = 'CAÑUELAS';
}



$candidato_partido[1] = [
    'AGRUPACION' => "La libertad avanza",
    'EXTRANJEROS' => 500,
    'NATIVOS' => 500,
    'TOTAL' =>1000
];
$candidato_partido[2] = [
    'AGRUPACION' => "Union por la Patria",
    'EXTRANJEROS' => 400,
    'NATIVOS' => 500,
    'TOTAL' =>900
];
$candidato_partido[3] = [
    'AGRUPACION' => "Juntos por el cambio",
    'EXTRANJEROS' => 400,
    'NATIVOS' => 400,
    'TOTAL' =>800
];
$candidato_partido[4] = [
    'AGRUPACION' => "Frente de izquierda",
    'EXTRANJEROS' => 300,
    'NATIVOS' => 400,
    'TOTAL' =>700
];



?>

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="css/mapa.css">
    <link rel="stylesheet" type="text/css" href="Leaflet-MiniMap-master/dist/Control.MiniMap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    

<div style="width: 80%; margin :auto">
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
                    <h3 style="color: #5e5b5b">Datos por Agrupacion</h3>

                            <table class=".table-striped" style="width: 100%" bgcolor="#FFFFFF" border="2" bordercolor="3f3f3f" cellpadding="5" cellspacing="0">
                            <tr>   
                                <td>&nbsp;</td>
                                <td align="center"><b style="color:#5e5b5b;">Nacionales</b></td>
                                <td align="center"><b style="color:#5e5b5b;">Extranjeros</b></td>
                                <td align="center"><b style="color:#5e5b5b;">Total</b></td>       
                            </tr>


                            <tr>
                                <td><b style="color:#5e5b5b;" id="partido_candidato_1">&nbsp;<?php echo $candidato_partido[1]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[1]['NATIVOS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[1]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center" id="vot_1_partido"><?php echo number_format($candidato_partido[1]['TOTAL'], 0, '.', '.'); ?></td>
                            
                            </tr>
                        
                            <tr>
                                <td><b style="color:#5e5b5b;" id="partido_candidato_2">&nbsp;<?php echo $candidato_partido[2]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[2]['NATIVOS'], 0, '.', '.');  ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[2]['EXTRANJEROS'], 0, '.', '.');  ?></td>
                                <td align="center" id="vot_2_partido"><?php echo number_format($candidato_partido[2]['TOTAL'], 0, '.', '.');  ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#5e5b5b;" id="partido_candidato_3">&nbsp;<?php echo $candidato_partido[3]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[3]['NATIVOS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[3]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center" id="vot_3_partido"><?php echo number_format($candidato_partido[3]['TOTAL'], 0, '.', '.'); ?></td>
                        
                            </tr>
                            <tr>
                                <td><b style="color:#5e5b5b;" id="partido_candidato_4">&nbsp;<?php echo $candidato_partido[4]['AGRUPACION'];?></b></td>
                                <td align="center"><?php echo number_format($candidato_partido[4]['NATIVOS'], 0, '.', '.'); ?></td>
                                <td align="center"><?php echo number_format($candidato_partido[4]['EXTRANJEROS'], 0, '.', '.'); ?></td>
                                <td align="center" id="vot_4_partido"><?php echo number_format($candidato_partido[4]['TOTAL'], 0, '.', '.'); ?></td>                        
                            </tr>  
                        </table>             
                    <br>
                    <br> 
                    <br>
                    <div id="top_x_div" style="width: 90%; height: 500px; margin:auto">asdsa</div> 
                </div>
                </div>
            </div>
    </div>
    <br> 
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>    

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
        });
    });



    
    // function accordionEvents (){     
            
    //         var chartContainer1 = 'top_x_div';        
    //         var chartContainer2 = 'top_x_div2';        
    //         var chartContainer3 = 'top_x_div3';   
    
    //         cargarGrafico(chartContainer1);
    //         cargarGrafico(chartContainer2);
    //         cargarGrafico(chartContainer3);
        
    //         var acordeon = document.getElementById('collapseOne');       
    //         var acordeon2 = document.getElementById('collapseTwo');       
    //         var acordeon3 = document.getElementById('collapseThree');     
    
    //         acordeon.addEventListener('shown.bs.collapse', function () {           
    //             cargarGrafico(chartContainer1);
    //         });
    //         acordeon2.addEventListener('shown.bs.collapse', function () {           
    //             cargarGrafico(chartContainer2);
    //         });
    //         acordeon3.addEventListener('shown.bs.collapse', function () {           
    //             cargarGrafico(chartContainer3);
    //         });

    //         console.log("Abriendo")
    // }


function cargarGrafico(id){ 

let Candidato1, Candidato2, Candidato3, Candidato4;
let vot1, vot2, vot3, vot4;

if(id=='top_x_div'){
Candidato1=document.getElementById("partido_candidato_1").textContent;
Candidato2=document.getElementById("partido_candidato_2").textContent;
Candidato3=document.getElementById("partido_candidato_3").textContent;
Candidato4=document.getElementById("partido_candidato_4").textContent;  

vot1 = parseInt(document.getElementById("vot_1_partido").textContent.replace(/\./g, ""));
vot2 = parseInt(document.getElementById("vot_2_partido").textContent.replace(/\./g, ""));
vot3 = parseInt(document.getElementById("vot_3_partido").textContent.replace(/\./g, ""));
vot4 = parseInt(document.getElementById("vot_4_partido").textContent.replace(/\./g, ""));  
}

if(id=='top_x_div2'){
Candidato1=document.getElementById("seccion_candidato_1").textContent;
Candidato2=document.getElementById("seccion_candidato_2").textContent;
Candidato3=document.getElementById("seccion_candidato_3").textContent;
Candidato4=document.getElementById("seccion_candidato_4").textContent;

vot1 = parseInt(document.getElementById("vot_1_seccion").textContent.replace(/\./g, ""));
vot2 = parseInt(document.getElementById("vot_2_seccion").textContent.replace(/\./g, ""));
vot3 = parseInt(document.getElementById("vot_3_seccion").textContent.replace(/\./g, ""));
vot4 = parseInt(document.getElementById("vot_4_seccion").textContent.replace(/\./g, ""));   
}

if(id=='top_x_div3'){
Candidato1=document.getElementById("provincia_candidato_1").textContent;
Candidato2=document.getElementById("provincia_candidato_2").textContent;
Candidato3=document.getElementById("provincia_candidato_3").textContent;
Candidato4=document.getElementById("provincia_candidato_4").textContent;

vot1 = parseInt(document.getElementById("vot_1_provincia").textContent.replace(/\./g, ""));
vot2 = parseInt(document.getElementById("vot_2_provincia").textContent.replace(/\./g, ""));
vot3 = parseInt(document.getElementById("vot_3_provincia").textContent.replace(/\./g, ""));
vot4 = parseInt(document.getElementById("vot_4_provincia").textContent.replace(/\./g, "")); 
}


  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawStuff);
  function drawStuff() {
    var data = new google.visualization.arrayToDataTable([
      ['Candidatos', 'Votos'],
      [Candidato1, vot1],
      [Candidato2, vot2],   
      [Candidato3, vot3],
      [Candidato4, vot4]
    ]);

    data.sort({column: 1, desc: true});

    var options = {
      title: 'Candidatos',
      width: '100%',
      height: 350,
      legend: { position: 'none' },
      colors: ['#009aae', '#009aae', '#009aae', '#009aae'],
      
      bars: 'horizontal', // Required for Material Bar Charts.
      axes: {
        x: {
          0: { side: 'top', label: 'Cantidad de votos'} // Top x-axis.
        },
        y: {
            0: { side: 'left', label: ''} // Top x-axis.
          }
      },
      bar: { groupWidth: 20 }
    };

    var chart = new google.charts.Bar(document.getElementById(id));
    chart.draw(data, options);
  };
}





</script>