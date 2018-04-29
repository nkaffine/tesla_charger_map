<?php

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 3/14/18
     * Time: 11:23 PM
     */

    /**
     * Interface InputCleanserFactory represents a cleanser of input.
     */
    interface IInputCleanser {
        /**
         * Cleanses the input based on the specifications of this cleanser.
         *
         * @param $input string the input being cleansed.
         * @return string the cleansed input.
         */
        public function cleanse($input);
    }