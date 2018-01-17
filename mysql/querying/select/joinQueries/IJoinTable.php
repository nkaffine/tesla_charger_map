<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/29/17
     * Time: 8:53 AM
     */
    interface IJoinTable {
        /**
         * Gets the table for the join query.
         *
         * @return string the table used in the query.
         */
        public function getTable();

        /**
         * Gets the name of the table for join query.
         *
         * @return string the name of the table for the join query.
         */
        public function getTableName();

        /**
         * Gets teh keys for the join query.
         *
         * @return string the keys for the join query.
         */
        public function getKeys();

        /**
         * Gets the parameters for the join query for this table.
         *
         * @return string[] the parameters for the join query.
         */
        public function getParams();

        /**
         * Adds parameters to the join table for the join query.
         *
         * @param ...$params string[] the parameters for this join table.
         * @return void
         */
        public function addParams(...$params);
    }