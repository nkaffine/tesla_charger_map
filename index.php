<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 11:23 AM
     */

    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertIncrementQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/Page.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/update/UpdateQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/LocationBox.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/Location.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/CustomerQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/SuperCharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/ChargerReviewCreator.php");
    header("Access-Control-Allow-Origin: *");


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
        try {

            header('Content-Type: application/json');
            $array = array();
            $array['error'] = null;
            echo json_encode($array, JSON_PRETTY_PRINT);
        } catch (SQLNonUniqueValueException $nonUniqueValueException) {
            header('Content-Type: application/json');
            $array = array();
            $array['error'] = "There were multiple chargers in that location";
            echo json_encode($array, JSON_PRETTY_PRINT);
        } catch (Exception $exception) {
            header('Content-Type: application/json');
            $array = array();
            $array['error'] = $exception->getMessage();
            echo json_encode($array, JSON_PRETTY_PRINT);
        }
    } else {
        $page = new DefaultPage("Super Chargers");
        $page->addStyleSheet("/main.css");
        $page->addJSFiles(array("/view/map.js", "/view/charger.js", "/view/chargerReview.js", "/view/superCharger.js",
            "/view/destinationCharger.js"));
        $page->addToBody("<div style='width:100%;' id='map'></div>", Page::BOTTOM);
        $page->addToHead("<script async defer src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyDs2nMmdnDV0o3BJCPrrfa96qTvXetPW_w&callback=initMap\"></script>",
            Page::BOTTOM);
        echo $page->generateHtml();
    }
