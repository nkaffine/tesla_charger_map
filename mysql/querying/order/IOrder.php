<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 1:41 PM
     */
    interface IOrder {
        /**
         * Generates the order string for this IOrder.
         *
         * @return mixed
         */
        public function generateOrder();

        /**
         * Determines if there is an order.
         *
         * @return boolean
         */
        public function isOrder();
    }