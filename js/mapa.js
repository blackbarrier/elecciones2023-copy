

// set posicion inicial del mapa
const EXTRANJERO ="EXTRANJEROS";
const NACIONAL ="NACIONALES";


/**
 * Mapa base de OpenStreetMap
 */
let map = L.map('map').setView([-37, -60], 6.3)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


// Agregar mapa base
var carto_light = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '©OpenStreetMap, ©CartoDB', subdomains: 'abcd', maxZoom: 24 });

// Agregar plugin MiniMap
var minimap = new L.Control.MiniMap(carto_light,
    {
        toggleDisplay: true,
        minimized: false,
        position: "bottomleft"
    }).addTo(map);

// Agregar escala
new L.control.scale({ imperial: false }).addTo(map);

function popup(feature, layer) {
    if (feature.properties && feature.properties.nombre) {
        layer.bindPopup("<strong>" + feature.properties.id + "</strong>");
    }
}

/**
 * Dado un nro de seccion retorna un color
 * @param {string} nroSeccion 
 * @returns {string} color - hex color
 */
function getColor(nroSeccion) {
    
    /* colores gob [nroSeccion, hexColor ] */
    const palSec8 = new Map();
    palSec8.set('1', '#004d57');
    palSec8.set('2', '#006b79');
    palSec8.set('3', '#008a9c');
    palSec8.set('4', '#19a4b6');
    palSec8.set('5', '#4cb8c6');
    palSec8.set('6', '#7fccd6');
    palSec8.set('7', '#b2e0e6');
    palSec8.set('8', '#e5f4f6');

    if(palSec8.has(nroSeccion)) return palSec8.get(nroSeccion);
    throw new Error('No existe color para seccion '+ nroSeccion);
}

/**
 * Retorna un objeto con los detalles de visualizacion
 * para cada feature
 * @param {json} feature 
 * @returns {object} 
 */
function style(feature) {
    return {
        //fillColor: getColor(feature.properties.seccion),
        fillColor: feature.properties.colorSeccion,
        weight: 1,
        opacity: 1,
        color: 'white',
        dashArray: '2',
        fillOpacity: 0.7
    };
}

options = {};
const modalMapaNew = new bootstrap.Modal('#modalMapaNew', options);

L.geoJson(municipios, { style: style }).addTo(map);

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }

    info.update(layer.feature.properties);
}

function resetHighlight(e) {
    geojson.resetStyle(e.target);
    info.update();
}

var geojson;
// ... our listeners
geojson = L.geoJson(municipios);

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: function(event){
            modalNew(feature);
        }
    });
}


function modalNew(feature) {
    cargarContenidoNew(feature);
    
    $('#modalMapaNew').modal('show');
    //modalMapaNew.show();
}

function cargarContenidoNew(feature) {

    let municipioId = feature.properties.codigoMunicipio;
    let nombre = feature.properties.nombre;
    let seccionElectoral = feature.properties.seccion;
    nombre = nombre.toUpperCase();    


    //LOCAL
    let url = "/elecciones2023/lib/tablaIndex.php";

    //DESA
    // let url = "/apps/elecciones2023/lib/tablaIndex.php";

    var data = { 
        seccion_id: seccionElectoral,
        nombreConsulta: nombre,
        municipio_id: municipioId,
    };

    let formData = new FormData();
    formData.append("seccion_id", seccionElectoral);
    formData.append("nombreConsulta", nombre);
    formData.append("municipio_id", municipioId);
    
    fetch(url, {
        method: "POST",
        body: formData,
        
    }).then(response => response.text())
    .catch((error) => console.error("Error:", error))
    .then(response => { 
        //console.log("Success:", response.text());
        
        document.querySelector('.modal-content').innerHTML = response;        
            accordionEvents();        
    });
    
}



function accordionEvents (){     
        
        var chartContainer1 = 'top_x_div';        
        var chartContainer2 = 'top_x_div2';        
        var chartContainer3 = 'top_x_div3';   

        cargarGrafico(chartContainer1);
        cargarGrafico(chartContainer2);
        cargarGrafico(chartContainer3);
    
        var acordeon = document.getElementById('collapseOne');       
        var acordeon2 = document.getElementById('collapseTwo');       
        var acordeon3 = document.getElementById('collapseThree');     

        acordeon.addEventListener('shown.bs.collapse', function () {           
            cargarGrafico(chartContainer1);
        });
        acordeon2.addEventListener('shown.bs.collapse', function () {           
            cargarGrafico(chartContainer2);
        });
        acordeon3.addEventListener('shown.bs.collapse', function () {           
            cargarGrafico(chartContainer3);
        });
}


function cargarGrafico(id){ 

    var Candidato1, Candidato2, Candidato3, Candidato4;
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
        ['Candidatos', Candidato1, Candidato2, Candidato3, Candidato4],
        [' ', vot1, vot2, vot3, vot4]
]);

        data.sort({column: 1, desc: true});
        
        var options = {
          title: 'Votos por candidato',
          width: '100%',
          height: 300,
          legend: { position: 'none' },
          colors: colorearBarras([Candidato1, Candidato2, Candidato3, Candidato4]),          
          bars: 'horizontal', // Required for Material Bar Charts.
          
          axes: {
            x: {
              0: { side: 'top', label: 'Cantidad de votos', fontSize: 18,} // Top x-axis.
            },
            y: {
                0: { side: 'left', label: 'Fuerza política'} // Top x-axis.
              }
          },
          bar: { groupWidth: 10 }
        };

        var chart = new google.charts.Bar(document.getElementById(id));
        chart.draw(data, options);
      };
}

function colorearBarras(candidatos){
        var colores = [];
        var color = '#009aae';      
        for (var i = 0; i < 4; i++) {   
            
            if (candidatos[i].trim()==="JUNTOS POR EL CAMBIO"){
                color="#f4f000";                
            }
            else if (candidatos[i].trim()==="UNION POR LA PATRIA"){
                color="#43cff9";                
            }
            else if(candidatos[i].trim()==="LA LIBERTAD AVANZA"){
                color="#8144af";
            }
            else if(candidatos[i].trim()==="FRENTE DE IZQUIERDA Y DE TRABAJADORES - UNIDAD"){
                color="#dd2113";
            }else{
                color = '#009aae';   
            }
            colores.push(color);
        }

    
    return colores;

}



function cargaMiniGrafico(id, vot1, vot2, vot3, vot4){

    google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Partido');
        data.addColumn('number', 'Votos');
        data.addRows([
          ['Partido1', vot1],
          ['Partido2', vot2],
          ['Partido3', vot3],
          ['Partido4', vot4]
        ]);

        // Set chart options
        var options = {'title':'Resultados',
        'titleTextStyle': { fontSize: 15,  textAlign: 'left' },
          'width':'18rem',
         'height':'auto'};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(id));
        chart.draw(data, options);
      }
   

}

geojson = L.geoJson(municipios, {
    style: style,
    onEachFeature: onEachFeature
}).addTo(map);

var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
    this.update();
    return this._div;
};


/**
 * Update de la ventana de exploracion
 * 
 * @param {object} props 
 */
info.update = function (props) {
    if (!props) {
        this._div.innerHTML = '';
    } else {
        console.log(props);
        let cardHtml = '<div class="card" style="width: 18rem;">' +
            '<div class="card-body p-3">' +
                '<h5 class="card-title mb-0"><span style="color:'+ props.colorSeccion +'">&#9611;</span><b>'+ props.nombreSeccion +'</b></h5>' + 
            '</div>' +
            '<ul class="list-group list-group-flush">' +
            '<li class="list-group-item d-flex justify-content-between">Partido <b>'+ props.nombre +'</b></li>' +
            '<li class="list-group-item d-flex justify-content-between">Establecimientos nacionales <b>'+ props.cantidadEstablecimientos +'</b></li>' +
            '<li class="list-group-item d-flex justify-content-between">Establecimientos extranjeros <b>'+ props.cantidadEstablecimientosExtranjeros +'</b></li>' +
            '<li class="list-group-item d-flex justify-content-between">Mesas nacionales <b>'+ props.cantidadMesas +'</b></li>' +
            '<li class="list-group-item d-flex justify-content-between">Mesas extranjeras <b>'+ props.cantidadMesasExtranjeros +'</b></li>' +
            '</ul>' +
            '<div style="max-width:18rem" id="mini-graph"></div>' +
            '</div>';
        cargaMiniGrafico("mini-graph",props.res1,props.res2,props.res3,props.res4);
        this._div.innerHTML = cardHtml;        
    }
};

info.addTo(map);




