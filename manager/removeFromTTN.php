<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/update/UpdateQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/27/17
     * Time: 3:16 PM
     */
    $cleanser = InputCleanserFactory::dataBaseFriendly();
    if (count($_POST)) {
        $review_id = $cleanser->cleanse($_POST["rid"]);
        $query = new CustomQuery("UPDATE review SET ttn_id to NULL");
        $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($review_id)));
        DBQuerrier::defaultUpdate($query);
        header("location: /brent.php");
        exit;
    } else {
        header("location: /index.php");
        exit;
    }