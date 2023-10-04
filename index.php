<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- fontawesome --><link rel="stylesheet" href="css/css_all.min.css" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="css/mapa.css">
    <link rel="stylesheet" type="text/css" href="Leaflet-MiniMap-master/dist/Control.MiniMap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <title>Elecciones 2023</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    

</head>
<body>
<span id="menuTrigger">&#x21e8;</span>
    <div id="sideMenu">
          <span id="menuClose">X</span>
        <div class="sideMenu-content m-0">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3 style="color: #009aae">
                            <img src="img/provincia.png" class="img-thumbnail" style="width: 40px; border: none;">
                            Total Provincial
                        </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="w-100" bgcolor="#FFFFFF" border="3" bordercolor="#009aae" cellpadding="5" cellspacing="0">
                <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center"><b style="color:#009aae;">Nacionales</b></td>
                    <td align="center"><b style="color:#009aae;">Extranjeros</b></td>
                    <td align="center"><b style="color:#009aae;">Total</b></td>
                </tr>
                <tr>
                    <td>
                        <svg class="svg-inline--fa fa-person" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="person" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V256.9L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6h29.7c33.7 0 64.9 17.7 82.3 46.6l58.3 97c9.1 15.1 4.2 34.8-10.9 43.9s-34.8 4.2-43.9-10.9L232 256.9V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V352H152z"></path></svg>
                        <b style="color:#009aae;">Electores</b>
                    </td>
                    <td align="center">13.024.210</td>
                    <td align="center">946.096</td>
                    <td align="center">13.970.306</td>

                </tr>
                <tr>
                    <td>
                        <svg class="svg-inline--fa fa-school-flag" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="school-flag" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 0H400c8.8 0 16 7.2 16 16V80c0 8.8-7.2 16-16 16H320.7l89.6 64H512c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H336V400c0-26.5-21.5-48-48-48s-48 21.5-48 48V512H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64H165.7L256 95.5V32c0-17.7 14.3-32 32-32zm48 240a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM80 224c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16H80zm368 16v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16H464c-8.8 0-16 7.2-16 16zM80 352c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V368c0-8.8-7.2-16-16-16H80zm384 0c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V368c0-8.8-7.2-16-16-16H464z"></path></svg>
                        <b style="color:#009aae;">
                            Establecimientos
                        </b>
                    </td>
                    <td align="center">6145</td>
                    <td align="center">386</td>
                    <td align="center">6.531</td>

                </tr>
                <tr>
                    <td>
                        <svg class="svg-inline--fa fa-boxes-packing" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="boxes-packing" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M256 48c0-26.5 21.5-48 48-48H592c26.5 0 48 21.5 48 48V464c0 26.5-21.5 48-48 48H381.3c1.8-5 2.7-10.4 2.7-16V253.3c18.6-6.6 32-24.4 32-45.3V176c0-26.5-21.5-48-48-48H256V48zM571.3 347.3c6.2-6.2 6.2-16.4 0-22.6l-64-64c-6.2-6.2-16.4-6.2-22.6 0l-64 64c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L480 310.6V432c0 8.8 7.2 16 16 16s16-7.2 16-16V310.6l36.7 36.7c6.2 6.2 16.4 6.2 22.6 0zM0 176c0-8.8 7.2-16 16-16H368c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H16c-8.8 0-16-7.2-16-16V176zm352 80V480c0 17.7-14.3 32-32 32H64c-17.7 0-32-14.3-32-32V256H352zM144 320c-8.8 0-16 7.2-16 16s7.2 16 16 16h96c8.8 0 16-7.2 16-16s-7.2-16-16-16H144z"></path></svg>
                        <b style="color:#009aae;">Mesas</b>
                    </td>
                    <td align="center">38.074</td>
                    <td align="center">2.127</td>
                    <td align="center">40.201</td>

                </tr>

                </tbody>
            </table>
                    </div>
                </div>
            </div>
</div>
    </div>
    <script>

        document.addEventListener("DOMContentLoaded", function() {
          var menuTrigger = document.getElementById("menuTrigger");
          var sideMenu = document.getElementById("sideMenu");

          menuTrigger.addEventListener("click", function() {
            sideMenu.style.left = "0"; // Set the left property to 0 to animate the element to the left border
          });
        });
        document.addEventListener("DOMContentLoaded", function() {
          var menuClose = document.getElementById("menuClose");
          var sideMenu = document.getElementById("sideMenu");

          menuClose.addEventListener("click", function() {
            sideMenu.style.left = "-50%"; // Set the left property to -300px to animate the element back to its initial position
          });
        });
    </script>

        <div class="container">
            <div class="row">
                <div class="col">
                <img src="img/logo_gba.svg" height="85" alt="Ministerio de Gobierno">
                </div>
                <div class="col-md-auto row align-items-center"><h2 style="color: #009aae;">Mapa de Distribución por Sección Electoral</h2>
            </div>
          </div>
        </div>

    <div id="map"></div>

        <div id="modalMapaNew" class="modal" tabindex="-1">
        
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
               

                </div>
            </div>
        </div>

    <script src="js/leaflet.js"></script>
    <script src="Leaflet-MiniMap-master/dist/Control.MiniMap.min.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script>
       
        /**
         * Se carga el layer desde la BD        
         */
        var municipios =<?php include("./lib/municipios_layer.php"); ?>;
    </script>
    
    <script src="layers/consulta.js?v=<?php echo rand(0,10000) ?>"></script>
    <script src="layers/secciones.js?v=<?php echo rand(0,10000) ?>"></script>

    <script src="js/mapa.js?v=<?php echo rand(0,10000) ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" 
        integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" 
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        

    <footer class="footer">
      <div class="container" align="center">
        <span class="text-muted"><b>Dirección de Informática</b></span>&nbsp;-&nbsp;
        <span class="text-muted">Ministerio de Gobierno - Provincia de Buenos Aires</span>
      </div>
    </footer>

</body>
</html>