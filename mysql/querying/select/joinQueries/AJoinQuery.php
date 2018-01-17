<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/IJoinQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/29/17
     * Time: 9:26 AM
     */
    abstract class AJoinQuery implements IJoinQuery {
        /**
         * @var IJoinTable[]
         */
        private $joinTables;
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
         * AJoinQuery constructor.
         *
         * @param array ...$joinTables
         */
        public function __construct(...$joinTables) {
            if ($joinTables === null) {
                throw new InvalidArgumentException("Join Tables cannot be null");
            }
            if (is_array($joinTables[0])) {
                $joinTables = $joinTables[0];
            }
            if (sizeof($joinTables) === 0) {
                throw new InvalidArgumentException("There must be at least one table to join");
            }
            $this->joinTables = $joinTables;
        }

        /**
         * Generates the query string of this query.
         *
         * @return string the query string of this query.n
         */
        public function generateQuery() {
            $query = "SELECT ";
            $params = array();
            foreach ($this->joinTables as $table) {
                foreach ($table->getParams() as $string) {
                    array_push($params, $string);
                }
            }
            $paramString = "";
            foreach ($params as &$param) {
                $paramString .= $param . ", ";
            }
            $paramString = substr($paramString, 0, strlen($paramString) - 2);
            $query = $query . $paramString . " FROM ";
            $query .= $this->joinTables[0]->getTable();
            for ($i = 1; $i < sizeof($this->joinTables); $i++) {
                $query .= " " . $this->getJoinCommand() . " " . $this->joinTables[$i]->getTable() . " USING (" .
                    $this->joinTables[$i]->getKeys() . ")";
            }
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
         * Adds a where to this query.
         *
         * @param $where IWhere the where being added to this query.
         * @return void
         */
        public function where($where) {
            if ($this->where === null) {
                $this->where = $where;
            } else {
                $this->where = AndWhereCombiner::newCombiner()->addWhere($this->where)->addWhere($where);
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
         * Sets the group by for this query.
         *
         * @param $groupBy IGroupBy the group by for this query.
         */
        public function groupBy($groupBy) {
            $this->groupBY = $groupBy;
        }

        /**
         * Gets the join command for this join query.
         *
         * @return string the join command for this query.
         */
        abstract protected function getJoinCommand();
    }