<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IParameterValueQuery.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/mysql/querying/insert/InsertRow.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/mysql/querying/DBValue.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 9:19 AM
     */
    class InsertQuery implements IParameterValueQuery {
        private $table;
        private $parameters;
        /**
         * @var InsertRow[]
         */
        private $rows;

        /**
         * InsertQuery constructor.
         *
         * @param $table string the name of the table where values are being inserted.
         */
        public function __construct($table) {
            if ($table === null) {
                throw new InvalidArgumentException("Table cannot be null");
            }
            $this->table = $table;
            $this->parameters = array();
            $this->rows = array();
        }

        /**
         * Add values for the given parameter.
         *
         * @param               $parameter string the parameter the values correspond to.
         * @param DBValue[]     ...$values
         * @return $this
         */
        public function addParamAndValues($parameter, ...$values) {
            array_push($this->parameters, $parameter);
            $isFirst = sizeof($this->rows) === 0;
            // Checks if the first value of the array is an array to allow for overloading.
            if (is_array($values[0])) {
                $values = $values[0];
            }
            for ($i = 0; $i < sizeof($values); $i++) {
                if ($isFirst) {
                    $row = new InsertRow();
                    $row->addValue($parameter, $values[$i]);
                    $this->rows[$i] = $row;
                } else {
                    $this->rows[$i]->addValue($parameter, $values[$i]);
                }
            }
            return $this;
        }

        /**
         * Gets the number of rows in the insert statement.
         *
         * @return int the number of rows in the insert statement.
         */
        public function getNumRows() {
            return sizeof($this->rows);
        }

        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.
         */
        public function generateQuery() {
            $queryString = "INSERT INTO " . $this->table;
            $queryString .= " (";
            for ($i = 0; $i < (sizeof($this->parameters) - 1); $i++) {
                $queryString .= $this->parameters[$i] . ", ";
            }
            $queryString .= $this->parameters[sizeof($this->parameters) - 1] . ") VALUES ";
            for ($i = 0; $i < (sizeof($this->rows) - 1); $i++) {
                $queryString .= $this->rows[$i]->getRowString($this->parameters) . ", ";
            }
            $queryString .= $this->rows[sizeof($this->rows) - 1]->getRowString($this->parameters);
            return $queryString;
        }
    }