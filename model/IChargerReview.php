<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 9:27 AM
     */
    interface IChargerReview {
        /**
         * Gets the rating for this charger.
         *
         * @return int the rating for this charger.
         */
        public function getRating();

        /**
         * Gets the reviewer for this charger review.
         *
         * @return string the reviewer for this charger.
         */
        public function getReviewer();

        /**
         * Gets the email for this charger review.
         *
         * @return string the email of the reviewer.
         */
        public function getEmail();

        /**
         * Gets the id of this charger review.
         *
         * @return int the review id.
         */
        public function getId();

        /**
         * Gets the link of this charger review.
         *
         * @return string the charger link.
         */
        public function getLink();

        /**
         * Gets the review data of this charger.
         *
         * @return string the date of this charger.
         */
        public function getReviewDate();

        /**
         * Gets the ttn episode associated with this charger review
         *
         * @return mixed
         */
        public function getTtnEpisode();

        /**
         * Creates a new review with the given information.
         *
         * @param $charger Charger the charger this review is for.
         * @param $link string the link for the video review.
         * @param $reviewer string the name of the person reviewing the charger.
         * @param $email string the email of the person reviewing the charger.
         * @param $rating int the rating for this review.
         * @param $reviewDate string the date of this review.
         * @return ChargerReview the new charger review.
         */
        public static function newReview($charger, $link, $reviewer, $email, $rating, $reviewDate);
    }