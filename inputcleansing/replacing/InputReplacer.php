<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/17/18
     * Time: 6:47 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/replacing/IInputReplacer.php");

    class InputReplacer implements IInputReplacer {
        private $replaceMap;
        private $max_length;

        public function __construct($max_length) {
            if ($max_length === null) {
                throw new InvalidArgumentException("Max length must be a valid value");
            }
            $this->max_length = $max_length;
            $this->replaceMap = array();

        }

        /**
         * Cleanses the input based on the specifications of this cleanser.
         *
         * @param $input string the input being cleansed.
         * @return string the cleansed input.
         */
        public function cleanse($input) {
            $input = substr($input, 0, $this->max_length);
            $chars = str_split($input);
            $new = array();
            $changed = false;
            foreach ($chars as $char) {
                if ((@$temp = $this->replaceMap[$char]) !== null) {
                    array_push($new, $temp);
                    $changed = true;
                } else {
                    array_push($new, $char);
                }
            }
            if ($changed) {
                return implode("", $new);
            } else {
                return $input;
            }
        }

        /**
         * Adds a value to be replaced and a value to replace it with to the replacer.
         *
         * @param $search string the 1 char string to be search for.
         * @param $replace string the string to replace the 1 char string with.
         * @return IInputReplacer returns a reference to this remover for chaining convenience.
         */
        public function addReplacePair($search, $replace) {
            if (strlen($search) !== 1) {
                throw new InvalidArgumentException("The search must be a 1 character string");
            }
            $this->replaceMap[$search] = $replace;
            return $this;
        }
    }