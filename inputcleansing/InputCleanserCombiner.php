<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/19/18
     * Time: 5:35 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/IInputCleanser.php");

    class InputCleanserCombiner implements IInputCleanser {
        /**
         * @var IInputCleanser[]
         */
        private $cleansers;

        /**
         * InputCleanserCombiner constructor.
         *
         * @param $cleansers IInputCleanser[] the input cleansers being combined.
         */
        public function __construct(...$cleansers) {
            if ($cleansers === null) {
                throw new InvalidArgumentException("Cleansers must not be null");
            } else if (is_array($cleansers[0])) {
                $this->verifyCleansers($cleansers[0]);
            } else {
                $this->verifyCleansers($cleansers);
            }
        }

        /**
         * Verifies if the given array of cleansers contains all non-null cleansers
         *
         * @param $cleansers IInputCleanser[] the array of cleansers.
         */
        private function verifyCleansers($cleansers) {
            foreach ($cleansers as $cleanser) {
                if ($cleanser === null) {
                    throw new InvalidArgumentException("All cleansers must not be null");
                }
            }
            $this->cleansers = $cleansers;
        }

        /**
         * Cleanses the input based on the specifications of this cleanser.
         *
         * @param $input string the input being cleansed.
         * @return string the cleansed input.
         */
        public function cleanse($input) {
            foreach ($this->cleansers as $cleanser) {
                $input = $cleanser->cleanse($input);
            }
            return $input;
        }
    }