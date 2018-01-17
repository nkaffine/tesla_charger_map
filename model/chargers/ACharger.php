<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/chargers/Charger.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/ChargerReview.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 8:54 AM
     */
    abstract class ACharger implements Charger {
        protected $id;
        protected $name;
        protected $location;
        protected $reviews;
        protected $address;


        /**
         * ACharger constructor.
         *
         * @param $id int the id of the charger.
         * @param $name string the name of the charger.
         * @param $location ILocation the location of the charger.
         * @param $reviews IChargerReview[] the reviews for this charger.
         * @param $address string the address for this charger.
         */
        public function __construct($id, $name, $location, $reviews, $address) {
            if ($id === null) {
                throw new InvalidArgumentException("Id cannot be null");
            }
            $this->id = $id;
            $this->name = $name;
            $this->location = $location;
            $this->reviews = $reviews;
            $this->address = $address;
        }

        /**
         * Gets the average rating of this charger.
         *
         * @return double the average rating for this super charger.
         */
        public function getAverageRating() {
            $reviews = $this->getReviews();
            $isNull = true;
            foreach ($reviews as $review) {
                $isNull = $isNull && $review->getRating() == null;
            }
            if ($isNull) {
                return null;
            } else {
                $count = 0;
                $sum = 0;
                foreach ($reviews as $review) {
                    if ($review->getRating() !== null) {
                        $count++;
                        $sum += $review->getRating();
                    }
                }
                return round(($sum / $count) * 10) / 10;
            }
        }

        /**
         * Gets the reviews for this charger.
         *
         * @return IChargerReview[] the reviews for this charger.
         */
        public function getReviews() {
            if ($this->reviews === null) {
                $this->reviews = $this->getReviewsFromDB();
            }
            return $this->reviews;
        }

        /**
         * Gets the name of this charger.
         *
         * @return string the name of the charger.
         */
        public function getName() {
            if ($this->name === null) {
                $this->name = $this->getNameFromDB();
            }
            return $this->name;
        }

        /**
         * Gets teh id of this charger.
         *
         * @return int the id of this charger.
         */
        public function getId() {
            return $this->id;
        }

        /**
         * Returns the type of charger that this charger is.
         *
         * @return string the type of charger this charger is.
         */
        abstract public function getChargerType();

        /**
         * Gets the location of this charger.
         *
         * @return ILocation the location of this supercharger.
         */
        public function getLocation() {
            if ($this->location === null) {
                $this->location = $this->getLocationFromDB();
            }
            return $this->location;
        }

        /**
         * @return IChargerReview[]
         */
        private function getReviewsFromDb() {
            $query = new SelectQuery("reviews", "link", "reviewer", "email", "rating", "review_date", "review_id");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::defaultQuery($query);
            $reviews = array();
            while ($row = @ mysqli_fetch_array($result)) {
                $review = new ChargerReview($row['review_id'], $this, $row['link'], $row['reviewer'], $row['email'],
                    $row['rating'], $row['review_date'], null);
                array_push($reviews, $review);
            }
            return $reviews;
        }

        /**
         * Gets the name of this charger from the database.
         *
         * @return string the name of the supercharger from the database.
         */
        private function getNameFromDB() {
            $query = new SelectQuery("chargers", "name");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['name'];
        }

        /**
         * Gets the location of the charger from the location.
         *
         * @return ILocation the location of the charger from the database.
         */
        private function getLocationFromDB() {
            $query = new SelectQuery("charges", "lng", "lat");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return new Location($row['lat'], $row['lng']);
        }

        /**
         * @param $link
         * @param $reviewer
         * @param $email
         * @param $rating
         * @param $reviewDate
         */
        public function newReview($link, $reviewer, $email, $rating, $reviewDate) {
            $review = ChargerReview::newReview($this, $link, $reviewer, $email, $rating, $reviewDate);
            if ($this->reviews !== null) {
                array_push($this->reviews, $review);
            }
        }

        /**
         * Gets the address for this charger.
         *
         * @return string the address of the charger.
         */
        public function getAddress() {
            if ($this->address === null) {
                $this->address = $this->getAddressFromDB();
            }
            return $this->address;
        }


        /**
         * Gets the address of this charger from the database.
         *
         * @return string the address of this charger from the database.
         */
        private function getAddressFromDB() {
            $query = new SelectQuery("chargers", "address");
            $query->where(Where::whereEqualValue("charger_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['address'];
        }
    }