<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/29/17
     * Time: 9:10 AM
     */
    interface ISelectQuery extends IQuery {
        /**
         * Adds a where to this query.
         *
         * @param $where IWhere the where being added to this query.
         * @return void
         */
        public function where($where);

        /**
         * Adds an order to this query.
         *
         * @param $order IOrder the order being added.
         * @return void
         */
        public function order($order);

        /**
         * Adds a limit to this query.
         *
         * @param $limit int the max number of rows the query cna return.
         */
        public function limit($limit);

        /**
         * Sets the group by for this query.
         *
         * @param $groupBy IGroupBy the group by for this query.
         */
        public function groupBy($groupBy);
    }