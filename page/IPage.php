<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/Page.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/17/17
     * Time: 7:26 PM
     */
    interface IPage {
        /**
         * Prints the html page that this page represents.
         *
         * @return string the hmtl formatted page.
         */
        public function generateHtml();

        /**
         * Adds the given html to the bottom of the head.
         *
         * @param $html string the html being added to the bottom of the head tag.
         * @param $placement int top = 0 bottom = 1
         * @return void
         */
        public function addToHead($html, $placement);

        /**
         * Adds the given html to the bottom of the body.
         *
         * @param $html string the html being added to the bottom of the body tag.
         * @param $placement int top = 0 bottom = 1
         * @return void
         */
        public function addToBody($html, $placement);

        /**
         * Initializes all imports for Bootstrap on the page.
         *
         * @return void
         */
        public function initializeBootstrap();

        /**
         * Initializes all imports for JQuery on the page.
         *
         * @return void
         */
        public function initializeJQuery();

        /**
         * Initializes all imports for Selectors on the page.
         *
         * @return void
         */
        public function initializeSelectors();

        /**
         * Adds a style sheet to this page.
         *
         * @param $filePath string the path to the style sheet.
         * @return void
         */
        public function addStyleSheet($filePath);

        /**
         * Adds a javascript file to this page.
         *
         * @param $filePath string the path to the javascript file.
         * @return void
         */
        public function addJSFile($filePath);

        /**
         * Adds style sheets to this page.
         *
         * @param $filePaths string[] file paths of style sheets.
         * @return void
         */
        public function addStyleSheets($filePaths);

        /**
         * Adds javascript files to this pages.
         *
         * @param $filePaths string[] file paths of javascript files.
         * @return void
         */
        public function addJSFiles($filePaths);
    }