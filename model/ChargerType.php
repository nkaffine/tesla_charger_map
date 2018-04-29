<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 10:17 PM
     */

    /**
     * Class ChargerType that represents the types of chargers.
     */
    class ChargerType {
        const AllChargers = 0;
        const SuperCharger = 1;
        const DestinationCharger = 2;

        /**
         * Checks if the two charger types are the same.
         *
         * @param $type1 int the first charger type.
         * @param $type2 int the second charger type.
         * @return bool whether the two charger types are the same.
         */
        public static function sameType($type1, $type2) {
            return $type1 === $type2;
        }
    }