<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 3:16 PM
     */
    interface ILocation {
        /**
         * Gets the latitude of this location.
         *
         * @return float latitude of this location.
         */
        public function getLat();

        /**
         * Gets the longitude of this location.
         *
         * @return float the longitude of this location.
         */
        public function getLng();

        /**
         * Gets the distance between this point and the given location.
         *
         * @param $location ILocation the location the distance is being calculated for.
         * @return float the distance between this point and the given point.
         */
        public function getDistance($location);

        /**
         * Gets the box of coordinates that are within the given radius of this location.
         *
         * @param $radius double the radius in miles.
         * @return LocationBox
         */
        public function getBox($radius);

        /**
         * Determines if this location is within the given radius of the given location.
         *
         * @param $location ILocation the location being compared to.
         * @param $radius double the radius checking distance between the two points against.
         * @return bool whether this location is within $radius miles of $location.
         */
        public function withinDistance($location, $radius);

        /**
         * Adds the location parameters to the given query with the appropriate values.
         *
         * @param $query IParameterValueQuery the query with parameter values being added.
         * @return IParameterValueQuery
         */
        public function addParamValues($query);
    }