<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/12/18
     * Time: 11:06 PM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/DBJoinTable.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/InnerJoin.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Like.php");

    $cleanser = InputCleanserFactory::dataBaseFriendly();
    $joinTable1 = new DBJoinTable("destination_charger", "charger_id");
    $joinTable2 = new DBJoinTable("charger", "charger_id");
    $joinTable1->addParams("charger_id");
    $joinTable2->addParams("lat", "lng", "charger_id", "name", "region", "street", "city", "state", "zip", "country", "address");
    $chargerQuery = new InnerJoin($joinTable1, $joinTable2);

    $DBTable1 = new DBJoinTable("destination_charger", "charger_id");
    $DBTable2 = new DBJoinTable("review", "charger_id");
    $DBTable3 = new DBJoinTable("charger", "charger_id");
    $DBTable1->addParams("charger_id");
    $DBTable2->addParams("link", "rating");
    $DBTable3->addParams("region", "state", "city", "country");
    $reviewQuery = new InnerJoin($DBTable1, $DBTable2, $DBTable3);
    if (count(@$_GET["region"])) {
        $where = Where::whereEqualValue("region", DBValue::stringValue($cleanser->cleanse($_GET['region'])));
        $chargerQuery->where($where);
        $reviewQuery->where($where);
    }
    if (count(@$_GET["country"])) {
        $where = Where::whereEqualValue("country", DBValue::stringValue($cleanser->cleanse($_GET['country'])));
        $chargerQuery->where($where);
        $reviewQuery->where($where);
    }
    if (count(@$_GET["state"])) {
        $where = Where::whereEqualValue("state", DBValue::stringValue($cleanser->cleanse($_GET['state'])));
        $chargerQuery->where($where);
        $reviewQuery->where($where);
    }
    if (count(@$_GET["city"])) {
        $where = Where::whereEqualValue("city", DBValue::stringValue($cleanser->cleanse($_GET["city"])));
        $chargerQuery->where($where);
        $reviewQuery->where($where);
    }
    if (count(@$_GET["address"])) {
        $where = Like::newLike("address");
        $where->contains($cleanser->cleanse($_GET["address"]));
        $chargerQuery->where($where);
        $reviewQuery->where($where);
    }
    if (count(@$_GET["location"])) {
        $where = Like::newLike("name");
        $where->contains($cleanser->cleanse($_GET["location"]));
        $chargerQuery->where($where);
        $reviewQuery->where($where);
    }
    $destinationChargers = array();
    $results = DBQuerrier::defaultQuery($chargerQuery);
    while ($row = @ mysqli_fetch_assoc($results)) {
        $row['type'] = 'destinationcharger';
        $destinationChargers[$row["charger_id"]] = $row;
    }
    $destinationChargerReviews = array();
    $results = DBQuerrier::defaultQuery($reviewQuery);
    while ($row = @ mysqli_fetch_assoc($results)) {
        if (@$destinationChargerReviews[$row["charger_id"]] === null) {
            $destinationChargerReviews[$row["charger_id"]] = array();
        }
        array_push($destinationChargerReviews[$row["charger_id"]], $row);
    }
    foreach ($destinationChargerReviews as $chargerReviews) {
        $total = 0;
        $num = 0;
        foreach ($chargerReviews as $review) {
            $total += $review['rating'];
            $num += 1;
        }
        $destinationChargers[$chargerReviews[0]["charger_id"]]["reviews"] = $chargerReviews;
        $destinationChargers[$chargerReviews[0]["charger_id"]]["average"] = $total / $num;
    }
    $array = array();
    foreach ($destinationChargers as $destinationCharger) {
        if (@$destinationCharger["region"] !== null) {
            if (@$destinationCharger["country"] !== null) {
                if (@$destinationCharger["state"] != null) {
                    if (@$array[$destinationCharger["region"]][$destinationCharger["country"]][$destinationCharger["state"]] ===
                        null
                    ) {
                        $array[$destinationCharger["region"]][$destinationCharger["country"]][$destinationCharger["state"]] =
                            array();
                    }
                    array_push($array[$destinationCharger["region"]][$destinationCharger["country"]][$destinationCharger["state"]],
                        $destinationCharger);
                } else {
                    if (@$destinationCharger["city"] != null) {
                        if (@$array[$destinationCharger["region"]][$destinationCharger["country"]][$destinationCharger["city"]] ===
                            null
                        ) {
                            $array[$destinationCharger["region"]][$destinationCharger["country"]][$destinationCharger["city"]] =
                                array();
                        }
                        array_push($array[$destinationCharger["region"]][$destinationCharger["country"]][$destinationCharger["city"]],
                            $destinationCharger);
                    } else {
                        if (@$array[$destinationCharger["region"]][$destinationCharger["country"]]["stateless"] ===
                            null
                        ) {
                            $array[$destinationCharger["region"]][$destinationCharger["country"]]["stateless"] =
                                array();
                        }
                        array_push($array[$destinationCharger["region"]][$destinationCharger["country"]]["stateless"],
                            $destinationCharger);
                    }
                }
            } else {
                if (@$array[$destinationCharger["region"]]["countryless"] === null) {
                    $array[$destinationCharger["region"]]["countryless"] = array();
                }
                array_push($array[$destinationCharger["region"]]["countryless"], $destinationCharger);
            }
        } else {
            if (@$array["regionless"] === null) {
                $array["regionless"] = array();
            }
            array_push($array["regionless"], $destinationCharger);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($destinationChargers, JSON_PRETTY_PRINT);
