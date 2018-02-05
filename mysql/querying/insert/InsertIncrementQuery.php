<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IParameterValueQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 10:11 AM
     */
    class InsertIncrementQuery implements IParameterValueQuery {
        private $innerInsertQuery;
        private $innerPrimaryKeyQuery;
        private $primaryKey;
        private $primaryKeyRetrieved;
        /**
         * @var DBValue[]
         */
        private $primaryKeyValues;

        /**
         * InsertIncrementQuery constructor.
         *
         * @param $table string the table in the database being inserted into.
         * @param $primaryKey string the primary key of the table in the database.
         */
        public function __construct($table, $primaryKey) {
            if ($table === null || $primaryKey === null) {
                throw new InvalidArgumentException("Table and Primary Key cannot be null");
            }
            $this->innerPrimaryKeyQuery = new SelectQuery($table, "MAX(" . $primaryKey . ")");
            $this->innerInsertQuery = new InsertQuery($table);
            $this->primaryKey = $primaryKey;
            $this->primaryKeyRetrieved = false;
        }

        /**
         * Gets the next primary key value in the table.
         *
         * @return void
         */
        private function getNextPrimaryKey() {
            if(!$this->primaryKeyRetrieved) {
                $result = DBQuerrier::queryUniqueValue($this->innerPrimaryKeyQuery);
                $row = @ mysqli_fetch_array($result);
                $max = $row["MAX(" . $this->primaryKey . ")"] + 1;
                $numRows = $this->innerInsertQuery->getNumRows();
                $values = array();
                for ($i = 0; $i < $numRows; $i++) {
                    array_push($values, DBValue::nonStringValue($max + $i));
                }
                $this->primaryKeyValues = $values;
                $this->innerInsertQuery->addParamAndValues($this->primaryKey, $values);
                $this->primaryKeyRetrieved = true;
            }
        }

        /**
         * Adds a where statement to the query for finding the next primary key.
         *
         * @param $where IWhere the where for the primary key.
         * @return void
         */
        public function where($where) {
            $this->innerPrimaryKeyQuery->where($where);
        }

        /**
         * Adds values for a parameter.
         *
         * @param string    $param
         * @param DBValue[] ...$values
         * @return $this;
         */
        public function addParamAndValues($param, ...$values) {
            return $this->addParamAndValuesArray($param, $values);
        }

        public function addParamAndValuesArray($param, $values) {
            $this->innerInsertQuery->addParamAndValues($param, $values);
            return $this;
        }

        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.
         */
        public function generateQuery() {
            $this->getNextPrimaryKey();
            return $this->innerInsertQuery->generateQuery();
        }

        public function getPrimaryKeyValues() {
            $values = array();
            foreach ($this->primaryKeyValues as $value) {
                array_push($values, $value->getValueString());
            }
            return $values;
        }
    }