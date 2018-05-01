<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/2/18
     * Time: 3:29 PM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/Table.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/InputCleanserFactory.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");

    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $cleanser = InputCleanserFactory::dataBaseFriendly();
    if (count($_GET)) {
        if (isset($_GET['id'])) {
            $id = $cleanser->cleanse($_GET["id"]);
            $query = new SelectQuery("charger_not_in_system", "name", "email", "charger_name", "address", "link",
                "rating", "lat", "lng");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($id)));
            $results = DBQuerrier::queryUniqueValue($query);
            $charger = @ mysqli_fetch_assoc($results);
            $table = new Table();
            $table->addColumns("Name", "Email", "Charger Name", "Address", "Link", "Rating", "Lat", "Lng");
            $table->addRows(array($charger['name'], $charger['email'], $charger['charger_name'],
                $charger['address'], $charger['link'], $charger['rating'],
                $charger['lat'], $charger['lng']));
            $page = new DefaultPage("Check Review");
            $page->addStyleSheet("main.css");
            $page->addJSFile("/view/checkReview.js");
            $page->addToBody("<div class='col-lg-10 col-lg-offset-1 no-pad' id='error'></div>" .
                "<div class='col-lg-10 col-lg-offset-1 form no-pad'>" .
                "<h1 class='col-lg-12 no-pad'>Search Chargers in the System</h1>" .
                "<div class='col-lg-5 no-pad'><label class='col-lg-12 no-pad' for='location'>Chargers Location Name</label>" .
                "<input class='col-lg-12 form-control' type='text' id='location'></div>" .
                "<div class='col-lg-5 no-pad'><label class='col-lg-12 no-pad' for='address'>Charger Address</label>" .
                "<input class='col-lg-12 form-control' type='text' id='address'></div><div class='col-lg-2'>" .
                "<label class='col-lg-12' style='color:white;'>I</label>" .
                "<button class='col-lg-12 btn btn-primary no-pad' id='searchCharger'>Search</button></div></div>" .
                "<div id='searchResults' class='col-lg-12 no-pad'></div>",
                Page::TOP);
            $page->addToBody($table->getHtml(10, 1), Page::TOP);
            echo $page->generateHtml();
        } else {
            throw new InvalidArgumentException("Required values were not supplied");
        }
    } else {
        throw new InvalidArgumentException("Required values were not supplied");
    }