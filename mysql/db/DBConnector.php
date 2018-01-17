<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/exceptions/SqlExceptions.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 9:20 AM
     */
    final class DBConnector {
        private static $instance = null;
        private $connection;
        private $DB_hostname;
        private $DB_database_name;
        private $DB_username;
        private $DB_password;

        private function __construct() {
            $temp = fgets(fopen($_SERVER['DOCUMENT_ROOT'] . '/dbconfig', 'r'));
            $details = explode(", ", $temp);
            $this->DB_hostname = $details[0];
            $this->DB_database_name = $details[1];
            $this->DB_username = $details[2];
            $this->DB_password = $details[3];
            if (!($connection =
                @ mysqli_connect($this->DB_hostname, $this->DB_username, $this->DB_password, $this->DB_database_name))
            ) {
                throw new SQLConnectionException("Connection to database failed");
            }
            $this->connection = $connection;
        }

        /**
         * Gets an instance of the DBConnector.
         *
         * @return mysqli connection;
         */
        public static function getConnector() {
            if (self::$instance == null) {
                self::$instance = new DBConnector();
            }
            return self::$instance->connection;
        }
    }