<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 3:33 PM
     */
    interface ILocationBox {
        /**
         * Returns the minimum longitude coordinate for this location box.
         *
         * @return float the minimum longitude coordinate.
         */
        public function getMinLng();

        /**
         * Returns the maximum longitude coordinate for this location box.
         *
         * @return float the maximum longitude coordinate.
         */
        public function getMaxLng();

        /**
         * Returns the minimum latitude coordinate for this location box.
         *
         * @return float the minimum latitude coordinate.
         */
        public function getMinLat();

        /**
         * Returns the maximum latitude coordinate for this location box.
         *
         * @return float the maximum latitude coordinate.
         */
        public function getMaxLat();

        /**
         * determines if the given location is in this box.
         *
         * @param $location ILocation the location being checked.
         * @return bool whether the given location is in the box.
         */
        public function inBox($location);
    }