<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/16/18
     * Time: 12:24 AM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/IInputRemover.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/IInputRemover.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/ACleanser.php");

//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/ACleanser.php");

    class CustomCleanser extends ACleanser {
        private $pattern;

        /**
         * CustomCleanser constructor.
         *
         * @param $cleanser IInputCleanser
         * @param $pattern string the expression for the pattern
         */
        public function __construct($cleanser, $pattern) {
            parent::__construct($cleanser);
            if ($pattern === null) {
                throw new InvalidArgumentException("Pattern cannot be null");
            }
            $this->pattern = str_replace("\/", "/", $pattern);
            $this->pattern = str_replace("/", "\/", $this->pattern);
        }

        /**
         * Gets the pattern being used to cleanse the string.
         *
         * @return string the patter being used to cleanse the string.
         */
        public function getPatterns() {
            return $this->innerCleanser->getPatterns() . $this->pattern;
        }
    }