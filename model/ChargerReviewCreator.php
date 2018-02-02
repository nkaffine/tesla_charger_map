<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/Location.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/1/18
     * Time: 8:38 PM
     */
    class ChargerReviewCreator {
        /**
         * @param $name string the name supplied by the user.
         * @param $email string the email supplied by the user.
         * @param $locationName string the name of the location that the charger is in.
         * @param $address string the address of the charger.
         * @param $stalls int the number of stalls the charger has.
         * @param $link string the link to the youtube video.
         * @param $rating int the rating of the charger the user provided.
         * @param $lng float the longitude of the charger.
         * @param $lat float the latitude of the charger.
         * @param $type int the type of the charger.
         * @param $status int the status of the charger.
         * @param $openDate string the date that the charger was opened.
         */
        public static function newUserReview($name, $email, $locationName, $address, $stalls, $link, $rating, $lng,
                                             $lat, $type, $status, $openDate) {
            try {
                $location1 = new Location($lat, $lng);
                $box = $location1->getBox(.1);
                $query = new SelectQuery("chargers", "charger_id", "lat", "lng");
                $query = new CustomerQuery($query->generateQuery() . " WHERE " .
                    withinCoords($box->getMinLng(), $box->getMaxLng(), $box->getMinLat(), $box->getMaxLat()));
                $result = DBQuerrier::checkExistsQuery($query);
                if (!$result) {
                    switch ($type) {
                        case 0:
                            //the supercharger is not in the system or the google maps.
                            self::superChargerNotInSystem($name, $email, $locationName, $address, $stalls, $link, $rating,
                                $lng, $lat, $status, $openDate);
                            break;
                        case 1:
                            //the destination charger is not in the system.
                            self::destinationChargerNotInSystem($name, $email, $locationName, $address, $link, $rating, $lng,
                                $lat);
                            break;
                        default:
                            throw new InvalidArgumentException("Invalid charger type");
                    }
                } else {
                    $row = @ mysqli_fetch_array($result);
                    $primaryKey = $row['charger_id'];
                    $query = new InsertIncrementQuery("reviews", "review_id");
                    $query->addParamAndValues("link", DBValue::stringValue($link))
                        ->addParamAndValues("reviewer", DBValue::stringValue($name))
                        ->addParamAndValues("email", DBValue::stringValue($email))
                        ->addParamAndValues("rating", DBValue::nonStringValue($rating))
                        ->addParamAndValues("charger_id", DBValue::nonStringValue($primaryKey));
                    DBQuerrier::defaultInsert($query);
                }
            } catch (Exception $exception) {
                //There was more than one supercharger that returned.
                switch ($type) {
                    case 0:
                        //the supercharger is not in the system or the google maps.
                        self::superChargerNotInSystem($name, $email, $locationName, $address, $stalls, $link, $rating,
                            $lng, $lat, $status, $openDate);
                        break;
                    case 1:
                        //the destination charger is not in the system.
                        self::destinationChargerNotInSystem($name, $email, $locationName, $address, $link, $rating, $lng,
                            $lat);
                        break;
                    default:
                        throw new InvalidArgumentException("Invalid charger type");
                }
            }
        }

        /**
         * @param $name string the name of the reviewer.
         * @param $email string the email of the user
         * @param $locationName string the location name.
         * @param $address string the address of the supercharger.
         * @param $stalls int the number of stalls of the charger.
         * @param $link string the link to the review.
         * @param $rating int the user rating for the charger.
         * @param $lng float the longitude for the charger.
         * @param $lat float the latitude of the charger.
         * @param $status int the status of the charger.
         * @param $openDate string the date the charger was opened.
         */
        private static function superChargerNotInSystem($name, $email, $locationName, $address, $stalls, $link, $rating,
                                                        $lng, $lat, $status, $openDate) {
            $query = new InsertIncrementQuery("sc_not_in_system", "charger_id");
            $query->addParamAndValues("name", DBValue::stringValue($name))
                ->addParamAndValues("email", DBValue::stringValue($email))
                ->addParamAndValues("location", DBValue::stringValue($locationName))
                ->addParamAndValues("address", DBValue::stringValue($address))
                ->addParamAndValues("stalls", DBValue::nonStringValue($stalls))
                ->addParamAndValues("link", DBValue::stringValue($link))
                ->addParamAndValues("rating", DBValue::nonStringValue($rating))
                ->addParamAndValues("lng", DBValue::nonStringValue($lng))
                ->addParamAndValues("lat", DBValue::nonStringValue($lat))
                ->addParamAndValues("status", DBValue::nonStringValue($status))
                ->addParamAndValues("open_date", DBValue::nonStringValue($openDate));
            DBQuerrier::defaultInsert($query);
        }

        /**
         * @param $name string the name of the reviewer.
         * @param $email string the email of the user.
         * @param $locationName string the location name.
         * @param $address string the address of the destination charger.
         * @param $link string the link to the review.
         * @param $rating int the user rating for the charger.
         * @param $lng float the longitude of the charger.
         * @param $lat float the latitude of the charger.
         */
        private static function destinationChargerNotInSystem($name, $email, $locationName, $address, $link, $rating,
                                                              $lng, $lat) {
            $query = new InsertIncrementQuery("dc_not_in_chargers", "charger_id");
            $query->addParamAndValues("name", DBValue::stringValue($name))
                ->addParamAndValues("email", DBValue::stringValue($email))
                ->addParamAndValues("location", DBValue::stringValue($locationName))
                ->addParamAndValues("address", DBValue::stringValue($address))
                ->addParamAndValues("link", DBValue::stringValue($link))
                ->addParamAndValues("rating", DBValue::nonStringValue($rating))
                ->addParamAndValues("lng", DBValue::nonStringValue($lng))
                ->addParamAndValues("lat", DBValue::nonStringValue($lng));
            DBQuerrier::defaultInsert($query);
        }

        private static function withinCoords($min_lon, $max_lon, $min_lat, $max_lat) {
            return "(lng > $min_lon and lng < $max_lon) and " .
                "(lat > $min_lat and lat < $max_lat)";
        }
    }