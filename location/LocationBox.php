<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/ILocationBox.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 3:41 PM
     */
    class LocationBox implements ILocationBox {
        //Longitude is north and south
        //Latitude is east and west
        private $minLng;
        private $minLat;
        private $maxLng;
        private $maxLat;

        public function __construct($minLng, $minLat, $maxLng, $maxLat) {
            if ($minLng === null || $minLat === null || $maxLng === null || $maxLng === null) {
                throw new InvalidArgumentException("All values must not be null");
            }
            $this->minLng = $minLng;
            $this->maxLng = $maxLng;
            $this->minLat = $minLat;
            $this->maxLat = $maxLat;
        }

        /**
         * Returns the minimum longitude coordinate for this location box.
         *
         * @return float the minimum longitude coordinate.
         */
        public function getMinLng() {
            return $this->minLng;
        }

        /**
         * Returns the maximum longitude coordinate for this location box.
         *
         * @return float the maximum longitude coordinate.
         */
        public function getMaxLng() {
            return $this->maxLng;
        }

        /**
         * Returns the minimum latitude coordinate for this location box.
         *
         * @return float the minimum latitude coordinate.
         */
        public function getMinLat() {
            return $this->minLat;
        }

        /**
         * Returns the maximum latitude coordinate for this location box.
         *
         * @return float the maximum latitude coordinate.
         */
        public function getMaxLat() {
            return $this->maxLat;
        }

        /**
         * determines if the given location is in this box.
         *
         * @param $location ILocation the location being checked.
         * @return bool whether the given location is in the box.
         */
        public function inBox($location) {
            return $this->minLng <= $location->getLng() && $location->getLng() <= $this->maxLng &&
                $this->minLat <= $location->getLat() && $location->getLat() <= $this->maxLat;
        }
    }