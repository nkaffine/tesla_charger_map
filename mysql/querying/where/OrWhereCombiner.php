<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/AndWhereCombiner.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:53 AM
     */
    class OrWhereCombiner extends AWhereCombiner {

        /**
         * Gets the expression to combine the wheres.
         *
         * @return string the sql expression to combine the wheres.
         */
        protected function getCombiner() {
            return "OR";
        }

        /**
         * Static constructor for convenience.
         *
         * @return OrWhereCombiner
         */
        public static function newCombiner() {
            return new OrWhereCombiner();
        }
    }