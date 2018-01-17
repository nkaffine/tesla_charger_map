<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/AggregateQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/26/17
     * Time: 1:19 PM
     */
    abstract class NonDistinctAggregateQuery extends AggregateQuery {
        /**
         * Returns the name of the function for this query.
         *
         * @return string the name of the function for this query.
         */
        public function distinct() {
            throw new InvalidArgumentException("Distinct is not compatible with this function");
        }
    }