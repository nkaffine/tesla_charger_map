<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/IWhere.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:28 AM
     */
    interface IWhereCombiner extends IWhere {
        /**
         * Adds a where to the combiner.
         *
         * @param $where IWhere the where being added to the combiner.
         * @return IWhere
         */
        public function addWhere($where);
    }