<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 10:23 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/IChargerMapModel.php");

    /**
     * Class ChargerMapModel that represents the model for the charger map.
     */
    class ChargerMapModel implements IChargerMapModel {

        /**
         * Creates a new charger review in the system.
         *
         * @param $name string the name of the person reviewing the charger.
         * @param $email string the email of the person reviewing the charger.
         * @param $locationName string the name of the location of the charger being reviewed.
         * @param $address string the address of the charger being reviewed.
         * @param $link string the youtube link of the charger being reviewed.
         * @param $rating int the rating of the charger out of 10.
         * @param $lng double the longitude of the charger being reviewed.
         * @param $lat double the latitude of the charger being reviewed.
         * @param $type int the type of charger that the charger being reviewed is.
         * @return void
         */
        public function newChargerReview($name, $email, $locationName, $address, $link, $rating, $lng, $lat, $type) {
            // TODO: Implement newChargerReview() method.
        }

        /**
         * Gets the superchargers of the given type in the given region.
         *
         * @param $chargerType int the type of chargers.
         * @param $chargerRegion int the region of the chargers.
         * @return array the JSON representation of the chargers from the request.
         */
        public function getChargers($chargerType, $chargerRegion) {
            // TODO: Implement getChargers() method.
        }
    }