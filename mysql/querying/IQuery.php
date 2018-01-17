<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 10:46 AM
     */
    interface IQuery {
        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.n
         */
        public function generateQuery();
    }