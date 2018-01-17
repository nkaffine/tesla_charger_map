<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/AndWhereCombiner.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 1:50 PM
     */
    class UpdateQuery implements IQuery {
        private $table;
        private $parameters;
        /**
         * @var DBValue[]
         */
        private $values;
        /**
         * @var IWhere
         */
        private $where;

        public function __construct($table) {
            $this->table = $table;
            $this->parameters = array();
            $this->values = array();
        }

        /**
         * Adds a where statement to this query.
         *
         * @param $where IWhere the where statement for this query.
         */
        public function where($where) {
            if ($this->where === null) {
                $this->where = $where;
            } else {
                $combiner = new AndWhereCombiner();
                $combiner->addWhere($this->where)->addWhere($where);
                $this->where = $combiner;
            }
        }

        /**
         * Adds a parameter and value to the update statement.
         *
         * @param $param string the column in the database being updated.
         * @param $value DBValue the value the column is being updated to.
         * @return $this
         */
        public function addParamAndValue($param, $value) {
            array_push($this->parameters, $param);
            $this->values[$param] = $value;
            return $this;
        }

        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.
         */
        public function generateQuery() {
            $query = "UPDATE " . $this->table . " set ";
            for ($i = 0; $i < (sizeof($this->parameters) - 1); $i++) {
                $query .= $this->parameters[$i] . " = " . $this->values[$this->parameters[$i]]->getValueString() . ", ";
            }
            $query .= $this->parameters[sizeof($this->parameters) - 1] . " = " .
                $this->values[$this->parameters[sizeof($this->parameters) - 1]]->getValueString();
            if ($this->where != null && $this->where->isWhere()) {
                $query .= " WHERE " . $this->where->generateWhere();
            }
            return $query;
        }
    }