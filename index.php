<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 11:23 AM
     */

    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertIncrementQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/Page.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/update/UpdateQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/LocationBox.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/Location.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model2/ChargerReview.php");
    header("Access-Control-Allow-Origin: *");

    if (count($_GET)) {
        try {
            $cleanser = InputCleanserFactory::dataBaseFriendly();
            $name = $cleanser->cleanse($_GET["name"]);
            $email = $cleanser->cleanse($_GET["email"]);
            $location = $cleanser->cleanse($_GET["location"]);
            $address = $cleanser->cleanse($_GET["address"]);
            $link = $cleanser->cleanse($_GET["link"]);
            $rating = $cleanser->cleanse($_GET["rating"]);
            $lng = $cleanser->cleanse($_GET["lng"]);
            $lat = $cleanser->cleanse($_GET["lat"]);
            $type = $cleanser->cleanse($_GET["type"]);
            if ($type > 1 || $type < 0) {
                throw new InvalidArgumentException("Invalid type");
            }
            if ($rating > 10 || $rating < 0) {
                throw new InvalidArgumentException("Invalid rating scale");
            }
            $inSystem = ChargerReview::addReview($name, $email, $location, $address, $link, $rating, $lng, $lat, $type);
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
        $page->addToHead("<script src='/markerclusterer.js'></script>", Page::BOTTOM);
        echo $page->generateHtml();
    }
