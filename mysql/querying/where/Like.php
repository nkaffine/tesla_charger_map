<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/IWhere.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/26/17
     * Time: 5:21 PM
     */
    class Like implements IWhere {
        private $param;
        private $start;
        private $end;
        private $contains;

        private function __construct($param) {
            if ($param === null) {
                throw new InvalidArgumentException("Parameter cannot be null");
            }
            $this->param = $param;
        }

        /**
         * Generates the string where statement of this where.
         *
         * @return string the where statement.
         */
        public function generateWhere() {
            return $this->param . " LIKE '" . $this->generateLikeString() . "'";
        }

        private function generateLikeString() {
            $inner = "";
            if ($this->start !== null) {
                $inner .= $this->start . "%";
            }
            if ($this->contains !== null) {
                $inner .= "%" . $this->contains . "%";
            }
            if ($this->end !== null) {
                $inner .= "%" . $this->end;
            }
            $inner = str_split($inner, 1);
            $newInner = array();
            for ($i = 0; $i < (sizeof($inner) - 1); $i++) {
                if (!($inner[$i] === "%" && $inner[$i + 1] === "%")) {
                    array_push($newInner, $inner[$i]);
                }
            }
            array_push($newInner, $inner[sizeof($inner) - 1]);
            return implode("", $newInner);
        }

        /**
         * Determines if there is a where.
         *
         * @return boolean if there is a where statement.
         */
        public function isWhere() {
            return $this->param !== null && ($this->start !== null || $this->end !== null || $this->contains !== null);
        }

        /**
         * Sets the start with value of this where statement to the given value
         *
         * @param $value string the value.
         * @return $this
         */
        public function startsWith($value) {
            if ($value === null) {
                throw new InvalidArgumentException("Starts with value cannot be null");
            }
            $this->start = $value;
            return $this;
        }

        /**
         * Sets the end value of this where statement to the given value.
         *
         * @param $value string the value.
         * @return $this
         */
        public function endsWith($value) {
            if ($value === null) {
                throw new InvalidArgumentException("Ends with value cannot be null");
            }
            $this->end = $value;
            return $this;
        }

        /**
         * Sets the contains value of this where statement to the given value.
         *
         * @param $value string the value.
         * @return $this
         */
        public function contains($value) {
            if ($value === null) {
                throw new InvalidArgumentException("Contains with value cannot be null");
            }
            $this->contains = $value;
            return $this;
        }

        /**
         * Static constructor for convenience.
         *
         * @param $param string the parameter in the database.
         * @return Like
         */
        public static function newLike($param) {
            return new Like($param);
        }
    }