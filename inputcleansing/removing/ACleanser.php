<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 11:25 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/IInputRemover.php");

    abstract class ACleanser implements IInputRemover {
        /**
         * @var IInputRemover
         */
        protected $innerCleanser;

        /**
         * ACleanser constructor.
         *
         * @param $cleanser IInputCleanser the inner input cleanser.
         */
        protected function __construct($cleanser) {
            if ($cleanser === null) {
                throw new InvalidArgumentException("Invalid max length or pattern");
            }
            $this->innerCleanser = $cleanser;
        }

        /**
         * Cleanses the input based on the specifications of this cleanser.
         *
         * @param $input string the input being cleansed.
         * @return string the cleansed input.
         */
        public function cleanse($input) {
            $sizedString = substr($input, 0, $this->getMaxLength());
            return preg_replace("/[^" . $this->getPatterns() . "]/", "", $sizedString);
        }

        /**
         * Gets max length of the cleansed string.
         *
         * @return int the max length of the cleansed string.
         */
        public function getMaxLength() {
            return $this->innerCleanser->getMaxLength();
        }
    }