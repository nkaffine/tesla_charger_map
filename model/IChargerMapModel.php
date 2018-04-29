<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 10:06 PM
     */

    /**
     * Interface IChargerMapModel representing the commands available for the a ChargerMapModel
     */
    interface IChargerMapModel {
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
         * @return array the JSON representation of the results of the request.
         */
        public function newChargerReview($name, $email, $locationName, $address, $link, $rating, $lng, $lat, $type);

        /**
         * Gets the superchargers of the given type in the given region.
         *
         * @param $chargerType int the type of chargers.
         * @param $chargerRegion int the region of the chargers.
         * @return array the JSON representation of the chargers from the request.
         */
        public function getChargers($chargerType, $chargerRegion);
    }