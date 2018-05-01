<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/2/18
     * Time: 2:59 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/notinsystem/ReviewDestinationChargerNotInSystem.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/notinsystem/ReviewSuperchargerNotInSystem.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/Table.php");
    $destinationChargers = ReviewDestinationChargerNotInSystem::getAllDestinationChargerReviewsNotInSystem();
    $superchargers = ReviewSuperchargerNotInSystem::getAllSuperchargerReviewsNotInSystem();
    $page = new DefaultPage("Reviews For Chargers Not In System");
    $dcTable = new Table();
    $dcTable->addTitle("Reviews From Destination Chargers Not In the System");
    $dcTable->addColumns("Name", "Email", "Location", "Address", "Link", "Rating", "Lng", "Lat", "Checked", "");
    foreach ($destinationChargers as $destinationCharger) {
        $dcTable->addRows(array($destinationCharger->getName(), $destinationCharger->getEmail(),
            $destinationCharger->getLocation(), $destinationCharger->getAddress(), $destinationCharger->getLink(),
            $destinationCharger->getRating(), $destinationCharger->getLng(), $destinationCharger->getLat(),
            $destinationCharger->isChecked(),
            "<a class='btn btn-primary' href='/checkReview.php?type=dc&id=" . $destinationCharger->getReviewId() .
            "'>Check</a>"));
    }
    $scTable = new Table();
    $scTable->addTitle("Reviews From Super Chargers Not In the System");
    $scTable->addColumns("Name", "Email", "Location", "Address", "Stalls", "Link", "Rating", "Lng", "Lat", "Status",
        "Open Date", "Checked", "");
    foreach ($superchargers as $supercharger) {
        $scTable->addRows(array($supercharger->getName(), $supercharger->getEmail(), $supercharger->getLocation(),
            $supercharger->getAddress(), $supercharger->getStalls(), $supercharger->getLink(),
            $supercharger->getRating(), $supercharger->getLng(), $supercharger->getLat(), $supercharger->getStatus(),
            $supercharger->getOpenDate(), $supercharger->isChecked(),
            "<a class='btn btn-primary' href='/checkReview.php?type=sc&id=" . $supercharger->getReviewId() . "'>Check</a>"));
    }
    $page->addToBody($dcTable->getHtml(10, 1), Page::BOTTOM);
    $page->addToBody($scTable->getHtml(10, 1), Page::BOTTOM);
    echo $page->generateHtml();

