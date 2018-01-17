<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/order/IOrder.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 1:47 PM
     */
    interface IOrderStatement extends IOrder {
        /**
         * Gets an order that is ordered in descending.
         *
         * @param $parameter string the column in the database.
         * @return IOrder
         */
        public static function orderDesc($parameter);

        /**
         * Gets an order that is ordered in ascending.
         *
         * @param $parameter string the column in the database.
         * @return IOrder
         */
        public static function orderAsc($parameter);

        /**
         * Gets an order that ordered by the value with no direction specified.
         *
         * @param $parameter string the column in the database.
         * @return mixed
         */
        public static function orderNoDirection($parameter);

    }