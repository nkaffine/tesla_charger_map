<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/ILocation.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/location/LocationBox.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 3:18 PM
     */
    class Location implements ILocation {
        private $lat;
        private $lng;
        const EARTH_CIRCUMFERENCE = 24901;

        /**
         * Location constructor.
         *
         * @param $lat float the latitude of the location.
         * @param $lng float the longitude of the location.
         */
        public function __construct($lat, $lng) {
            if ($lat === null || $lng === null) {
                throw new InvalidArgumentException("Lat and Lng must not be null");
            }
            $this->lat = $lat;
            $this->lng = $lng;
        }

        /**
         * Gets the latitude of this location.
         *
         * @return float latitude of this location.
         */
        public function getLat() {
            return $this->lat;
        }

        /**
         * Gets the longitude of this location.
         *
         * @return float the longitude of this location.
         */
        public function getLng() {
            return $this->lng;
        }

        /**
         * Gets the distance between this point and the given location.
         *
         * @param $location ILocation the location the distance is being calculated for.
         * @return float the distance between this point and the given point.
         */
        public function getDistance($location) {
            // Change in latitude
            $dLat = abs($this->lat - $location->getLat());
            // Change in Longitude
            $dLon = abs($this->lng - $location->getLng());
            $distLat = ($dLat / (2 * M_PI * (180 / M_PI))) * self::EARTH_CIRCUMFERENCE;
            $latC = cos($this->lat * (M_PI / 180)) * self::EARTH_CIRCUMFERENCE;
            $distLon = ($dLon / (2 * M_PI * (180 / M_PI))) * $latC;
            return sqrt(($distLat * $distLat) + ($distLon * $distLon));
        }

        /**
         * Gets the box of coordinates that are within the given radius of this location.
         *
         * @param $radius double the radius in miles.
         * @return LocationBox
         */
        public function getBox($radius) {
            // Converting the distance given to change in degrees in the lat
            // Conversion from radians to degrees is radians * 180/Pi
            $r = (($radius / self::EARTH_CIRCUMFERENCE) * 2 * M_PI * (180 / M_PI));
            // Calculating the min and max lattitude in the distance box in degrees
            $minLat = $this->lat - $r;
            $maxLat = $this->lat + $r;
            // Calculating the circumfrence of the circle of the given lattitude
            // Conversion from degrees to radians is degrees * pi/180
            $latC = cos($minLat * (M_PI / 180)) * self::EARTH_CIRCUMFERENCE;
            // Calculating the change in radians of the longitude
            $latR = (($radius / $latC) * 2 * M_PI * (180 / M_PI));
            // Calculating the min and max longitude
            $minLng = $this->lng - $latR;
            $maxLng = $this->lng + $latR;
            return new LocationBox($minLng, $minLat, $maxLng, $maxLat);
        }

        /**
         * Determines if this location is within the given radius of the given location.
         *
         * @param $location ILocation the location being compared to.
         * @param $radius double the radius checking distance between the two points against.
         * @return bool whether this location is within $radius miles of $location.
         */
        public function withinDistance($location, $radius) {
            return $this->getDistance($location) <= $radius;
        }

        /**
         * Adds the location parameters to the given query with the appropriate values.
         *
         * @param $query IParameterValueQuery the query with parameter values being added.
         * @return void
         */
        public function addParamValues($query) {
            $query->addParamAndValues("lat", DBValue::nonStringValue($this->getLat()));
            $query->addParamAndValues("lng", DBValue::nonStringValue($this->getLng()));
        }
    }