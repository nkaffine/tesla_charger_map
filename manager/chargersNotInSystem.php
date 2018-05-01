<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/2/18
     * Time: 2:59 PM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/Table.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    $query =
        new SelectQuery("charger_not_in_system", "review_id", "name", "email", "charger_name", "address", "link",
            "rating", "lat", "lng", "review_date", "type");
    $query->where(Where::whereEqualValue("checked", DBValue::nonStringValue(0)));
    $chargers = DBQuerrier::defaultQuery($query);
    $page = new DefaultPage("Reviews For Chargers Not In System");
    $dcTable = new Table();
    $dcTable->addTitle("Reviews From Chargers Not In the System");
    $dcTable->addColumns("Name", "Email", "Charger Name", "Address", "Link", "Rating", "Lat", "Lng", "Type", "");
    foreach ($chargers as $charger) {
        if ($charger['type'] == 0) {
            $charger['type'] = "supercharger";
        } else {
            $charger['type'] = "destinationcharger";
        }
        $dcTable->addRows(array($charger['name'], $charger['email'],
            $charger['charger_name'], $charger['address'], $charger['link'],
            $charger['rating'], $charger['lat'], $charger['lng'], $charger['type'],
            "<a class='btn btn-primary' href='/manager/checkReview.php?id=" . $charger['review_id'] .
            "'>Check</a>"));
    }
    $scTable = new Table();
    $scTable->addTitle("Reviews From Super Chargers Not In the System");
    $scTable->addColumns("Name", "Email", "Location", "Address", "Stalls", "Link", "Rating", "Lng", "Lat", "Status",
        "Open Date", "Checked", "");
    $page->addToBody($dcTable->getHtml(10, 1), Page::BOTTOM);
    echo $page->generateHtml();

