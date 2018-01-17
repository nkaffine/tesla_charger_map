<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/IJoinTable.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/29/17
     * Time: 8:58 AM
     */
    class DBJoinTable implements IJoinTable {
        private $table;
        private $keys;
        private $params;

        /**
         * DBJoinTable constructor.
         *
         * @param $table string the name of the table for the join
         * @param ...$keys string[] the keys for the join
         */
        public function __construct($table, ...$keys) {
            if ($table === null || $keys === null) {
                throw new InvalidArgumentException("Table and keys must not be null");
            }
            if (is_array($keys[0])) {
                $keys = $keys[0];
            }
            $this->table = $table;
            $this->keys = $keys;
        }

        /**
         * Gets the table for the join query.
         *
         * @return string the table used in the query.
         */
        public function getTable() {
            return $this->table;
        }

        /**
         * Gets the name of the table for join query.
         *
         * @return string the name of the table for the join query.
         */
        public function getTableName() {
            return $this->table;
        }

        /**
         * Gets teh keys for the join query.
         *
         * @return string the keys for the join query.
         */
        public function getKeys() {
            $keys = "";
            for ($i = 0; $i < (sizeof($this->keys) - 1); $i++) {
                $keys .= $this->keys[$i] .", ";
            }
            $keys .= $this->keys[sizeof($this->keys) - 1];
            return $keys;
        }

        /**
         * Gets the parameters for the join query for this table.
         *
         * @return string[] the parameters for the join query.
         */
        public function getParams() {
            $newParams = array();
            foreach ($this->params as $param) {
                array_push($newParams, $this->table . "." . $param);
            }
            return $newParams;
        }

        /**
         * Adds parameters to the join table for the join query.
         *
         * @param ...$params string[] the parameters for this join table.
         * @return void
         */
        public function addParams(...$params) {
            if($params === null) {
                throw new InvalidArgumentException("Params cannot be null");
            }
            if (is_array($params[0])) {
                $params = $params[0];
            }
            $this->params = $params;
        }
    }