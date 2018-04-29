<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/16/18
     * Time: 12:58 AM
     */
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/BaseCleanser.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/BaseCleanser.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/LowerCaseChars.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/LowerCaseChars.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/UpperCaseChars.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/UpperCaseChars.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/removing/NumericChars.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/removing/NumericChars.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/inputcleansing/replacing/InputReplacer.php");
//    require_once("/home/zaccat2/chargerCronJobs/inputcleansing/replacing/InputReplacer.php");

    class InputCleanserFactory {
        /**
         * Returns an input cleanser that only accepts alphanumeric characters.
         *
         * @param $maxLength int the max length of the allowed string
         * @return IInputCleanser
         */
        public static function alphaNumericCharacters($maxLength) {
            $c1 = new BaseCleanser($maxLength);
            $c2 = new LowerCaseChars($c1);
            $c3 = new UpperCaseChars($c2);
            $c3 = new CustomCleanser($c3, "-");
            return new NumericChars($c3);
        }

        public static function dataBaseFriendly() {
            $c1 = new InputReplacer(256);
            $c1->addReplacePair("'", "\'");
            return $c1;
        }
    }