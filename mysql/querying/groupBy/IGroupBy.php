<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/27/17
     * Time: 1:13 PM
     */
    interface IGroupBy {
        /**
         * Creates the group by string for this group by.
         *
         * @return string the group by string.
         */
        public function generateGroupBy();

        /**
         * Determines if there is a group by.
         *
         * @return bool is there a group by.
         */
        public function isGroupBy();

        /**
         * Adds a parameter to this group by statement.
         *
         * @param $param string the parameter in the database to group by.
         * @return $this
         */
        public function addParameter($param);

        /**
         * Static constructor for convenience.
         *
         * @return IGroupBy
         */
        public static function groupBy();
    }