<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:04 AM
     */
    interface IWhere {
        /**
         * Generates the string where statement of this where.
         *
         * @return string the where statement.
         */
        public function generateWhere();

        /**
         * Determines if there is a where.
         *
         * @return boolean if there is a where statement.
         */
        public function isWhere();
    }