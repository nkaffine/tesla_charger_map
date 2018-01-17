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
            for (let i = 0; i < data.results.length; i++) {
                let charger = new SuperCharger(data.results[i]);
                charger.addMarkerToMap(map, infoWindows);
            }
        });
        link = "/destinationchargers.php";
        $.getJSON(link, function (data) {
            for(let i = 0; i < data.results.length; i++) {
                let charger = new DestinationCharger(data.results[[i]]);
                charger.addMarkerToMap(map, infoWindows);
            }
        });
    });
}
function handleError(error) {
    document.getElementById('error').innerHTML = "<input type='hidden' name='error' value='" + error + "'>";
}