<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 9:30 AM
     */
    class DBValue {
        private $value;
        private $type;

        const STRING = 0;
        const NOT_STRING = 1;

        /**
         * DBValue constructor.
         *
         * @param $value mixed the value being inserted into the database.
         * @param $type int the type of value being inserted.
         */
        private function __construct($value, $type) {
            if ($value === null || $type === null) {
                throw new InvalidArgumentException("Value and Type cannot be null");
            }
            $this->type = $type;
            $this->value = $value;
        }

        /**
         * Creates a new insert value with the given value that is a string.
         *
         * @param $value string the string value being inserted into the database.
         * @return DBValue
         */
        public static function stringValue($value) {
            return new DBValue($value, self::STRING);
        }

        /**
         * Creates a new insert value with the given value that is not a string.
         *
         * @param $value mixed the value being inserted into the database that is not a string.
         * @return DBValue
         */
        public static function nonStringValue($value) {
            return new DBValue($value, self::NOT_STRING);
        }

        public function getValueString() {
            switch ($this->type) {
                case self::NOT_STRING:
                    return $this->value;
                    break;
                case self::STRING:
                    return "'" . $this->value . "'";
                    break;
                default:
                    throw new InvalidArgumentException("Invalid value type");
                    break;
            }
        }
    }