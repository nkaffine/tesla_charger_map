<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/2/18
     * Time: 3:29 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/notinsystem/ReviewSuperchargerNotInSystem.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/notinsystem/ReviewDestinationChargerNotInSystem.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/Table.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");

    /**
     * Cleans the string by escaping and removing special characters.
     *
     * @param $uncleanString string
     * @param $maxLength int
     * @return string
     */
    function validInputSizeAlpha($uncleanString, $maxLength) {
        $sizedString = substr($uncleanString, 0, $maxLength);
        $cleanString = preg_replace("[^\.a-zA-Z' ]", '', $sizedString);
        $cleanString = str_replace("'", "\'", $cleanString);
        return ($cleanString);
    }

    /**
     * Cleans the numbers by escaping and removing non numeric chars
     *
     * @param $uncleanString string
     * @param $maxLength int
     * @return int
     */
    function validNumbers($uncleanString, $maxLength) {
        $cleanString = substr($uncleanString, 0, $maxLength);
        $cleanString = preg_replace("[^\.0-9]", '', $cleanString);
        return ($cleanString);
    }

    if (count($_GET)) {
        if (isset($_GET['type']) && isset($_GET['id'])) {
            $type = validInputSizeAlpha($_GET["type"], 25);
            $id = validNumbers($_GET["id"], 10);
            if ($type === "sc") {
                $charger =
                    new ReviewSuperchargerNotInSystem($id, null, null, null, null, null, null, null, null, null, null,
                        null, null);
                $table = new Table();
                $table->addColumns("Name", "Email", "Location", "Address", "Stalls", "Link", "Rating", "Lng", "Lat",
                    "Status", "Open Date", "Checked");
                $table->addRows(array($charger->getName(), $charger->getEmail(), $charger->getLocation(),
                    $charger->getAddress(), $charger->getStalls(), $charger->getLink(), $charger->getRating(),
                    $charger->getLng(), $charger->getLat(), $charger->getStatus(), $charger->getOpenDate(),
                    $charger->isChecked()));
                $link = "/superchargers.php";
            } else if ($type === "dc") {
                $charger =
                    new ReviewDestinationChargerNotInSystem($id, null, null, null, null, null, null, null, null, null);
                $table = new Table();
                $table->addColumns("Name", "Email", "Location", "Address", "Link", "Rating", "Lat", "Lng", "Checked");
                $table->addRows(array($charger->getName(), $charger->getEmail(), $charger->getLocation(),
                    $charger->getAddress(), $charger->getLink(), $charger->getRating(), $charger->getLat(),
                    $charger->getLng(), $charger->isChecked()));
                $link = "/destinationchargers.php";
            } else {
                throw new InvalidArgumentException("Invalid charger type");
            }
            $page = new DefaultPage("Check Review");
            $page->addStyleSheet("main.css");
            $page->addJSFile("/view/checkReview.js");
            $page->addToBody("<div class='col-lg-10 col-lg-offset-1 no-pad' id='error'></div>" .
                "<div class='col-lg-10 col-lg-offset-1 form no-pad'>" .
                "<h1 class='col-lg-12 no-pad'>Search Chargers in the System</h1>" .
                "<div class='col-lg-5 no-pad'><label class='col-lg-12 no-pad' for='location'>Chargers Location Name</label>" .
                "<input class='col-lg-12 form-control' type='text' id='location'></div>" .
                "<div class='col-lg-5 no-pad'><label class='col-lg-12 no-pad' for='address'>Charger Address</label>" .
                "<input class='col-lg-12 form-control' type='text' id='address'></div><div class='col-lg-2'>" .
                "<label class='col-lg-12' style='color:white;'>I</label>" .
                "<button class='col-lg-12 btn btn-primary no-pad' id='searchCharger'>Search</button></div></div>" .
                "<div id='searchResults' class='col-lg-12 no-pad'></div>",
                Page::TOP);
            $page->addToBody($table->getHtml(10, 1), Page::TOP);
            echo $page->generateHtml();
        } else {
            throw new InvalidArgumentException("Required values were not supplied");
        }
    } else {
        throw new InvalidArgumentException("Required values were not supplied");
    }