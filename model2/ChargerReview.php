<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 4/29/18
     * Time: 6:12 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model2/IChargerReview.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/LocationBox.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/Location.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/CustomQuery.php");

    class ChargerReview implements IChargerReview {
        /**
         * @param $name string the name of the reviewer.
         * @param $email string the email of the reviewer.
         * @param $charger_name string the name of the charger.
         * @param $address string the address of the charger.
         * @param $link string the link of the video review.
         * @param $rating int the rating of the charger.
         * @param $lng double the longitude of the charger.
         * @param $lat double the latitude of the charger.
         *
         * @return boolean true if it was inserted into the reviews false if it was inserted into chargers not in
         *     system.
         */
        public static function addReview($name, $email, $charger_name, $address, $link, $rating, $lng, $lat, $type) {
            if ($type > 1 || $type < 0) {
                throw new InvalidArgumentException("Invalid type");
            }
            if ($rating > 10 || $rating < 0) {
                throw new InvalidArgumentException("Invalid rating scale");
            }
            $location = new Location($lat, $lng);
            //Gets the location box for a 0.1 mile distance to each edge
            $box = $location->getBox(0.1);
            if ($type == 0) {
                $type = "super_charger";
            } else {
                $type = "destination_charger";
            }
            $query = new CustomQuery("SELECT charger_id FROM " . $type .
                " INNER JOIN charger USING (charger_id) WHERE lat > " . $box->getMinLat() .
                " AND lat < " . $box->getMaxLat() . " AND lng > " . $box->getMinLng() . " AND lng < " .
                $box->getMaxLng());
            try {
                $result = DBQuerrier::checkExistsQuery($query);
                if (!$result) {
                    self::addToChargersNotInSystem($name, $email, $charger_name, $address, $link, $rating, $lng, $lat,
                        $type);
                    return false;
                } else {
                    $row = @ mysqli_fetch_array($result);
                    $charger_id = $row['charger_id'];
                    $query = new InsertQuery("review");
                    $query->addParamAndValues("charger_id", DBValue::nonStringValue($charger_id));
                    $query->addParamAndValues("link", DBValue::stringValue($link));
                    $query->addParamAndValues("reviewer", DBValue::stringValue($name));
                    $query->addParamAndValues("email", DBValue::stringValue($email));
                    $query->addParamAndValues("rating", DBValue::nonStringValue($rating));
                    $query->addParamAndValues("review_date", DBValue::nonStringValue("CURRENT_DATE"));
                    DBQuerrier::defaultQuery($query);
                    return true;
                }
            } catch (SQLNonUniqueValueException $exception) {
                self::addToChargersNotInSystem($name, $email, $charger_name, $address, $link, $rating, $lng, $lat,
                    $type);
                return false;
            }
        }

        private static function addToChargersNotInSystem($name, $email, $charger_name, $address, $link, $rating, $lng,
                                                         $lat,
                                                         $type) {
            $query = new InsertQuery("charger_not_in_system");
            $query->addParamAndValues("name", DBValue::stringValue($name));
            $query->addParamAndValues("email", DBValue::stringValue($email));
            $query->addParamAndValues("charger_name", DBValue::stringValue($charger_name));
            $query->addParamAndValues("address", DBValue::stringValue($address));
            $query->addParamAndValues("link", DBValue::stringValue($link));
            $query->addParamAndValues("rating", DBValue::nonStringValue($rating));
            $query->addParamAndValues("lng", DBValue::nonStringValue($lng));
            $query->addParamAndValues("lat", DBValue::nonStringValue($lat));
            $query->addParamAndValues("type", DBValue::stringValue($charger_name));
            DBQuerrier::defaultInsert($query);
        }
    }