<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 10:15 AM
     */
    $superChargers = array();
    $query = new CustomQuery(fgets(fopen($_SERVER["DOCUMENT_ROOT"] . "/sqlProcedures/getSuperchargers.sql", 'r')));
    $results = DBQuerrier::defaultQuery($query);
    while ($row = @ mysqli_fetch_assoc($results)) {
        $row['type'] = "supercharger";
        $superChargers[$row["charger_id"]] = $row;
    }
    $query =
        new CustomQuery(fgets(fopen($_SERVER['DOCUMENT_ROOT'] . '/sqlProcedures/getSuperchargerReviews.sql', 'r')));
    $superChargerReviews = array();
    $results = DBQuerrier::defaultQuery($query);
    while ($row = @ mysqli_fetch_assoc($results)) {
        if (@$superChargerReviews[$row["charger_id"]] === null) {
            $superChargerReviews[$row["charger_id"]] = array();
        }
        array_push($superChargerReviews[$row["charger_id"]], $row);
    }
    foreach ($superChargerReviews as $chargerReviews) {
        $total = 0;
        $num = 0;
        foreach ($chargerReviews as $review) {
            $total += $review["rating"];
            $num += 1;
        }
        $superChargers[$chargerReviews[0]["charger_id"]]["reviews"] = $chargerReviews;
        $superChargers[$chargerReviews[0]["charger_id"]]["average"] = $total / $num;
    }
//    $array = array();
//    foreach ($superChargers as $superCharger) {
//        if (@$superCharger["region"] !== null) {
//            if (@$superCharger["country"] !== null) {
//                if (@$superCharger["state"] != null) {
//                    if (@$array[$superCharger["region"]][$superCharger["country"]][$superCharger["state"]] === null) {
//                        $array[$superCharger["region"]][$superCharger["country"]][$superCharger["state"]] = array();
//                    }
//                    array_push($array[$superCharger["region"]][$superCharger["country"]][$superCharger["state"]],
//                        $superCharger);
//                } else {
//                    if (@$superCharger["city"] != null) {
//                        if (@$array[$superCharger["region"]][$superCharger["country"]][$superCharger["city"]] ===
//                            null
//                        ) {
//                            $array[$superCharger["region"]][$superCharger["country"]][$superCharger["city"]] = array();
//                        }
//                        array_push($array[$superCharger["region"]][$superCharger["country"]][$superCharger["city"]],
//                            $superCharger);
//                    } else {
//                        if (@$array[$superCharger["region"]][$superCharger["country"]]["stateless"] === null) {
//                            $array[$superCharger["region"]][$superCharger["country"]]["stateless"] = array();
//                        }
//                        array_push($array[$superCharger["region"]][$superCharger["country"]]["stateless"],
//                            $superCharger);
//                    }
//                }
//            } else {
//                if (@$array[$superCharger["region"]]["countryless"] === null) {
//                    $array[$superCharger["region"]]["countryless"] = array();
//                }
//                array_push($array[$superCharger["region"]]["countryless"], $superCharger);
//            }
//        } else {
//            if (@$array["regionless"] === null) {
//                $array["regionless"] = array();
//            }
//            array_push($array["regionless"], $superCharger);
//        }
//    }
    header('Content-Type: application/json');
    echo json_encode($superChargers, JSON_PRETTY_PRINT);