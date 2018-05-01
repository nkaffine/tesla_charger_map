/**
 * Created by Nick on 2/2/18.
 */
function get(name) {
    if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search)) {
        return decodeURIComponent(name[1]);
    }
}

function processSuperchargerData(data) {
    let rows = [];
    let link = "";
    for (let i = 0; i < data.results.length; i++) {
        rows[i] = [data.results[i].name, data.results[i].lat, data.results[i].lng, data.results[i].address,
            data.results[i].status, data.results[i].open_date, data.results[i].stalls,
            "<button class='chargerbtn btn btn-primary' data-charger-id='" + data.results[i].id +
            "'>Choose</button>"];
    }
    return createTable("Superchargers", ["Name", "Lat", "Lng", "Address", "Status", "Open Date", "Stalls", ""], rows,
        10, 1);
}

function processDestinationChargerData(data) {
    let rows = [];
    for (let i = 0; i < data.results.length; i++) {
        rows[i] = [data.results[i].name, data.results[i].lat, data.results[i].lng, data.results[i].address,
            "<button class='chargerbtn btn btn-primary' data-charger-id='" + data.results[i].id +
            "'>Choose</button>"];
    }
    return createTable("Destination Chargers", ["Name", "Lat", "Lng", "Address", ""], rows, 10, 1);
}

function createTable(title, columns, rows, width, offset) {
    let html = "<div class='col-lg-" + width + " col-lg-offset-" + offset + "' style='background-color: white;" +
        " margin-top:" + " 2%; padding-left:0; padding-right:0;'>";
    if (title !== null) {
        html += "<h1>" + title + "</h1>";
    }
    html += "<table class='col-lg-12 table table-striped' style='margin-bottom:0;'><thead>";
    for (let i = 0; i < columns.length; i++) {
        html += "<th>" + columns[i] + "</th>";
    }
    html += "</thead><tbody>";
    for (let i = 0; i < rows.length; i++) {
        html += "<tr>";
        for (let n = 0; n < columns.length; n++) {
            html += "<td>" + rows[i][n] + "</td>";
        }
        html += "</tr>"
    }
    return html + "</tbody></table></div>";
}

function generateError(error) {
    return "<div class=\"panel panel-danger\">" + "<div class=\"panel-heading\">Error:</div>" +
        "<div class=\"panel-body\">" + error + "</div>" + "</div>"
}

function generateSuccess(error) {
    return "<div class=\"panel panel-success\">" + "<div class=\"panel-heading\">Success!</div>" +
        "<div class=\"panel-body\">" + error + "</div>" + "</div>"
}

$(document).ready(function () {
    $("#searchCharger").click(function () {
        let location = $("#location").val();
        let address = $("#address").val();
        let link = null;
        if (get("type") === "sc") {
            link = "/superchargers.php";
        } else if (get("type") === "dc") {
            link = "/destinationchargers.php";
        } else {

        }
        if (location !== "" && address !== "") {
            link = link + "?location=" + encodeURIComponent(location) + "&address=" + encodeURIComponent(address);
        } else if (location !== "") {
            link = link + "?location=" + encodeURIComponent(location);
        } else if (address !== "") {
            link = link + "?address=" + encodeURIComponent(address);
        }
        $.getJSON(link, function (data) {
            let html = null;
            if (get("type") === "sc") {
                html = processSuperchargerData(data);
            } else {
                html = processDestinationChargerData(data);
            }
            $("#searchResults").html(html);
            $(".chargerbtn").click(function () {
                let link = "/assignReview.php?charger_id=" + encodeURIComponent($(this).data("charger-id")) + "&type=" +
                    encodeURIComponent(get("type")) + "&id=" + encodeURIComponent(get("id"));
                $.getJSON(link, function (data) {
                    if (data.error !== null) {
                        $("#error").html(generateError(data.error));
                    } else if (data.success === null) {
                        $("#error").html(generateError("An unexpected error occurred."));
                    } else {
                        $("#error").html(generateSuccess("The review was successfully assigned to the charger"));
                    }
                });
            });
        });
    });
});