<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Candidatos', 'Porcentaje'],
          ["Candidato1", 44],
          ["Candidato2", 31],
          ["Candidato3", 12],
          ["Candidato4", 10]
        ]);

        var options = {
          title: 'Chess opening moves',
          width: 900,
          legend: { position: 'none' },
          chart: { title: 'Votos por candidato',
                   subtitle: 'por cantidad y porcentaje' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Cantidad de votos'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
    </script>
  </head>
  <body>
    <div id="top_x_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>


<?php

$sql = "WITH RankedVotes AS (
        SELECT
          seccion_id,
          agrupacion_nombre,
          SUM(votos_cantidad) as suma_votos_positivos,
          RANK() OVER (PARTITION BY seccion_id ORDER BY SUM(votos_cantidad) DESC) as rango
        FROM
          pba_gobernador_2023
        WHERE
          votos_tipo = 'POSITIVO' AND mesa_tipo = 'NATIVOS' AND seccion_id={$idMunicipio}
        GROUP BY
          seccion_id,
          agrupacion_nombre
        )
        SELECT
            seccion_id,
            agrupacion_nombre,
            suma_votos_positivos
        FROM
            RankedVotes
        WHERE
            rango <= 4;";

        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
        //$array_por_partido = mysqli_fetch_assoc($res);

        
        $array_votos1_por_partido_nac =  $array_por_partido[0]["suma_votos_positivos"];
        $array_votos2_por_partido_nac =  $array_por_partido[1]["suma_votos_positivos"];
        $array_votos3_por_partido_nac =  $array_por_partido[2]["suma_votos_positivos"];
        $array_votos4_por_partido_nac =  $array_por_partido[3]["suma_votos_positivos"];
