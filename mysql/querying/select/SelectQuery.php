<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/AndWhereCombiner.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/order/OrderCombiner.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/order/OrderStatement.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/AverageQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/CountQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/GroupConcatQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/MaxQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/MinQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/PopulationStandardDeviation.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/PopulationVariance.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/SampleStandardDeviation.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/SampleVariance.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/aggregateQuery/SumQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/ISelectQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 10:48 AM
     */
    class SelectQuery implements ISelectQuery {
        protected $table;
        protected $parameters;
        /**
         * @var IWhere
         */
        protected $where;
        /**
         * @var IOrder
         */
        protected $order;
        protected $limit;
        /**
         * @var IGroupBy
         */
        protected $groupBY;

        /**
         * SelectQuery constructor.
         *
         * @param $table string the table being queried.
         * @param ...$parameters string[] the parameters for the query
         */
        public function __construct($table, ...$parameters) {
            if ($table == null) {
                throw new InvalidArgumentException("Table cannot be null");
            }
            if ($parameters == null) {
                throw new InvalidArgumentException("Parameters cannot be null");
            }
            $this->table = $table;
            $this->parameters = $parameters;
            $this->joins = array();
        }

        /**
         * Adds a where to this query.
         *
         * @param $where IWhere the where being added to this query.
         * @return void
         */
        public function where($where) {
            if ($this->where == null) {
                $this->where = $where;
            } else {
                $combiner = new AndWhereCombiner();
                $this->where = $combiner->addWhere($this->where)->addWhere($where);
            }
        }

        /**
         * Adds an order to this query.
         *
         * @param $order IOrder the order being added.
         * @return void
         */
        public function order($order) {
            if ($this->order == null) {
                $this->order = $order;
            } else {
                $this->order = OrderCombiner::newCombiner()->addOrder($this->order)->addOrder($order);
            }
        }

        /**
         * Adds a limit to this query.
         *
         * @param $limit int the max number of rows the query cna return.
         */
        public function limit($limit) {
            $this->limit = $limit;
        }


        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.
         */
        public function generateQuery() {
            $query = "SELECT ";
            $paramString = "";
            foreach ($this->parameters as &$param) {
                $paramString .= $param . ", ";
            }
            $paramString = substr($paramString, 0, strlen($paramString) - 2);
            $query = $query . $paramString . " FROM " . $this->table;
            if ($this->where !== null && $this->where->isWhere()) {
                $query .= " WHERE " . $this->where->generateWhere();
            }
            if ($this->groupBY !== null && $this->groupBY->isGroupBy()) {
                $query .= " GROUP BY " . $this->groupBY->generateGroupBy();
            }
            if ($this->order != null && $this->order->isOrder()) {
                $query .= " ORDER BY " . $this->order->generateOrder();
            }
            if ($this->limit != null) {
                $query .= " LIMIT " . $this->limit;
            }
            return $query;
        }

        /**
         * Sets the group by for this query.
         *
         * @param $groupBy IGroupBy the group by for this query.
         */
        public function groupBy($groupBy) {
            $this->groupBY = $groupBy;
        }
    }