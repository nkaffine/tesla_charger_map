<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/IWhere.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/26/17
     * Time: 5:01 PM
     */
    class NotWhere implements IWhere {
        private $where;

        /**
         * NotWhere constructor.
         *
         * @param $where IWhere the inner where that will be reversed.
         */
        private function __construct($where) {
            if ($where === null) {
                throw new InvalidArgumentException("Where must not be null");
            }
            $this->where = $where;
        }


        /**
         * Generates the string where statement of this where.
         *
         * @return string the where statement.
         */
        public function generateWhere() {
            return "NOT (" . $this->where->generateWhere() . ")";
        }

        /**
         * Determines if there is a where.
         *
         * @return boolean if there is a where statement.
         */
        public function isWhere() {
            return true;
        }

        /**
         * Static constructor for the not where for convenience.
         *
         * @param $where IWhere the where that will be reversed.
         * @return NotWhere
         */
        public static function newWhere($where) {
            return new NotWhere($where);
        }
    }