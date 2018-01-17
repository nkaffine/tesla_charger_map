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

    function withinCoords($min_lon, $max_lon, $min_lat, $max_lat) {
        return "(lng > $min_lon and lng < $max_lon) and " .
            "(lat > $min_lat and lat < $max_lat)";
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
            switch ($type) {
                case 0:
                    $type = "supercharger";
                    break;
                case 1:
                    $type = "destinationcharger";
                    break;
                default:
                    throw new InvalidArgumentException("Invalid type of charger");
            }
            $status = validNumbers($_GET["status"], 1);
            switch ($status) {
                case 0:
                    $status = "OPEN";
                    break;
                case 1:
                    $status = "PERMIT";
                    break;
                case 2:
                    $status = "CONSTRUCTION";
                    break;
                case 3:
                    $status = "CLOSED";
                    break;
                default:
                    throw new InvalidArgumentException("Invalid status");
            }
            $openDate = validInputSizeAlpha($_GET["openDate"], 255);
            $location1 = new Location($lat, $lng);
            $box = $location1->getBox(.1);
            $query = new SelectQuery("chargers", "charger_id", "lat", "lng");
            $query = new CustomerQuery($query->generateQuery() . " WHERE " .
                withinCoords($box->getMinLng(), $box->getMaxLng(), $box->getMinLat(), $box->getMaxLat()));
            $result = DBQuerrier::checkExistsQuery($query);
            if (!$result) {
                switch ($type) {
                    case "supercharger":
                        $charger =
                            SuperCharger::newCharger($location, $location1, $address, $status, $openDate, $stalls);
                        break;
                    case "destinationcharger":
                        $charger = DestinationCharger::newCharger($location, $location1, $address);
                        break;
                    default:
                        throw new InvalidArgumentException("Invalid charger type");
                }
                $primaryKey = $charger->getId();
            } else {
                $row = @ mysqli_fetch_array($result);
                $primaryKey = $row['charger_id'];
            }
            $query = new InsertIncrementQuery("reviews", "review_id");
            $query->addParamAndValues("link", DBValue::stringValue($link))
                ->addParamAndValues("reviewer", DBValue::stringValue($name))
                ->addParamAndValues("email", DBValue::stringValue($email))
                ->addParamAndValues("rating", DBValue::nonStringValue($rating))
                ->addParamAndValues("charger_id", DBValue::nonStringValue($primaryKey));
            DBQuerrier::defaultInsert($query);
            $array = array();
            $array['error'] = $exception->getMessage();
            echo json_encode($array, JSON_PRETTY_PRINT);
        } catch (SQLNonUniqueValueException $nonUniqueValueException) {
            $array = array();
            $array['error'] = "There were multiple chargers in that location";
            echo json_encode($array, JSON_PRETTY_PRINT);
        } catch (Exception $exception) {
            $array = array();
            $array['error'] = $exception->getMessage();
            echo json_encode($array, JSON_PRETTY_PRINT);
        }
        $array = array();
        $array['error'] = null;
        echo json_encode($array, JSON_PRETTY_PRINT);
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
