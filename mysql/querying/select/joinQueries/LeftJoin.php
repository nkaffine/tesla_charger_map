<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/AJoinQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/29/17
     * Time: 11:09 AM
     */
    class LeftJoin extends AJoinQuery {

        /**
         * LeftJoin constructor.
         *
         * @param ...$joinTables IJoinTable[]
         */
        public function __construct(...$joinTables) {
            parent::__construct($joinTables);
        }

        /**
         * Gets the join command for this join query.
         *
         * @return string the join command for this query.
         */
        protected function getJoinCommand() {
            return "LEFT JOIN";
        }
    }