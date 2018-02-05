<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/ChargerReview.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/update/UpdateQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/2/18
     * Time: 1:41 PM
     */
    class ReviewDestinationChargerNotInSystem {
        private $review_id;
        private $name;
        private $email;
        private $location;
        private $address;
        private $link;
        private $rating;
        private $lng;
        private $lat;
        private $checked;
        private $reviewDate;
        private $allInitialized;

        public function __construct($review_id, $name, $email, $location, $address, $link, $rating, $lng, $lat,
                                    $checked) {
            if ($review_id === null) {
                throw new InvalidArgumentException("Review id cannot be null");
            }
            $this->review_id = $review_id;
            $this->name = $name;
            $this->email = $email;
            $this->location = $location;
            $this->address = $address;
            $this->link = $link;
            $this->rating = $rating;
            $this->lng = $lng;
            $this->lat = $lat;
            $this->checked = $checked;
            $this->allInitialized = false;
            $this->reviewDate = null;
        }

        /**
         * Gets the review id of this review.
         *
         * @return int the id of this review.
         */
        public function getReviewId() {
            return $this->review_id;
        }

        /**
         * Gets the name of the reviewer.
         *
         * @return string the name of the reviewer.
         */
        public function getName() {
            if ($this->name === null) {
                $this->initializeValues();
            }
            return $this->name;
        }

        /**
         * Gets the email of the reviewer.
         *
         * @return string the email of the reviewer.
         */
        public function getEmail() {
            if ($this->email === null) {
                $this->initializeValues();
            }
            return $this->email;
        }

        /**
         * Gets the location of the destination charger being reviewed.
         *
         * @return string the location of the destination charger being reviewed.
         */
        public function getLocation() {
            if ($this->location === null) {
                $this->initializeValues();
            }
            return $this->location;
        }

        /**
         * Gets the address of the destination charger being reviewed.
         *
         * @return string the address of the destination charger being reviewed.
         */
        public function getAddress() {
            if ($this->address === null) {
                $this->initializeValues();
            }
            return $this->address;
        }

        /**
         * Gets the link of the video for this review.
         *
         * @return string the link of the video for this review.
         */
        public function getLink() {
            if ($this->link === null) {
                $this->initializeValues();
            }
            return $this->link;
        }

        /**
         * Gets the rating for this review.
         *
         * @return int the rating of this review.
         */
        public function getRating() {
            if ($this->rating === null) {
                $this->initializeValues();
            }
            return $this->rating;
        }

        /**
         * Gets the longitude of the destination charger being reviewed.
         *
         * @return float the longitude of the destination charger being reviewed.
         */
        public function getLng() {
            if ($this->lng === null) {
                $this->initializeValues();
            }
            return $this->lng;
        }

        /**
         * Gets the latitude of the destination charger being reviewed.
         *
         * @return float the latitude of the destination charger being reviewed.
         */
        public function getLat() {
            if ($this->lat === null) {
                $this->initializeValues();
            }
            return $this->lat;
        }

        /**
         * Gets whether the review has already been checked.
         *
         * @return boolean whether the review has already been checked.
         */
        public function isChecked() {
            if ($this->checked === null) {
                $this->initializeValues();
            }
            return $this->checked;
        }

        /**
         * Gets the review date for this review.
         *
         * @return string the review date for this review.
         */
        public function getReviewDate() {
            if ($this->reviewDate === null) {
                $this->initializeValues();
            }
            return $this->reviewDate;
        }

        /**
         * Initializes the values for this review of a destination charger not in the system.
         *
         * @return void
         */
        private function initializeValues() {
            if ($this->allInitialized === false) {
                $query = new SelectQuery("dc_not_in_system", "name", "email", "location", "address", "link", "rating",
                    "lng", "lat", "checked");
                $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->review_id)));
                $result = DBQuerrier::queryUniqueValue($query);
                $row = @ mysqli_fetch_array($result);
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->location = $row['location'];
                $this->address = $row['address'];
                $this->link = $row['link'];
                $this->rating = $row['rating'];
                $this->lng = $row['lng'];
                $this->lat = $row['lat'];
                $this->checked = $row['checked'];
            }
        }

        /**
         * Gets all of the reviews for destination chargers that are not in the system.
         *
         * @return ReviewDestinationChargerNotInSystem[] list of all reviews for superchargers not in the system.
         */
        public static function getAllDestinationChargerReviewsNotInSystem() {
            $query = new SelectQuery("dc_not_in_system", "review_id", "name", "email", "location", "address", "link",
                "rating", "lng", "lat", "checked");
            $query->where(Where::whereEqualValue("checked", DBValue::nonStringValue(0)));
            $result = DBQuerrier::defaultQuery($query);
            $chargers = array();
            while ($row = @ mysqli_fetch_array($result)) {
                array_push($chargers,
                    new ReviewDestinationChargerNotInSystem($row['review_id'], $row['name'], $row['email'],
                        $row['location'], $row['address'], $row['link'], $row['rating'], $row['lng'], $row['lat'],
                        $row['checked']));
            }
            return $chargers;
        }

        /**
         * Assigns this review to the given destination charger.
         *
         * @param $charger DestinationCharger the charger for this review.
         * @return void
         */
        public function assignToDestinationCharger($charger) {
            $this->initializeValues();
            ChargerReview::newReview($charger, $this->getLink(), $this->getName(), $this->getEmail(),
                $this->getRating(), $this->getReviewDate());
            $this->markAsChecked();
        }

        /**
         * Marks this review as checked.
         *
         * @return void
         */
        public function markAsChecked() {
            $query = new UpdateQuery("dc_not_in_system");
            $query->addParamAndValue("checked", DBValue::nonStringValue(1));
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->getReviewId())));
            DBQuerrier::defaultUpdate($query);
        }
    }