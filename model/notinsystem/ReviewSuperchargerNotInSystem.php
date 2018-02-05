<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/db/DBQuerrier.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/ChargerReview.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/update/UpdateQuery.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 2/2/18
     * Time: 1:41 PM
     */
    class ReviewSuperchargerNotInSystem {
        private $review_id;
        private $name;
        private $email;
        private $location;
        private $address;
        private $stalls;
        private $link;
        private $rating;
        private $lng;
        private $lat;
        private $status;
        private $openDate;
        private $checked;
        private $allInitialized;
        private $reviewDate;

        /**
         * ReviewSuperchargerNotInSystem constructor.
         *
         * @param $review_id int the id of the review of the supercharger not in the system.
         * @param $name string the name of the person who is reviewing the supercharger
         * @param $email string the email of the person who is reviewing the supercharger.
         * @param $location string the location of the supercharger the person is reviewing.
         * @param $address string the address of the supercharger the person is reviewing.
         * @param $stalls int the number of stalls at the supercharger.
         * @param $link string the link to the video review of the supercharger.
         * @param $rating int the rating of the supercharger.
         * @param $lng float the longitude of the supercharger.
         * @param $lat float the latitude of the supercharger.
         * @param $status int the status of the supercharger.
         * @param $openDate string the date the charger was opened.
         * @param $checked boolean whether or not the supercharger review has been manually checked.
         */
        public function __construct($review_id, $name, $email, $location, $address, $stalls, $link, $rating, $lng, $lat,
                                    $status, $openDate, $checked) {
            if ($review_id === null) {
                throw new InvalidArgumentException("Review id cannot be null");
            }

            $this->review_id = $review_id;
            $this->name = $name;
            $this->email = $email;
            $this->location = $location;
            $this->address = $address;
            $this->stalls = $stalls;
            $this->link = $link;
            $this->rating = $rating;
            $this->lng = $lng;
            $this->lat = $lat;
            $this->checked = $checked;
            $this->allInitialized = false;
            $this->reviewDate = null;
        }

        /**
         * Gets the id of this review of a supercharger not in the system.
         *
         * @return int the id of this review of a supercharger not in the system.
         */
        public
        function getReviewId() {
            return $this->review_id;
        }

        /**
         * Gets the name of the reviewer of this review.
         *
         * @return string the name of this reviewer.
         */
        public
        function getName() {
            if ($this->name === null) {
                $this->initializeValues();
            }
            return $this->name;
        }

        /**
         * Gets the email of the reviewer for this review.
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
         * Gets the location of the supercharger this review is for.
         *
         * @return string the location of the supercharger.
         */
        public function getLocation() {
            if ($this->location === null) {
                $this->initializeValues();
            }
            return $this->location;
        }

        /**
         * Gets the address of the supercharger this review is for.
         *
         * @return string the address for the review of this supercharger.
         */
        public function getAddress() {
            if ($this->address === null) {
                $this->initializeValues();
            }
            return $this->address;
        }

        public function getStalls() {
            if ($this->stalls === null) {
                $this->initializeValues();
            }
            return $this->stalls;
        }

        /**
         * Gets the link of the video for this review.
         *
         * @return string the link for the video of this review.
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
         * @return int the rating for this review.
         */
        public function getRating() {
            if ($this->rating === null) {
                $this->initializeValues();
            }
            return $this->rating;
        }

        /**
         * Gets the longitude of the supercharger this review is for.
         *
         * @return float the longitude for the supercharger this review is for.
         */
        public function getLng() {
            if ($this->lng === null) {
                $this->initializeValues();
            }
            return $this->lng;
        }

        /**
         * Gets the latitude of the supercharger this review is for.
         *
         * @return float the latitude of the supercharger this review is for.
         */
        public function getLat() {
            if ($this->lat === null) {
                $this->initializeValues();
            }
            return $this->lat;
        }

        /**
         * Gets the status of the supercharger this review is for.
         *
         * @return int the status of the supercharger this review is for.
         */
        public function getStatus() {
            if ($this->status === null) {
                $this->initializeValues();
            }
            return $this->status;
        }

        /**
         * Gets the open data of the supercharger this review is for.
         *
         * @return string teh open date for the supercharger this review is for.
         */
        public function getOpenDate() {
            if ($this->openDate === null) {
                $this->initializeValues();
            }
            return $this->openDate;
        }

        /**
         * Gets whether this review has been manually checked.
         *
         * @return bool whether this review has been manually checked.
         */
        public function isChecked() {
            if ($this->checked === null) {
                $this->initializeValues();
            }
            return $this->checked;
        }

        /**
         * Gets the review date of this review
         *
         * @return string the review date of this review.
         */
        public function getReviewDate() {
            if ($this->reviewDate === null) {
                $this->initializeValues();
            }
            return $this->reviewDate;
        }

        /**
         * Initializes all of the values of this review from the database.
         *
         * @return void
         */
        private function initializeValues() {
            if ($this->allInitialized === false) {
                $query =
                    new SelectQuery("sc_not_in_system", "name", "email", "location", "address", "stalls", "link",
                        "rating", "lng", "lat", "status", "open_date", "checked", "review_date");
                $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->review_id)));
                $result = DBQuerrier::queryUniqueValue($query);
                $row = @ mysqli_fetch_array($result);
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->location = $row['location'];
                $this->address = $row['address'];
                $this->stalls = $row['stalls'];
                $this->link = $row['link'];
                $this->rating = $row['rating'];
                $this->lng = $row['lng'];
                $this->lat = $row['lat'];
                $this->status = $row['status'];
                $this->openDate = $row['open_date'];
                $this->checked = $row['checked'];
                $this->reviewDate = $row['review_date'];
                $this->allInitialized = true;
            }
        }

        /**
         * Gets all of the reviews for superchargers that are not in the system.
         *
         * @return ReviewSuperchargerNotInSystem[] list of all reviews for superchargers not in the system.
         */
        public static function getAllSuperchargerReviewsNotInSystem() {
            $query = new SelectQuery("sc_not_in_system", "review_id", "name", "email", "location", "address", "stalls",
                "link", "rating", "lng", "lat", "status", "open_date", "checked");
            $query->where(Where::whereEqualValue("checked", DBValue::nonStringValue(0)));
            $result = DBQuerrier::defaultQuery($query);
            $chargers = array();
            while ($row = @ mysqli_fetch_array($result)) {
                array_push($chargers,
                    new ReviewSuperchargerNotInSystem($row['review_id'], $row['name'], $row['email'], $row['location'],
                        $row['address'], $row['stalls'], $row['link'], $row['rating'], $row['lng'], $row['lat'],
                        $row['status'], $row['open_date'], $row['checked']));
            }
            return $chargers;
        }

        /**
         * Assigns this review to a supercharger.
         *
         * @param $charger SuperCharger the charger that this review is being assigned to.
         * @return void
         */
        public function assignToSuperCharger($charger) {
            $this->initializeValues();
            ChargerReview::newReview($charger, $this->getLink(), $this->getName(), $this->getEmail(),
                $this->getRating(), $this->getReviewDate());
            $this->markAsChecked();
        }

        /**
         * Marks this review as being checked.
         *
         * @return void
         */
        private function markAsChecked() {
            $query = new UpdateQuery("sc_not_in_system");
            $query->addParamAndValue("checked", DBValue::nonStringValue(1));
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->getReviewId())));
            DBQuerrier::defaultUpdate($query);
        }
    }