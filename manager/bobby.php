<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/mysql/querying/where/OrWhereCombiner.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/Table.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");


    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/5/18
     * Time: 3:59 PM
     */
    $page = new DefaultPage("TTN Supercharger Review Manager");
//    $query =
//        new SelectQuery("review", "review_id", "link", "reviewer", "email", "rating", "review_date", "charger_id");
//    $query->where(Where::whereIsNull("ttn_id"));
    $query = new CustomQuery("SELECT review_id, link, reviewer, email, rating, review_date, charger_id, lat, lng, address FROM review INNER JOIN charger USING (charger_id) WHERE review.ttn_id IS NULL");
    $results = DBQuerrier::defaultQuery($query);
    $table = new Table();
    $reviews = array();
    $rows = array();
    while ($row = @ mysqli_fetch_assoc($results)) {
        array_push($reviews, $row);
        array_push($rows, array("<a href='{$row['link']}'>Link</a>", $row['reviewer'], $row['email'], $row['rating'],
            "<a target='_blank' href='https://maps.google.com/?q={$row['address']}'>Maps</a>",
            $row["review_date"],
            "<form action='/manager/addToTTN.php' method='post'><input type='hidden' name='rid' value='{$row['review_id']}'>"
            . "<input type='number' min='0' style='1' name='ttn_id' class='form-control' required>"
            . "<input type='submit' value='Submit' class='btn btn-primary form-control'></form>"));
    }
    $table->addColumns("Youtube Link", "Reviewer", "Reviewer Email", "Rating", "Google Maps", "Date", "TTN Number");
    $table->addRowsArray($rows);
    $page->addToBody("<div class='col-lg-8 col-lg-offset-2'><h1>Add Tesla Time News</h1><form class='form-inline' action='/addTTN.php' method='post'>" .
        "<div class='form-group'><label for='ttn_id'>Tesla Time News Number:</label>&nbsp;" .
        "<input type='number' min='0' step='1' name='ttn_id' class='form-control' required>&nbsp;</div>" .
        "<div class='form-group'><label for='link'>Link:</label>&nbsp;" .
        "<input class='form-control' type='text' name='link' required></div>" .
        "<input type='submit' value='Submit' class='btn btn-primary form-control'></form><a href='/manager/brent.php' class='btn btn-primary'>Accidentally assigned a review to an episode of TTN?</a></div>", Page::BOTTOM);
    $page->addToBody($table->getHtml(8, 2), Page::BOTTOM);
    $query = new SelectQuery("ttn", "ttn_id", "link");
    $result = DBQuerrier::defaultQuery($query);
    $table = new Table();
    $table->addColumns("TTN", "Link");
    while ($row = @ mysqli_fetch_array($result)) {
        $table->addRows(array("TTN " . $row['ttn_id'], "<a href='" . $row['link'] . "'>Link</a>"));
    }
    $page->addToBody($table->getHtml(8, 2), Page::TOP);
    $page->addToBody("<h1 style='margin-bottom:0;' class='col-lg-8 col-lg-offset-2'>Tesla Time News Episodes</h1>",
        Page::TOP);
    echo $page->generateHtml();