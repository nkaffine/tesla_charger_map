<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/order/IOrder.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 1:47 PM
     */
    interface IOrderCombiner extends IOrder {
        /**
         * Adds an order statements to the list of order statements.
         *
         * @param $order IOrder the order being added to the list of IOrders
         * @return void
         */
        public function addOrder($order);
    }