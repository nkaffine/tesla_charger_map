<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/27/17
     * Time: 3:54 PM
     */
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

    if (count($_POST)) {
        $ttn_id = validNumbers($_POST["ttn_id"], 11);
        $link = validNumbers($_POST["link"], 255);
        $query = new InsertQuery("ttn");
        $query->addParamAndValues("ttn_id", DBValue::nonStringValue($ttn_id));
        $query->addParamAndValues("link", DBValue::stringValue($link));
        DBQuerrier::defaultInsert($query);
        header("Location: /bobby.php");
        exit;
    } else {
        header("Location: /index.php");
        exit;
    }