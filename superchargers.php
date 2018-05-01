<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Like.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/DBJoinTable.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/InnerJoin.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 10:15 AM
     */
    if (count($_GET)) {
        $cleanser = InputCleanserFactory::dataBaseFriendly();
        $where = null;
        if (isset($_GET["location"])) {
            $where = Like::newLike("name");
            $where->contains($cleanser->cleanse($_GET["location"]));
        }
        if (isset($_GET["address"])) {
            if ($where === null) {
                $where = Like::newLike("address");
                $where->contains($cleanser->cleanse($_GET["address"]));
            } else {
                $where1 = Like::newLike("address");
                $where1->contains($cleanser->cleanse($_GET["address"]));
                $where = OrWhereCombiner::newCombiner()->addWhere($where)->addWhere($where1);
            }
        }
        $joinTable1 = new DBJoinTable("super_charger", "charger_id");
        $joinTable2 = new DBJoinTable("charger", "charger_id");
        $joinTable1->addParams("charger_id", "status", "stalls", "open_date");
        $joinTable2->addParams("charger_id", "lat", "lng", "name", "region", "country", "street", "city", "state",
            "zip");
        $query = new InnerJoin($joinTable1, $joinTable2);
        if($where !== null) {
            $query->where($where);
        }
        $result = DBQuerrier::defaultQuery($query);
        $chargers = array();
        while ($row = @ mysqli_fetch_assoc($result)) {
            $row['type'] = "supercharger";
            array_push($chargers, $row);
        }
        header('Content-Type: application/json');
        echo json_encode($chargers, JSON_PRETTY_PRINT);

    } else {
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
    }
