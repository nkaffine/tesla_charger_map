<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 4/30/18
     * Time: 12:20 PM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/groupBy/GroupBy.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/InnerJoin.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/DBJoinTable.php");
    $joinTable1 = new DBJoinTable("destination_charger", "charger_id");
    $joinTable1->addParams("charger_id");
    $joinTable2 = new DBJoinTable("charger", "charger_id");
    $joinTable2->addParams("country", "charger_id");
    $query = new InnerJoin($joinTable1, $joinTable2);
    $query->groupBy(GroupBy::groupBy()->addParameter("country"));
    $result = DBQuerrier::defaultQuery($query);
    $array = array();
    while ($row = @ mysqli_fetch_assoc($result)) {
        array_push($array, $row["country"]);
    }
    header('Content-Type: application/json');
    echo json_encode($array, JSON_PRETTY_PRINT);
