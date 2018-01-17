<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/IQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 2:52 PM
     */
    interface IParameterValueQuery extends IQuery {
        /**
         * Adds values to this query.
         *
         * @param string    $param
         * @param DBValue[] ...$values
         * @return void
         */
        public function addParamAndValues($param, ...$values);
    }