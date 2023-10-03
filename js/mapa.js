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

    let url = "/elecciones2023/lib/tablaIndex.php";
    //let url = "/test_pasos_2023/lib/tablaIndex.php";
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
    });

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
            '</div>';
        this._div.innerHTML = cardHtml;        
    }
};

info.addTo(map);


