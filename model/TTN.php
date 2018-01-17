<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 1:41 PM
     */
    class TTN {
        private $id;
        private $reviews;

        public function __construct($id, $reviews) {
            if ($id === null) {
                throw new InvalidArgumentException("Id cannot be null");
            }
            $this->id = $id;
            $this->reviews = $reviews;
        }

        /**
         * Gets the reviews that appear in this TTN.
         *
         * @return IChargerReview[] the reviews in this TTN.
         */
        public function getReviews() {
            if ($this->reviews === null) {
                $this->reviews = $this->getReviewsFromDB();
            }
            return $this->reviews;
        }

        public function getId() {
            return $this->id;
        }

        private function getReviewsFromDB() {
            $query = new SelectQuery("reviews_in_ttn", "review_id");
            $query->where(Where::whereEqualValue("ttn_id", DBValue::nonStringValue($this->id)));
            $result = DBQuerrier::defaultQuery($query);
            $reviews = array();
            while ($row = @ mysqli_fetch_array($result)) {
                array_push($reviews, new ChargerReview($row['review'], null, null, null, null, null, null, null));
            }
            return $reviews;
        }
    }