<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IQuery.php");
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 4/18/18
     * Time: 5:37 PM
     */
    class CustomQuery implements IQuery {
        private $query;

        public function __construct($query) {
            $this->query = $query;
        }

        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.n
         */
        public function generateQuery() {
            return $this->query;
        }
    }