<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/order/IOrderStatement.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 1:29 PM
     */
    class OrderStatement implements IOrderStatement {
        private $parameter;
        private $direction;
        const DESCENDING = 0;
        const ASCENDING = 1;
        const NO_DIRECTION = 2;

        /**
         * OrderStatement constructor.
         *
         * @param $parameter string the column in the database.
         * @param $direction int the direction of the sorting.
         */
        private function __construct($parameter, $direction) {
            if ($parameter === null || $direction === null) {
                return new InvalidArgumentException("Parameter and Direction cannot be null");
            }
            $this->parameter = $parameter;
            $this->direction = $direction;
        }

        /**
         * Generates the order string for this order statement.
         *
         * @return string the order string for this order statement.
         */
        public function generateOrder() {
            return $this->parameter . $this->directionToString();
        }

        /**
         * Gets an order that is ordered in descending.
         *
         * @param $parameter string the column in the database.
         * @return IOrder
         */
        public static function orderDesc($parameter) {
            return new OrderStatement($parameter, self::DESCENDING);
        }

        /**
         * Gets an order that is ordered in ascending.
         *
         * @param $parameter string the column in the database.
         * @return IOrder
         */
        public static function orderAsc($parameter) {
            return new OrderStatement($parameter, self::ASCENDING);
        }

        /**
         * Gets an order that ordered by the value with no direction specified.
         *
         * @param $parameter string the column in the database.
         * @return IOrder
         */
        public static function orderNoDirection($parameter) {
            return new OrderStatement($parameter, self::NO_DIRECTION);
        }

        private function directionToString() {
            switch ($this->direction) {
                case 0:
                    return " DESC";
                    break;
                case 1:
                    return " ASC";
                    break;
                default:
                    return "";
                    break;
            }
        }

        /**
         * Determines if there is an order.
         *
         * @return boolean
         */
        public function isOrder() {
            return true;
        }
    }