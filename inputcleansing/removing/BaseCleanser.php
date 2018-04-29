<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 11:41 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/IInputRemover.php");

    class BaseCleanser implements IInputRemover {
        private $maxLength;

        public function __construct($maxLength) {
            if ($maxLength === null || $maxLength < 0) {
                throw new InvalidArgumentException("Invalid max length");
            }
            $this->maxLength = $maxLength;
        }

        /**
         * Cleanses the input based on the specifications of this cleanser.
         *
         * @param $input string the input being cleansed.
         * @return string the cleansed input.
         */
        public function cleanse($input) {
            return substr($input, 0, $this->getMaxLength());
        }

        /**
         * Gets the pattern being used to cleanse the string.
         *
         * @return string the patter being used to cleanse the string.
         */
        public function getPatterns() {
            return "";
        }

        /**
         * Gets max length of the cleansed string.
         *
         * @return int the max length of the cleansed string.
         */
        public function getMaxLength() {
            return $this->maxLength;
        }
    }