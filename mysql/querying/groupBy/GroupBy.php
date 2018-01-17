<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/groupBy/IGroupBy.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/27/17
     * Time: 1:22 PM
     */
    class GroupBy implements IGroupBy {
        private $params;

        private function __construct() {
            $this->params = array();
        }

        /**
         * Creates the group by string for this group by.
         *
         * @return string the group by string.
         */
        public function generateGroupBy() {
            $html = "";
            for ($i = 0; $i < (sizeof($this->params) - 1); $i++) {
                $html .= $this->params[$i] . ", ";
            }
            $html .= $this->params[sizeof($this->params) - 1];
            return $html;
        }

        /**
         * Determines if there is a group by.
         *
         * @return bool is there a group by.
         */
        public function isGroupBy() {
            return (sizeof($this->params) > 0);
        }

        /**
         * Adds a parameter to this group by statement.
         *
         * @param $param string the parameter in the database to group by.
         * @return $this;
         */
        public function addParameter($param) {
            array_push($this->params, $param);
            return $this;
        }

        /**
         * Static constructor for convenience.
         *
         * @return IGroupBy
         */
        public static function groupBy() {
            return new GroupBy();
        }
    }