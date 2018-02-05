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
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Like.php");
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
        $destinationChargers = DestinationCharger::getAllDestinationChargers($where);
        foreach ($destinationChargers as $destinationCharger) {
            $jsonSuperCharger['id'] = $destinationCharger->getId();
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
            if ($array['results'][$destinationChargerReview->getCharger()->getId()] !== null) {
                array_push($array['results'][$destinationChargerReview->getCharger()->getId()]['reviews'], $reviewJson);
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
