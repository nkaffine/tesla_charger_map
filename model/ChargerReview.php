<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/model/AChargerReview.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/insert/InsertIncrementQuery.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/mysql/querying/select/joinQueries/InnerJoin.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/SelectQuery.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/DBValue.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/Where.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/QueriedJoinTable.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/select/joinQueries/DBJoinTable.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 1/2/18
     * Time: 1:54 PM
     */
    class ChargerReview extends AChargerReview {
        public function __construct($id, $charger, $link, $reviewer, $email, $rating, $reviewDate, $ttnEpisode) {
            parent::__construct($id, $charger, $link, $reviewer, $email, $rating, $reviewDate, $ttnEpisode);
        }

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
        public static function newReview($charger, $link, $reviewer, $email, $rating, $reviewDate) {
            $query = new InsertIncrementQuery("reviews", "review_id");
            $query->addParamAndValues("charger_id", DBValue::nonStringValue($charger->getId()));
            $query->addParamAndValues("link", DBValue::stringValue($link));
            $query->addParamAndValues("reviewer", DBValue::stringValue($reviewer));
            $query->addParamAndValues("email", DBValue::stringValue($email));
            $query->addParamAndValues("rating", DBValue::nonStringValue($rating));
            if ($reviewDate !== null) {
                $query->addParamAndValues("review_date", DBValue::stringValue($reviewDate));
            }
            DBQuerrier::defaultInsert($query);
            return new ChargerReview($query->getPrimaryKeyValues()[0], $charger, null, null, null,
                null, null, null);
        }
    }