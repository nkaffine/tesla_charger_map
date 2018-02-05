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
            $name = validInputSizeAlpha($_GET["name"], 255);
            $email = validInputSizeAlpha($_GET["email"], 255);
            $location = validInputSizeAlpha($_GET["location"], 255);
            $address = validInputSizeAlpha($_GET["address"], 255);
            $stalls = validNumbers($_GET["stalls"], 3);
            $link = validInputSizeAlpha($_GET["link"], 255);
            $rating = validNumbers($_GET["rating"], 2);
            $lng = validNumbers($_GET["lng"], 15);
            $lat = validNumbers($_GET["lat"], 15);
            $type = validNumbers($_GET["type"], 1);
            $status = validNumbers($_GET["status"], 1);
            $openDate = validInputSizeAlpha($_GET["openDate"], 255);
            $inSystem =
                ChargerReviewCreator::newUserReview($name, $email, $location, $address, $stalls, $link, $rating, $lng,
                    $lat,
                    $type, $status, $openDate);
            header('Content-Type: application/json');
            $array = array();
            $array['error'] = null;
            if ($inSystem) {
                $array['success'] = "You should see your review when you refresh the page.";
            } else {
                $array['success'] = "It may take a few days for your review to be processed.";
            }
            echo json_encode($array, JSON_PRETTY_PRINT);
        } catch (SQLNonUniqueValueException $nonUniqueValueException) {
            header('Content-Type: application/json');
            $array = array();
            $array['error'] = "There were multiple chargers in that location";
            $array['success'] = null;
            echo json_encode($array, JSON_PRETTY_PRINT);
        } catch (Exception $exception) {
            header('Content-Type: application/json');
            $array = array();
            $array['error'] = $exception->getMessage();
            $array['success'] = null;
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
