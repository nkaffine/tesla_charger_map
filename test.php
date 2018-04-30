<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 4/29/18
     * Time: 4:52 PM
     */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBConnector.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    $q =
        new CustomQuery("CALL newReview(10326, 'https://www.youtube.com/watch?v=_zCDvOsdL9Q', 'Nick Kaffine', '411rockstar@gmail.com', 7)");
    $result = DBQuerrier::defaultQuery($q);
    //    $result = mysqli_query(DBConnector::getConnector(), $q->generateQuery());
    //    $row = @ mysqli_fetch_array($result);
    //    var_dump($row);
    var_dump($result);