<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/3/18
     * Time: 1:30 PM
     */
    class CustomerQuery implements IQuery {
        private $query;

        public function __construct($query) {
            if ($query === null) {
                throw new InvalidArgumentException("Query is null");
            }
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