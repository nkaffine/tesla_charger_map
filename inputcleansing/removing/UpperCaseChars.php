<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/15/18
     * Time: 12:01 AM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/CustomCleanserWrapper.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/CustomCleanserWrapper.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/CustomCleanser.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/CustomCleanser.php");

    class UpperCaseChars extends CustomCleanserWrapper {
        /**
         * ACleanser constructor.
         *
         * @param $cleanser IInputCleanser the inner input cleanser.
         */
        public function __construct($cleanser) {
            $this->customCleanser = new CustomCleanser($cleanser, "A-Z");
        }
    }