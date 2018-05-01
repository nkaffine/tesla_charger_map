/**
 * Created by Nick on 1/3/18.
 */
class SuperCharger {
    /**
     * Gets a charger from the json representation of the charger.
     * @param charger
     */
    constructor(charger) {
        this.innerCharger = new Charger(charger);
        this.status = charger.status;
        this.stalls = charger.stalls;
        if (charger.open_date === "0000-00-00") {
            this.open_date = null;
        } else {
            this.open_date = charger.open_date;
        }
    }

    addListenerToMarker(marker, map, infowindows) {
        this.innerCharger.addListenerToMarker(marker, map, infowindows,
            this.innerCharger.getInfoWindowContent(this.getInformationForDisplay()));
    }

    addMarkerToMap(map, infowindows) {
        let marker = this.innerCharger.createMarker(map, this.getIcon());
        this.addListenerToMarker(marker, map, infowindows);
        return marker;
    }

    getInformationForDisplay() {
        let content = this.innerCharger.name + " (" + this.status.toLowerCase() + ")";
        if (this.stalls !== null) {
            content += " " + this.stalls + " stalls";
        }
        content += "<br>" + this.innerCharger.address;
        if (this.open_date !== null) {
            content += "<br>Opened: " + this.open_date;
        }
        if (this.innerCharger.rating !== undefined) {
            content += "<br>" + this.innerCharger.rating + "/10";
        }
        return "<div>" + content + "</div>";
    }

    getIcon() {
        let filename = this.innerCharger.type + "_" + this.status.toLowerCase();
        if (this.innerCharger.reviews.length > 0) {
            filename += "_reviews";
        }
        return {
            url: "/images/" + filename + ".png", scaledSize: new google.maps.Size(35.84, 64)
        };
    }
}