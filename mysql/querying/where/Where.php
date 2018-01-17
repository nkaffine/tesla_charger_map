<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/IWhereStatement.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:20 AM
     */
    class Where implements IWhereStatement {
        private $parameter;
        private $operator;
        private $value;

        /**
         * Where constructor.
         *
         * @param $param string the column in the database.
         * @param $operator string the operator for the sql where statement.
         * @param $value DBValue the value being checked against.
         */
        private function __construct($param, $operator, $value) {
            if ($param == null || $operator == null || $value == null) {
                throw new InvalidArgumentException("No values can be null");
            }
            $this->parameter = $param;
            $this->operator = $operator;
            $this->value = $value;
        }

        /**
         * Creates an IWhere with the parameter set equal to the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that needs to be matched.
         * @return IWhere
         */
        public static function whereEqualValue($parameter, $value) {
            return new Where($parameter, "=", $value);
        }

        /**
         * Creates an IWhere with the parameter set greater than the value
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value needs to be greater than.
         * @return IWhere
         */
        public static function whereGreaterThanValue($parameter, $value) {
            return new Where($parameter, ">", $value);
        }

        /**
         * Creates an IWhere with the parameter set less than the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value needs to be less than.
         * @return IWhere
         */
        public static function whereLessThanValue($parameter, $value) {
            return new Where($parameter, "<", $value);
        }

        /**
         * Creates an IWhere with the parameter set greater than or equal to the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value need to be greater than or equal to.
         * @return IWhere
         */
        public static function whereGreaterOrEqualValue($parameter, $value) {
            return new Where($parameter, ">=", $value);
        }

        /**
         * Creates an IWhere with the parameter set less than or equal to the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value needs to be less than or equal to.
         * @return IWhere
         */
        public static function whereLessOrEqualValue($parameter, $value) {
            return new Where($parameter, "<=", $value);
        }

        /**
         * Creates an IWhere with the parameter set to null.
         *
         * @param $parameter string the column in the database being checked for null.
         * @return IWhere
         */
        public static function whereIsNull($parameter) {
            return new Where($parameter, "is", DBValue::nonStringValue("null"));
        }

        /**
         * Creates an IWhere with the parameter set to not the given value
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value the database value should not be.
         * @return IWhere
         */
        public static function whereIsNotValue($parameter, $value) {
            return new Where($parameter, "!=", $value);
        }

        /**
         * Generates the string where statement of this where.
         *
         * @return string the where statement.
         */
        public function generateWhere() {
            return $this->parameter . " " . $this->operator . " " . $this->value->getValueString();
        }

        /**
         * Determines if there is a where statement in this IWhere.
         *
         * @return boolean
         */
        public function isWhere() {
            return true;
        }
    }