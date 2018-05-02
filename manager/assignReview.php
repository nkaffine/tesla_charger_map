<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/3/18
     * Time: 3:31 PM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertQuery.php");

    $cleanser = InputCleanserFactory::dataBaseFriendly();
    $array['success'] = null;
    $array['error'] = null;
    try {
        if (count($_GET)) {
            if (isset($_GET["charger_id"]) && isset($_GET["type"]) && isset($_GET["id"])) {
                $query = new SelectQuery("charger_not_in_system", "name", "email", "link", "rating", "review_date");
                $query->where(Where::whereEqualValue("review_id",
                    DBValue::nonStringValue($cleanser->cleanse($_GET["id"]))));
                $results = DBQuerrier::queryUniqueValue($query);
                $row = @ mysqli_fetch_array($results);
                $query = new InsertQuery("review");
                $query->addParamAndValues("reviewer", DBValue::stringValue($row['name']));
                $query->addParamAndValues("email", DBValue::stringValue($row["email"]));
                $query->addParamAndValues("link", DBValue::stringValue($row['link']));
                $query->addParamAndValues("rating", DBValue::nonStringValue($row['rating']));
                $query->addParamAndValues("review_date", DBValue::stringValue($row['review_date']));
                $query->addParamAndValues("charger_id",
                    DBValue::nonStringValue($cleanser->cleanse($_GET["charger_id"])));
                DBQuerrier::defaultInsert($query);
                $array['success'] = "The Review Was Successfully Submitted";
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