<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/17/18
     * Time: 6:46 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/IInputCleanser.php");

    interface IInputReplacer extends IInputCleanser {
        /**
         * Adds a value to be replaced and a value to replace it with to the replacer.
         *
         * @param $search string the 1 char string to be search for.
         * @param $replace string the string to replace the 1 char string with.
         * @return IInputReplacer returns a reference to this remover for chaining convenience.
         */
        public function addReplacePair($search, $replace);
    }