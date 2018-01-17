<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/order/IOrderCombiner.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 2:00 PM
     */
    class OrderCombiner implements IOrderCombiner {
        /**
         * @var IOrder[];
         */
        private $orders;

        private function __construct() {
            $this->orders = array();
        }

        /**
         * Generates the order string of all the orders.
         *
         * @return string
         */
        public function generateOrder() {
            $size = sizeof($this->orders);
            switch ($size) {
                case 0:
                    return "";
                    break;
                case 1:
                    return $this->orders[0]->generateOrder();
                    break;
                default:
                    $result = "";
                    for ($i = 0; $i < ($size - 1); $i++) {
                        $result .= $this->orders[$i]->generateOrder() . ", ";
                    }
                    return $result . $this->orders[$size - 1]->generateOrder();
                    break;
            }
        }

        /**
         * Adds an order statements to the list of order statements.
         *
         * @param $order IOrder the order being added to the list of IOrders
         * @return OrderCombiner;
         */
        public function addOrder($order) {
            array_push($this->orders, $order);
            return $this;
        }

        /**
         * Determines if there is an order.
         *
         * @return boolean
         */
        public function isOrder() {
            return sizeof($this->orders) > 0;
        }

        /**
         * Static constructor for convenience.
         *
         * @return OrderCombiner
         */
        public static function newCombiner() {
            return new OrderCombiner();
        }
    }