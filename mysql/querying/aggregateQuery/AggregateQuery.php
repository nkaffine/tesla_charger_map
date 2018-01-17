<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/26/17
     * Time: 12:35 PM
     */
    abstract class AggregateQuery implements IQuery {
        /**
         * @var SelectQuery
         */
        private $innerQuery;
        protected $parameter;
        protected $distinct;

        /**
         * AggregateQuery constructor.
         *
         * @param $table string the table in the database.
         * @param $parameter string the column in the database.
         */
        public function __construct($table, $parameter) {
            if ($table === null || $parameter === null) {
                throw new InvalidArgumentException("Table and parameter cannot be null");
            }
            $this->parameter = $parameter;
            $this->innerQuery = new SelectQuery($table, $this->mathQuery());
            $this->distinct = false;
        }

        /**
         * Makes the query apply to distinct values.
         */
        public function distinct() {
            $this->distinct = true;
        }

        /**
         * Returns the column name based on the type of math query that it is.
         *
         * @return string the column with the math in the database.
         */
        public function mathQuery() {
            $param = $this->mathFunction() . "(";
            if ($this->distinct) {
                $param .= "DISTINCT ";
            }
            return $param . $this->parameter . ")";
        }

        /**
         * Returns the name of the function for this query.
         *
         * @return string the name of the function for this query.
         */
        protected abstract function mathFunction();

        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.
         */
        public function generateQuery() {
            return $this->innerQuery->generateQuery();
        }

        /**
         * Adds a where to the query.
         *
         * @param $where IWhere the where being added to the query.
         * @return void
         */
        public function where($where) {
            $this->innerQuery->where($where);
        }

        /**
         * Adds an order to this query.
         *
         * @param $order IOrder the order being added.
         * @return void
         */
        public function order($order) {
            $this->innerQuery->order($order);
        }

        /**
         * Adds a limit to this query.
         *
         * @param $limit int the max number of rows the query cna return.
         */
        public function limit($limit) {
            $this->innerQuery->limit($limit);
        }

        /**
         * Sets the group by for this query.
         *
         * @param $groupBy IGroupBy the group by for this query.
         */
        public function groupBy($groupBy) {
            $this->innerQuery->groupBy($groupBy);
        }
    }