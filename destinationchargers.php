<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/12/18
     * Time: 11:06 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/ChargerFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/model/chargers/SuperCharger.php");
    $array = array();
    $array['error'];
    $array['results'] = array();
    try {
        $destinationChargers = DestinationCharger::getAllDestinationChargers();
        foreach ($destinationChargers as $destinationCharger) {
            $jsonSuperCharger['name'] = $destinationCharger->getName();
            $jsonSuperCharger['lat'] = $destinationCharger->getLocation()->getLat();
            $jsonSuperCharger['lng'] = $destinationCharger->getLocation()->getLng();
            $jsonSuperCharger['address'] = $destinationCharger->getAddress();
            $jsonSuperCharger["type"] = "destinationcharger";
            $jsonSuperCharger['reviews'] = array();
            $jsonSuperCharger['rating'] = $destinationCharger->getAverageRating();
            $array['results'][$destinationCharger->getId()] = $jsonSuperCharger;
        }
        $destinationChargerReviews = ChargerFactory::getReviewsOfType("destinationcharger");
        foreach ($destinationChargerReviews as $destinationChargerReview) {
            $reviewJson = array();
            $reviewJson['link'] = $destinationChargerReview->getLink();
            $reviewJson['reviewer'] = $destinationChargerReview->getReviewer();
            $reviewJson['rating'] = $destinationChargerReview->getRating();
            $reviewJson['reviewDate'] = $destinationChargerReview->getReviewDate();
            $reviewJson['episode'] = $destinationChargerReview->getTtnEpisode();
            array_push($array['results'][$destinationChargerReview->getCharger()->getId()]['reviews'], $reviewJson);
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
