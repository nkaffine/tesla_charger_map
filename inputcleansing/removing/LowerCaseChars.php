<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 11:44 PM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/CustomCleanserWrapper.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/CustomCleanserWrapper.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/CustomCleanser.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/CustomCleanser.php");

    class LowerCaseChars extends CustomCleanserWrapper {

        /**
         * ACleanser constructor.
         *
         * @param $cleanser IInputCleanser the inner input cleanser.
         */
        public function __construct($cleanser) {
            $this->customCleanser = new CustomCleanser($cleanser, "a-z");
        }
    }