/**
 * Created by Nick on 1/3/18.
 */
class ChargerReview {
    constructor(review) {
        this.link = review.link;
        this.reviewer = review.reviewer;
        this.rating = review.rating;
        this.reviewDate = review.reviewDate;
        this.episode = review.episode;
    }

    getEmbedLink() {
        let elements = this.link.split(".");
        let first = "";
        let rest = "";
        if (elements.length === 2) {
            first = "https://www.youtube.com/embed";
            rest = elements[1].substr(2, elements[1].length);
        } else {
            if (elements[2].substr(4, 5) === "watch") {
                let part = elements[2].split("=");
                part = part[1].split("&");
                first = "https://www.youtube.com/embed/";
                rest = part[0];
            } else {
                first = elements[0] + "." + elements[1];
                rest = elements[2].substr(0, 3) + "/embed" + elements[2].substr(3, elements[2].length);
            }
        }
        return first + rest;
    }

    getEpisodeLink() {
        if (this.episode === null) {
            return "";
        } else {
            // return "Seen in <a href='" + this.episode + "'>Tesla Time News</a>";
            return "";
        }
    }

}