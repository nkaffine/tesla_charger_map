<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/ChargerFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/model/chargers/SuperCharger.php");
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 10:15 AM
     */
    $array = array();
    $array['error'];
    $array['results'] = array();
    try {
        $superChargers = SuperCharger::getAllSuperChargers();
        foreach ($superChargers as $superCharger) {
            $jsonSuperCharger['name'] = $superCharger->getName();
            $jsonSuperCharger['lat'] = $superCharger->getLocation()->getLat();
            $jsonSuperCharger['lng'] = $superCharger->getLocation()->getLng();
            $jsonSuperCharger['address'] = $superCharger->getAddress();
            $jsonSuperCharger['status'] = $superCharger->getStatus();
            $jsonSuperCharger['open_date'] = $superCharger->getOpenDate();
            $jsonSuperCharger['stalls'] = $superCharger->getNumberOfStalls();
            $jsonSuperCharger["type"] = "supercharger";
            $jsonSuperCharger['reviews'] = array();
            $jsonSuperCharger['rating'] = $superCharger->getAverageRating();
            $array['results'][$superCharger->getId()] = $jsonSuperCharger;
        }
        $superChargerReviews = ChargerFactory::getReviewsOfType("supercharger");
        foreach ($superChargerReviews as $superChargerReview) {
            $reviewJson = array();
            $reviewJson['link'] = $superChargerReview->getLink();
            $reviewJson['reviewer'] = $superChargerReview->getReviewer();
            $reviewJson['rating'] = $superChargerReview->getRating();
            $reviewJson['reviewDate'] = $superChargerReview->getReviewDate();
            $reviewJson['episode'] = $superChargerReview->getTtnEpisode();
            array_push($array['results'][$superChargerReview->getCharger()->getId()]['reviews'], $reviewJson);
        }
        $array['results'] = array_values($array['results']);
    } catch (Exception $exception) {
        if ($array['error'] === null) {
            $array['error'] = $exception->getMessage();
        } else {
            $array['error'] = $array['error'] . " " . $exception->getMessage();
        }
    }
    header('Content-Type: application/json');
    echo json_encode($array, JSON_PRETTY_PRINT);