/**
 * Created by Nick on 1/8/18.
 */
class Charger {
    /**
     * Gets a charger from the json representation of the charger.
     * @param charger
     */
    constructor(charger) {
        this.name = charger.name;
        this.lat = charger.lat;
        this.lng = charger.lng;
        this.address = charger.address;
        let reviews = [];
        for (let i = 0; i < charger.reviews.length; i++) {
            reviews.push(new ChargerReview(charger.reviews[i]));
        }
        this.reviews = reviews;
        this.rating = charger.rating;
        this.type = charger.type;
    }

    getPoint() {
        return new google.maps.LatLng(parseFloat(this.lat), parseFloat(this.lng))
    }

    createMarker(map, icon) {
        return new google.maps.Marker({
            map: map, position: this.getPoint(), icon: icon
        });
    }

    createTestMarker(map) {
        return new google.maps.Marker({
            map: map, position: this.getPoint()
        });
    }

    addListenerToMarker(marker, map, infowindows, content) {
        let infowindow = new google.maps.InfoWindow({
            content: content, maxWidth: 560
        });
        infowindows.push(infowindow);
        marker.addListener('click', function () {
            for (let i = 0; i < infowindows.length; i++) {
                infowindows[i].close();
            }
            infowindow.open(map, marker);
        });
    }

    addMarkerToMap(map, infowindows) {
        let marker = this.createMarker(map);
        this.addListenerToMarker(marker, map, infowindows);
        // let testMarker = this.createTestMarker(map);
        // this.addListenerToMarker(testMarker, map, infowindows);
    }

    getInfoWindowContent(content) {
        for (let i = 0; i < this.reviews.length; i++) {
            content += "<iframe width=\"450\" height=\"200\" src=\"" + this.reviews[i].getEmbedLink() + "\"" +
                " frameborder=\"0\" gesture=\"media\" allow=\"encrypted-media\" allowfullscreen></iframe>";
        }
        return content;
    }
}