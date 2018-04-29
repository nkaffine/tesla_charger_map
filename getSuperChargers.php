<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 4/18/18
     * Time: 5:36 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $query = new CustomQuery("SELECT * FROM super_charger INNER JOIN charger USING (charger_id)");
    $results = DBQuerrier::defaultQuery($query);
    $result = array();
    while($row = @ mysqli_fetch_array($results)) {
        array_push($result, $row);
    }
    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);