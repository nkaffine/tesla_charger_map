<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/IChargerReview.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 10:00 AM
     */
    abstract class AChargerReview implements IChargerReview {
        protected $superCharger;
        protected $id;
        protected $link;
        protected $reviewer;
        protected $email;
        protected $rating;
        protected $reviewDate;
        protected $ttnEpisode;

        public function __construct($id, $charger, $link, $reviewer, $email, $rating, $reviewDate,
                                    $ttnEpisode) {
            if ($id === null) {
                throw new InvalidArgumentException("Id cannot be null");
            }
            $this->id = $id;
            $this->superCharger = $charger;
            $this->link = $link;
            $this->reviewer = $reviewer;
            $this->email = $email;
            $this->rating = $rating;
            $this->reviewDate = $reviewDate;
            $this->ttnEpisode = $ttnEpisode;
        }

        /**
         * Gets the rating for this charger.
         *
         * @return int the rating for this charger.
         */
        public function getRating() {
            if ($this->rating === null) {
                $this->rating = $this->getRatingFromDB();
            }
            return $this->rating;
        }

        /**
         * Gets the reviewer for this charger review.
         *
         * @return string the reviewer for this charger.
         */
        public function getReviewer() {
            if ($this->reviewer === null) {
                $this->reviewer = $this->getReviewerFromDB();
            }
            return $this->reviewer;
        }

        /**
         * Gets the email for this charger review.
         *
         * @return string the email of the reviewer.
         */
        public function getEmail() {
            if ($this->email === null) {
                $this->email = $this->getEmailFromDB();
            }
            return $this->email;
        }

        /**
         * Gets the id of this charger review.
         *
         * @return int the review id.
         */
        public function getId() {
            return $this->id;
        }

        /**
         * Gets the link of this charger review.
         *
         * @return string the charger link.
         */
        public function getLink() {
            if ($this->link === null) {
                $this->link = $this->getLinkFromDB();
            }
            return $this->link;
        }

        /**
         * Gets the review data of this charger.
         *
         * @return string the date of this charger.
         */
        public function getReviewDate() {
            if ($this->reviewDate === null) {
                $this->reviewDate = $this->getReviewDateFromDB();
            }
            return $this->reviewDate;
        }

        /**
         * Gets the ttn episode associated with this charger review
         *
         * @return mixed
         */
        public function getTtnEpisode() {
            if ($this->ttnEpisode === null) {
                $this->ttnEpisode = $this->getTtnEpisodeFromDB();
            }
            return $this->ttnEpisode;
        }

        /**
         * Gets the rating of this review from the database.
         *
         * @return int the rating of this review from the database.
         */
        private function getRatingFromDB() {
            $query = new SelectQuery("reviews", "rating");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['rating'];
        }

        /**
         * Gets the reviewer from the database.
         *
         * @return string the reviewer from the database.
         */
        private function getReviewerFromDB() {
            $query = new SelectQuery("reviews", "reviewer");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['reviewer'];
        }

        /**
         * Gets the email of the reviewer for this review from the database.
         *
         * @return string the email of the reviewer of this review from the database.
         */
        private function getEmailFromDB() {
            $query = new SelectQuery("reviews", "email");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['email'];
        }

        /**
         * Gets the link to the video review from the database.
         *
         * @return string the link to the video review from the database.
         */
        private function getLinkFromDB() {
            $query = new SelectQuery("reviews", "link");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['link'];
        }

        /**
         * Gets the review date of this review from the database.
         *
         * @return string the review date of the review from the database.
         */
        private function getReviewDateFromDB() {
            $query = new SelectQuery("reviews", "review_date");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return $row['review_date'];
        }

        /**
         * Gets the ttn episode that this review appears in from the database.
         *
         * @return TTN the tesla time news episode the video appears in.
         */
        private function getTtnEpisodeFromDB() {
            $query = new SelectQuery("reviews", "review_id", "ttn_id");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            if ($row['id'] === null) {
                return null;
            } else {
                return new TTN($row['id'], null);
            }
        }

        /**
         * Gets the charger that this review is for.
         *
         * @return Charger the charger this review is for.
         */
        public function getCharger() {
            if ($this->superCharger === null) {
                $this->superCharger = $this->getSuperchargerFromDB();
            }
            return $this->superCharger;
        }

        /**
         * Gets the charger for this review from the database.
         *
         * @return Charger the charger that this review is for.
         */
        private function getSuperchargerFromDB() {
            $query = new SelectQuery("reviews", "charger_id");
            $query->where(Where::whereEqualValue("review_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::queryUniqueValue($query);
            $row = @ mysqli_fetch_array($result);
            return ChargerFactory::superChargerById($row['charger_id']);
        }
    }