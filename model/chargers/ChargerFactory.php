<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/ACharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/SuperCharger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/Location.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/DestinationCharger.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 1:51 PM
     */
    class ChargerFactory {
        /**
         * Gets the charger by the given id.
         *
         * @param $id int the charger_id
         * @return Charger the charger with the given id.
         */
        public static function superChargerById($id) {
            $query = new SelectQuery("chargers", "type");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return self::getChargerByIdOfType($id, $row['type']);
        }

        /**
         * Gets a list of all of the chargers in the database.
         *
         * @return Charger[]
         */
        public static function getAllChargers() {
            $query = new SelectQuery("chargers", "charger_id", "name", "lng", "lat", "address", "type");
            $result = DBQuerrier::defaultQuery($query);
            $chargers = array();
            while ($row = @ mysqli_fetch_array($result)) {
                switch ($row['type']) {
                    case "supercharger":
                        array_push($chargers,
                            new SuperCharger($row['charger_id'], $row['name'], new Location($row['lat'], $row['lng']),
                                null, $row['address'], null, null, null));
                        break;
                    case "destinationcharger":
                        array_push($chargers, new DestinationCharger($row['charger_id'], $row['name'],
                            new Location($row['lat'], $row['lng']), null, $row['address']));
                        break;
                    default:
                        throw new InvalidArgumentException("Invalid type of charger");
                }
            }
            return $chargers;
        }

        public static function getChargerByIdOfType($id, $type) {
            switch ($type) {
                case "supercharger":
                    return new SuperCharger($id, null, null, null, null, null, null, null);
                    break;
                case "destinationcharger":
                    return new DestinationCharger($id, null, null, null, null);
                    break;
                default:
                    throw new InvalidArgumentException("Invalid type of charger");
            }
        }

        /**
         * Gets all of the chargers of the given type from the database.
         *
         * @param $type string the charger_type.
         * @return ChargerReview[] the reviews of the given charger type;
         */
        public static function getReviewsOfType($type) {
            $innerQuery = new SelectQuery("chargers", "charger_id");
            $innerQuery->where(Where::whereEqualValue("type", DBValue::stringValue($type)));
            $table1 = new QueriedJoinTable($innerQuery, "results1", "charger_id");
            $table2 = new DBJoinTable("reviews", "charger_id");
            $table2->addParams("charger_id", "review_id", "link", "reviewer", "email", "rating", "review_date",
                "ttn_id");
            $joinQuery = new InnerJoin($table1, $table2);
            $results = DBQuerrier::defaultQuery($joinQuery);
            $reviews = array();
            while ($row = @ mysqli_fetch_array($results)) {
                array_push($reviews, new ChargerReview($row['review_id'],
                    self::getChargerByIdOfType($row['charger_id'], $type), $row['link'],
                    $row['reviewer'], $row['email'], $row['rating'], $row['review_date'], $row['ttn_episode']));
            }
            return $reviews;
        }
    }