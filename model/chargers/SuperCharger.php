<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 11:45 AM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/ACharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertIncrementQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/InnerJoin.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/DBJoinTable.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/mysql/querying/select/joinQueries/QueriedJoinTable.php");

    class SuperCharger extends ACharger {
        protected $status;
        protected $openDate;
        protected $stalls;

        public function __construct($id, $name, $location, $reviews, $address, $status, $openDate, $stalls) {
            parent::__construct($id, $name, $location, $reviews, $address);
            $this->status = $status;
            $this->openDate = $openDate;
            $this->stalls = $stalls;
        }

        /**
         * Gets the opening date for the charger.
         *
         * @return string the opening date for this charger
         */
        public function getOpenDate() {
            if ($this->openDate === null) {
                $this->openDate = $this->getOpenDateFromDB();
            }
            return $this->openDate;
        }


        /**
         * Gets the opening date for the charger from the database.
         *
         * @return string the opening date for this charger.
         */
        private function getOpenDateFromDB() {
            $query = new SelectQuery("super_chargers", "open_date");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $results = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($results);
            return $row['open_date'];
        }

        /**
         * Gets the status of the charger, i.e. open, under construction.
         *
         * @return string the status of the charger.
         */
        public function getStatus() {
            if ($this->status === null) {
                $this->status = $this->getStatusFromDB();
            }
            return $this->status;
        }

        /**
         * Gets the status of this charger from the database.
         *
         * @return string the status of this charger from the database.
         */
        private function getStatusFromDB() {
            $query = new SelectQuery("super_chargers", "status");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['status'];
        }

        /**
         * Gets the number of the stalls of this charger.
         *
         * @return int the number of stalls of this charger.
         */
        public function getNumberOfStalls() {
            if ($this->stalls === null) {
                $this->stalls = $this->getNumberOfStallsFromDB();
            }
            return $this->stalls;
        }

        /**
         * Gets the number of stalls of this charger from the database.
         *
         * @return int the number of stalls of this charger from the database.
         */
        private function getNumberOfStallsFromDB() {
            $query = new SelectQuery("super_chargers", "stalls");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $results = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($results);
            return $row['stalls'];
        }

        /**
         * Returns the type of charger that this charger is.
         *
         * @return string the type of charger this charger is.
         */
        public function getChargerType() {
            return "supercharger";
        }

        /**
         * Creates a new charger from the given name, location, and type.
         *
         * @param $name string the name of the charger.
         * @param $location ILocation the location of the charger.
         * @param $address string the address for the charger.
         * @param $status string the status of the charger.
         * @param $openDate string the open date of the super charger.
         * @param $stalls int the number of stalls the charger has.
         * @return Charger
         */
        public static function newCharger($name, $location, $address, $status, $openDate, $stalls) {
            if ($name === null || $location === null || $address === null || $status === null) {
                throw new InvalidArgumentException("Name, location and type must not be null");
            }
            $query = new InsertIncrementQuery("chargers", "charger_id");
            $query->addParamAndValues("name", DBValue::stringValue($name))
                ->addParamAndValues("lng", DBValue::nonStringValue($location->getLng()))
                ->addParamAndValues("lat", DBValue::nonStringValue($location->getLat()))
                ->addParamAndValues("address", DBValue::stringValue($address))
                ->addParamAndValues("type", DBValue::stringValue("supercharger"));
            DBQuerrier::defaultInsert($query);
            $primaryKey = $query->getPrimaryKeyValues()[0];
            $query = new InsertQuery("super_chargers");
            $query->addParamAndValues("status", DBValue::stringValue($status))
                ->addParamAndValues("stalls", DBValue::nonStringValue($stalls))
                ->addParamAndValues("charger_id", DBValue::nonStringValue($primaryKey));
            if ($openDate !== null) {
                $query->addParamAndValues("open_date", DBValue::stringValue($openDate));
            }
            DBQuerrier::defaultInsert($query);
            return new SuperCharger($primaryKey, $name, $location, null, $address,
                $status, $openDate, $stalls);
        }

        /**
         * Gets all of the superchargers in the database.
         *
         * @return SuperCharger[]
         */
        public static function getAllSuperChargers() {
            $innerQuery = new SelectQuery("chargers", "charger_id", "name", "lat", "lng", "address");
            $innerQuery->where(Where::whereEqualValue("type", DBValue::stringValue("supercharger")));
            $table1 = new QueriedJoinTable($innerQuery, "results1", "charger_id");
            $table1->addParams("charger_id", "name", "lat", "lng", "address");
            $table2 = new DBJoinTable("super_chargers", "charger_id");
            $table2->addParams("sc_info_id", "status", "open_date", "stalls");
            $joinQuery = new InnerJoin($table1, $table2);
            $results = DBQuerrier::defaultQuery($joinQuery);
            $superChargers = array();
            while ($row = @ mysqli_fetch_array($results)) {
                array_push($superChargers,
                    new SuperCharger($row['charger_id'], $row['name'], new Location($row['lat'], $row['lng']), null,
                        $row['address'], $row['status'], $row['open_date'], $row['stalls']));
            }
            return $superChargers;
        }
    }