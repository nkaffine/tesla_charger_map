<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 8:48 AM
     */
    interface Charger {
        /**
         * Gets the average rating of this supercharger.
         *
         * @return double the average rating for this super charger.
         */
        public function getAverageRating();

        /**
         * Gets the reviews for this charger.
         *
         * @return IChargerReview[] the review for this charger.
         */
        public function getReviews();

        /**
         * Gets the name of this charger.
         *
         * @return string the name of the charger.
         */
        public function getName();

        /**
         * Gets teh id of this charger.
         *
         * @return int the id of this charger.
         */
        public function getId();

        /**
         * Returns the type of charger that this charger is.
         *
         * @return string the type of charger this charger is.
         */
        public function getChargerType();

        /**
         * Gets the location of this charger.
         *
         * @return ILocation the location of this supercharger.
         */
        public function getLocation();

        /**
         * Gets the address for this charger.
         *
         * @return string the address of the charger.
         */
        public function getAddress();
    }