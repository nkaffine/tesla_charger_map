<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/3/18
     * Time: 3:31 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/notinsystem/ReviewDestinationChargerNotInSystem.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/notinsystem/ReviewSuperchargerNotInSystem.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/SuperCharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/DestinationCharger.php");

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

    $array['success'] = null;
    $array['error'] = null;
    try {
        if (count($_GET)) {
            if (isset($_GET["charger_id"]) && isset($_GET["type"]) && isset($_GET["id"])) {
                $charger_id = validNumbers($_GET["charger_id"], 10);
                $type = validInputSizeAlpha($_GET["type"], 25);
                $id = validNumbers($_GET['id'], 10);
                if ($type === "sc") {
                    $charger = new SuperCharger($charger_id, null, null, null, null, null, null, null);
                    $review =
                        new ReviewSuperchargerNotInSystem($id, null, null, null, null, null, null, null, null, null,
                            null, null, null);
                    $review->assignToSuperCharger($charger);
                } else if ($type === "dc") {
                    $charger = new DestinationCharger($charger_id, null, null, null, null);
                    $review =
                        new ReviewDestinationChargerNotInSystem($id, null, null, null, null, null, null, null, null,
                            null);
                    $review->assignToDestinationCharger($charger);
                } else {
                    throw new InvalidArgumentException("Invalid charger type");
                }
                $array['success'] = "Success";
            } else {
                throw new InvalidArgumentException("Not all required values were passed");
            }
        } else {
            throw new InvalidArgumentException("Not all required values were passed");
        }
    } catch (Exception $exception) {
        $array = array();
        $array['error'] = $exception->getMessage();
    }
    header('Content-Type: application/json');
    echo json_encode($array, JSON_PRETTY_PRINT);