<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 5/1/18
     * Time: 11:43 AM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");
    $connection = mysqli_connect("superchargers.projectcoups.dreamhosters.com", "scharger_user", "NkWonJldEmXbDagY",
        "superchargers");
    $ttnQuery = "SELECT ttn_id, link FROM ttn";
    $ttnResults = mysqli_query($connection, $ttnQuery);
    $ttns = array();
    while ($row = @ mysqli_fetch_assoc($ttnResults)) {
        array_push($ttns, $row);
    }
    foreach ($ttns as $ttn) {
        $query = new InsertQuery("ttn");
        $query->addParamAndValues("ttn_id", DBValue::nonStringValue($ttn["ttn_id"]));
        $query->addParamAndValues("link", DBValue::stringValue($ttn["link"]));
        DBQuerrier::defaultInsert($query);
    }
    $cleanser = InputCleanserFactory::dataBaseFriendly();
    $destinationChargerReviewsQuery =
        "SELECT tesla_id, link, reviewer, email, rating, review_date, ttn_id FROM reviews INNER JOIN destination_chargers USING (charger_id)";
    $superChargerReviewsQuery =
        "SELECT sc_info_id, link, reviewer, email, rating, review_date, ttn_id FROM reviews INNER JOIN super_chargers USING (charger_id)";
    $destinationChargerReviewsResults = mysqli_query($connection, $destinationChargerReviewsQuery);
    $destinationChargerReviews = array();
    while ($row = @ mysqli_fetch_assoc($destinationChargerReviewsResults)) {
        array_push($destinationChargerReviews, $row);
    }
    $superChargerReviewsResults = mysqli_query($connection, $superChargerReviewsQuery);
    $superChargerReviews = array();
    while ($row = @ mysqli_fetch_assoc($superChargerReviewsResults)) {
        array_push($superChargerReviews, $row);
    }
    foreach ($destinationChargerReviews as $destinationChargerReview) {
        $query = new SelectQuery("destination_charger", "charger_id");
        $query->where(Where::whereEqualValue("tesla_id",
            DBValue::stringValue($destinationChargerReview["tesla_id"])));
        $results = DBQuerrier::queryUniqueValue($query);
        $row = @ mysqli_fetch_array($results);
        $id = $row['charger_id'];
        $query = new InsertQuery("review");
        $query->addParamAndValues("charger_id", DBValue::nonStringValue($id));
        $query->addParamAndValues("link", DBValue::stringValue($destinationChargerReview['link']));
        $query->addParamAndValues("reviewer",
            DBValue::stringValue($cleanser->cleanse($destinationChargerReview['reviewer'])));
        $query->addParamAndValues("email", DBValue::stringValue($destinationChargerReview['email']));
        $query->addParamAndValues("rating", DBValue::nonStringValue($destinationChargerReview['rating']));
        $query->addParamAndValues("review_date", DBValue::stringValue($destinationChargerReview['review_date']));
        if ($destinationChargerReview['ttn_id'] !== null) {
            $query->addParamAndValues("ttn_id", DBValue::nonStringValue($destinationChargerReview['ttn_id']));
        }
        DBQuerrier::defaultInsert($query);
    }
    $fails = array();
    foreach ($superChargerReviews as $superChargerReview) {
        $query = new SelectQuery("super_charger", "charger_id");
        $query->where(Where::whereEqualValue("sc_info_id", DBValue::nonStringValue($superChargerReview['sc_info_id'])));
        try {
            $results = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($results);
            $id = $row['charger_id'];
            $query = new InsertQuery("review");
            $query->addParamAndValues("charger_id", DBValue::nonStringValue($id));
            $query->addParamAndValues("link", DBValue::stringValue($superChargerReview['link']));
            $query->addParamAndValues("reviewer",
                DBValue::stringValue($cleanser->cleanse($superChargerReview['reviewer'])));
            $query->addParamAndValues("email", DBValue::stringValue($superChargerReview['email']));
            if ($superChargerReview['rating'] !== null) {
                $query->addParamAndValues("rating", DBValue::nonStringValue($superChargerReview['rating']));
            }
            $query->addParamAndValues("review_date", DBValue::stringValue($superChargerReview['review_date']));
            if ($superChargerReview['ttn_id'] !== null) {
                $query->addParamAndValues("ttn_id", DBValue::nonStringValue($superChargerReview['ttn_id']));
            }
            DBQuerrier::defaultInsert($query);
        } catch (SQLNonUniqueValueException $exception) {
            array_push($fails, $superChargerReview);
        } catch (SQLNoSuchValueException $exception) {
            array_push($fails, $superChargerReview);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($fails, JSON_PRETTY_PRINT);

