<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 9:42 AM
     */
    class InsertRow {
        /**
         * @var DBValue[]
         */
        private $values;

        /**
         * InsertRow constructor;
         */
        public function __construct() {
            $this->values = array();
        }

        /**
         * Inserts the value into the row in the place of the given parameter.
         *
         * @param $param string the name of the parameter the value corresponds to.
         * @param $value DBValue the value being inserted.
         */
        public function addValue($param, $value) {
            if (@$this->values[$param] === null) {
                $this->values[$param] = $value;
            } else {
                throw new InvalidArgumentException("Parameter already has value");
            }
        }

        /**
         *
         * @param $parameters string[] a list of the parameters for the insert value.
         * @return string the value string.
         */
        public function getRowString($parameters) {
            $result = "(";
            for ($i = 0; $i < (sizeof($parameters) - 1); $i++) {
                $result .= $this->values[$parameters[$i]]->getValueString() . ", ";
            }
            $result .= $this->values[$parameters[sizeof($parameters) - 1]]->getValueString();
            return $result . ")";
        }
    }