<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/ChargerFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/model/chargers/SuperCharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Like.php");
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 10:15 AM
     */

    /**
     * Cleans the string by escaping and removing special characters.
     *
     * @param $uncleanString string
     * @param $maxLength int
     * @return string
     */
    function validInputSizeAlpha($uncleanString, $maxLength) {
        $sizedString = substr($uncleanString, 0, $maxLength);
        $cleanString = preg_replace("[^\.a-zA-Z' ]", '', $sizedString);
        $cleanString = str_replace("'", "\'", $cleanString);
        return ($cleanString);
    }

    $array = array();
    $array['error'];
    $array['results'] = array();
    try {
        $where = null;
        if (isset($_GET["location"])) {
            $where = Like::newLike("name");
            $where->contains(validInputSizeAlpha($_GET["location"], 255));
        }
        if (isset($_GET["address"])) {
            if ($where === null) {
                $where = Like::newLike("address");
                $where->contains(validInputSizeAlpha($_GET["address"], 255));
            } else {
                $where1 = Like::newLike("address");
                $where1->contains(validInputSizeAlpha($_GET["address"], 255));
                $where = OrWhereCombiner::newCombiner()->addWhere($where)->addWhere($where1);
            }
        }
        $superChargers = SuperCharger::getAllSuperChargers($where);
        foreach ($superChargers as $superCharger) {
            $jsonSuperCharger['id'] = $superCharger->getId();
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
            if ($array['results'][$superChargerReview->getCharger()->getId()] !== null) {
                array_push($array['results'][$superChargerReview->getCharger()->getId()]['reviews'], $reviewJson);
            }
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