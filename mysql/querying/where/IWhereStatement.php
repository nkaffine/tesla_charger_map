<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/mysql/querying/where/IWhere.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:48 AM
     */
    interface IWhereStatement extends IWhere {

        /**
         * Creates an IWhere with the parameter set equal to the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that needs to be matched.
         * @return IWhere
         */
        public static function whereEqualValue($parameter, $value);

        /**
         * Creates an IWhere with the parameter set greater than the value
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value needs to be greater than.
         * @return IWhere
         */
        public static function whereGreaterThanValue($parameter, $value);

        /**
         * Creates an IWhere with the parameter set less than the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value needs to be less than.
         * @return IWhere
         */
        public static function whereLessThanValue($parameter, $value);

        /**
         * Creates an IWhere with the parameter set greater than or equal to the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value need to be greater than or equal to.
         * @return IWhere
         */
        public static function whereGreaterOrEqualValue($parameter, $value);

        /**
         * Creates an IWhere with the parameter set less than or equal to the value.
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value that the database value needs to be less than or equal to.
         * @return IWhere
         */
        public static function whereLessOrEqualValue($parameter, $value);

        /**
         * Creates an IWhere with the parameter set to null.
         *
         * @param $parameter string the column in the database being checked for null.
         * @return IWhere
         */
        public static function whereIsNull($parameter);

        /**
         * Creates an IWhere with the parameter set to not the given value
         *
         * @param $parameter string the column in the database.
         * @param $value DBValue the value the database value should not be.
         * @return IWhere
         */
        public static function whereIsNotValue($parameter, $value);
    }