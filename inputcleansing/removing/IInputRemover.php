<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/17/18
     * Time: 6:43 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/IInputCleanser.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/IInputCleanser.php");
    interface IInputRemover extends IInputCleanser {
        /**
         * Gets the pattern being used to cleanse the string.
         *
         * @return string the patter being used to cleanse the string.
         */
        public function getPatterns();

        /**
         * Gets max length of the cleansed string.
         *
         * @return int the max length of the cleansed string.
         */
        public function getMaxLength();
    }