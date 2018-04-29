<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/16/18
     * Time: 1:02 AM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/IInputRemover.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/IInputRemover.php");
    class CustomCleanserWrapper implements IInputRemover {
        /**
         * @var IInputRemover
         */
        protected $customCleanser;

        /**
         * Gets the pattern being used to cleanse the string.
         *
         * @return string the patter being used to cleanse the string.
         */
        public function getPatterns() {
            return $this->customCleanser->getPatterns();
        }

        /**
         * Cleanses the input based on the specifications of this cleanser.
         *
         * @param $input string the input being cleansed.
         * @return string the cleansed input.
         */
        public function cleanse($input) {
            return $this->customCleanser->cleanse($input);
        }

        /**
         * Gets max length of the cleansed string.
         *
         * @return int the max length of the cleansed string.
         */
        public function getMaxLength() {
            return $this->customCleanser->getMaxLength();
        }
    }