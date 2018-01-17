/**
 * Created by Nick on 1/8/18.
 */
class DestinationCharger {
    /**
     * Gets a destination charger from the json representation of the destination charger.s
     * @param charger
     */
    constructor(charger) {
        this.innerCharger = new Charger(charger);
    }

    addListenerToMarker(marker, map, infowindows) {
        this.innerCharger.addListenerToMarker(marker, map, infowindows,
            this.innerCharger.getInfoWindowContent(this.getInformationForDisplay()));
    }

    addMarkerToMap(map, infowindows) {
        let marker = this.innerCharger.createMarker(map, this.getIcon());
        this.addListenerToMarker(marker, map, infowindows);
    }

    getInformationForDisplay() {
        let content = this.innerCharger.name;
        content += "<br>" + this.innerCharger.address;
        if (this.innerCharger.rating !== null) {
            content += "<br>" + this.innerCharger.rating + "/10";
        }
        return content;
    }

    getIcon() {
        let filename = this.innerCharger.type;
        if (this.innerCharger.reviews.length > 0) {
            filename += "_reviews";
        }
        return {
            url: "/images/" + filename + ".png", scaledSize: new google.maps.Size(35.84, 64)
        };
    }
}
