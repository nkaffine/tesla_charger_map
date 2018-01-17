<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBConnector.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 10:35 AM
     */
    class DBQuerrier {
        /**
         * Performs the given query without any expectations on the result
         *
         * @param $query IQuery.
         * @return mysqli_result.
         * @throws SQLQueryFailException when the query fails
         */
        public static function defaultQuery($query) {
            $result = @ mysqli_query(DBConnector::getConnector(), $query->generateQuery());
            if ($result == FALSE) {
                throw new SQLQueryFailException("Query Failed: " . $query->generateQuery());
            }
            return $result;
        }

        /**
         * Performs the given query which expects a unique value to be returned
         *
         * @param $query IQuery.
         * @return mysqli_result.
         * @throws SQLNonUniqueValueException when multiple values are found
         * @throws SQLQueryFailException when query fails
         * @throws SQLNoSuchValueException when no values are found
         */
        public static function queryUniqueValue($query) {
            $result = self::defaultQuery($query);
            if (mysqli_num_rows($result) > 1) {
                throw new SQLNonUniqueValueException("Expect to find unique value but found many");
            } elseif (mysqli_num_rows($result) < 1) {
                throw new SQLNoSuchValueException("Expected to find value but found none");
            }
            return $result;
        }


        /**
         * Runs the updating query without any expectations on the number of updates
         *
         * @param $query IQuery.
         * @throws SQLUpdateFailedException if an error occurred on update
         */
        public static function defaultUpdate($query) {
            self::defaultQuery($query);
            if (mysqli_affected_rows(DBConnector::getConnector()) == -1) {
                throw new SQLUpdateFailedException("Update Failed");
            }
        }

        /**
         * Runs the updating query expecting to update only one value and can either require all to be updated or all
         * for some to not be updated.
         *
         * @param $query IQuery.
         * @param $requireUpdate boolean is the update required.
         * @throws SQLNoSuchValueException.
         * @throws SQLValuesMatchedButNotUpdatedException.
         */
        private static function updateUniqueValue($query, $requireUpdate) {
            self::defaultUpdate($query);
            preg_match_all('/(\S[^:]+): (\d+)/', DBConnector::getConnector()->info, $matches);
            $info = array_combine($matches[1], $matches[2]);
            if ($info ['Rows matched'] == 0) {
                throw new SQLNoSuchValueException("This operation did not match any rows.");
            } elseif ($info ['Changed'] == 0) {
                if ($requireUpdate) {
                    throw new SQLValuesMatchedButNotUpdatedException("The operation matched rows but did not update them");
                }
            }
            if ($info ['Changed'] < $info ['Rows matched']) {
                throw new SQLValuesMatchedButNotUpdatedException(($info ['Rows matched'] - $info ['Changed']) .
                    " rows matched but were not changed.");
            }
        }

        /**
         * Performs the update query and requires that all values that match the query are updated.
         *
         * @param $query IQuery the update query.
         * @throws SQLNoSuchValueException.
         * @throws SQLValuesMatchedButNotUpdatedException
         */
        public static function updateUniqueValueRequireUpdate($query) {
            self::updateUniqueValue($query, true);
        }

        /**
         * Performs the update query and does not require that all values that match the query are updated.
         *
         * @param $query IQuery the update query.
         * @throws SQLNoSuchValueException
         */
        public static function updateUniqueValueUpdateNotRequired($query) {
            self::updateUniqueValue($query, false);
        }

        /**
         * Performs an insert query and expects that the query does not throw an error.
         *
         * @param $query IQuery the insert query.
         * @throws SQLInsertFailException
         */
        public static function defaultInsert($query) {
            self::defaultQuery($query);
            if (mysqli_affected_rows(DBConnector::getConnector()) == -1) {
                throw new SQLInsertFailException("Insert Failed");
            }
        }

        /**
         * Performs a delete query and expects that the query does not throw an error.
         *
         * @param $query IQuery the delete query.
         * @throws SQLDeleteFailException
         */
        public static function defaultDelete($query) {
            self::defaultQuery($query);
            if (mysqli_affected_rows(DBConnector::getConnector()) == -1) {
                throw new SQLDeleteFailException("Delete Failed");
            }
        }

        /**
         * Performs a query intended to see if something in the database exists and returns the value queried for if it
         * is in the database, otherwise it returns false.
         *
         * @param $query IQuery the query for checking if something exists.
         * @return bool|mysqli_result either the result of the query if it exists or false.
         * @throws SQLNonUniqueValueException if there is more than 1 result of the query.
         */
        public static function checkExistsQuery($query) {
            $result = self::defaultQuery($query);
            if (mysqli_num_rows($result) > 1) {
                throw new SQLNonUniqueValueException("More than one value was returned");
            }
            if (mysqli_num_rows($result) == 1) {
                return $result;
            } else {
                return false;
            }
        }

        /**
         * Performs a query that returns one value from the database from the query.
         *
         * @param $query AggregateQuery the aggregate query that are being queried.
         * @return mixed the value from the database.
         */
        public static function aggregateQuery($query) {
            $result = self::queryUniqueValue($query);
            return $result[$query->mathQuery()];
        }
    }