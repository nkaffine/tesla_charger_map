<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/AggregateQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/26/17
     * Time: 12:50 PM
     */
    class MinQuery extends AggregateQuery {
        /**
         * Returns the name of the function for this query.
         *
         * @return string the name of the function for this query.
         */
        protected function mathFunction() {
            return "MIN";
        }
    }