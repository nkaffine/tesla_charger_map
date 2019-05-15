/**
 * Created by Nick on 1/3/18.
 */
function initMap() {
    let infoWindows = [];

    $(document).ready(function () {
        let link = '/superchargers.php';
        let map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4, center: {lat: 40, lng: -100}
        });
        $.getJSON(link, function (data) {
            processSuperChargers(data, map, infoWindows);
        });
        link = "/countries.php";
        $.getJSON(link, function (data) {
            getDestinationChargers(data, map, infoWindows, []);
        });
    });
}
function handleError(error) {
    document.getElementById('error').innerHTML = "<input type='hidden' name='error' value='" + error + "'>";
}

function processSuperChargers(data, map, infoWindows) {
    let markers = [];
    for (let i in data) {
        let charger = new SuperCharger(data[i]);
        markers.push(charger.addMarkerToMap(map, infoWindows));
    }
    let markerCluster = new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
}

function getDestinationChargers(data, map, infoWindows, markers) {
    if (data.length === 0) {
        let newMarkerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    } else {
        let link = "/destinationchargers.php?country=" + encodeURIComponent(data[0]);
        $.getJSON(link, function (chargers) {
            for (let i in chargers) {
                let charger = new DestinationCharger(chargers[i]);
                markers.push(charger.addMarkerToMap(map, infoWindows));
            }
            getDestinationChargers(data.slice(1, data.length), map, infoWindows, markers);
        });
    }
}