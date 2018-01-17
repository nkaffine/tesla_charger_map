<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/IJoinTable.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/29/17
     * Time: 9:05 AM
     */
    class QueriedJoinTable implements IJoinTable {
        private $table;
        private $tableName;
        private $keys;
        private $params;

        /**
         * QueriedJoinTable constructor.
         *
         * @param $table ISelectQuery the query producing the table being joined.
         * @param $tableName string the name of the table for the join.
         * @param ...$keys string[] the keys for the join.
         */
        public function __construct($table, $tableName, ...$keys) {
            if ($table === null || $tableName === null || $keys === null) {
                throw new InvalidArgumentException("Table, Table name, and Keys cannot be null");
            }
            if (is_array($keys[0])) {
                $keys = $keys[0];
            }
            $this->table = $table;
            $this->tableName = $tableName;
            $this->keys = $keys;
        }

        /**
         * Gets the table for the join query.
         *
         * @return string the table used in the query.
         */
        public function getTable() {
            return "(" . $this->table->generateQuery() . ") AS " . $this->tableName;
        }

        /**
         * Gets the name of the table for join query.
         *
         * @return string the name of the table for the join query.
         */
        public function getTableName() {
            return $this->tableName;
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
                array_push($newParams, $this->tableName . "." . $param);
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
            if ($params === null) {
                throw new InvalidArgumentException("Params cannot be null");
            }
            if (is_array($params[0])) {
                $params = $params[0];
            }
            $this->params = $params;
        }
    }