<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/26/17
     * Time: 10:20 AM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLConnectionException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLDeleteFailException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLInsertFailException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLNonUniqueValueException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLNoSuchValueException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLQueryFailException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLUpdateFailedException.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SQLValuesMatchedButNotUpdatedException.php");