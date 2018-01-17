<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/AWhereCombiner.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:53 AM
     */
    class AndWhereCombiner extends AWhereCombiner {

        /**
         * Gets the expression to combine the wheres.
         *
         * @return string the sql expression to combine the wheres.
         */
        protected function getCombiner() {
            return "AND";
        }

        /**
         * Static constructor for convenience.
         *
         * @return AndWhereCombiner
         */
        public static function newCombiner() {
            return new AndWhereCombiner();
        }
    }