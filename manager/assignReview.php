<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/3/18
     * Time: 3:31 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");

    $cleanser = InputCleanserFactory::dataBaseFriendly();
    $array['success'] = null;
    $array['error'] = null;
    try {
        if (count($_GET)) {
            if (isset($_GET["charger_id"]) && isset($_GET["type"]) && isset($_GET["id"])) {
                $query = new SelectQuery("charger_not_in_system", "");
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