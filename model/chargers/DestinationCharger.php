<?php
    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/16/18
     * Time: 6:24 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/ACharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertIncrementQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertQuery.php");

    class DestinationCharger extends ACharger {
        /**
         * DestinationCharger constructor.
         *
         * @param $id int the id of the destination charger.
         * @param $name string the name of the destination charger.
         * @param $location ILocation the location of the destination charger.
         * @param $reviews IChargerReview[] the reviews for this destination charger.
         * @param $address string the address for this destination charger.
         */
        public function __construct($id, $name, $location, $reviews, $address) {
            parent::__construct($id, $name, $location, $reviews, $address);
        }

        /**
         * Creates a bew destination charger with the given information.
         *
         * @param $name string the name of the destination charger.
         * @param $location ILocation the location of the destination charger.
         * @param $address string the address of the destination charger.
         * @return DestinationCharger the new destination charger.
         */
        public static function newCharger($name, $location, $address) {
            if ($name === null || $location === null || $address === null) {
                throw new InvalidArgumentException("No values can be null");
            }
            $query = new InsertIncrementQuery("chargers", "charger_id");
            $query->addParamAndValues("name", DBValue::stringValue($name))
                ->addParamAndValues("lng", DBValue::nonStringValue($location->getLng()))
                ->addParamAndValues("lat", DBValue::nonStringValue($location->getLat()))
                ->addParamAndValues("type", DBValue::stringValue("destinationcharger"))
                ->addParamAndValues("address", DBValue::stringValue($address));
            DBQuerrier::defaultInsert($query);
            $primaryKey = $query->getPrimaryKeyValues()[0];
            return new DestinationCharger($primaryKey, $name, $location, null, $address);
        }

        /**
         * Returns the type of this charger.
         *
         * @return string the type of the charger.
         */
        public function getChargerType() {
            return "destinationcharger";
        }
        /**
                 * Gets all of the destination chargers in the database.
                 *
                 * @return DestinationCharger[]
                 */
        public static function getAllDestinationChargers() {
            $query = new SelectQuery("chargers", "charger_id", "name", "lat", "lng", "address");
            $query->where(Where::whereEqualValue("type", DBValue::stringValue("destinationcharger")));
            $results = DBQuerrier::defaultQuery($query);
            $destinationChargers = array();
            while ($row = @ mysqli_fetch_array($results)) {
                array_push($destinationChargers,
                    new DestinationCharger($row['charger_id'], $row['name'], new Location($row['lat'], $row['lng']),
                        null, $row['address']));
            }
            return $destinationChargers;
        }
    }