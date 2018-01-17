<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/IPage.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/21/17
     * Time: 8:56 AM
     */
    class ADecoratorPage implements IPage {
        /**
         * @var IPage
         */
        protected $page;

        protected function __construct($page) {
            if ($page === null) {
                throw new InvalidArgumentException("Page cannot be null");
            }
            $this->page = $page;
        }

        /**
         * Prints the html page that this page represents.
         *
         * @return string the hmtl formatted page.
         */
        public function generateHtml() {
            return $this->page->generateHtml();
        }

        /**
         * Adds the given html to the bottom of the head.
         *
         * @param $html string the html being added to the bottom of the head tag.
         * @param $placement int top = 0 bottom = 1
         * @return void
         */
        public function addToHead($html, $placement) {
            $this->page->addToHead($html, $placement);
        }

        /**
         * Adds the given html to the bottom of the body.
         *
         * @param $html string the html being added to the bottom of the body tag.
         * @param $placement int top = 0 bottom = 1
         * @return void
         */
        public function addToBody($html, $placement) {
            $this->page->addToBody($html, $placement);
        }

        /**
         * Initializes all imports for Bootstrap on the page.
         *
         * @return void
         */
        public function initializeBootstrap() {
            $this->page->initializeBootstrap();
        }

        /**
         * Initializes all imports for JQuery on the page.
         *
         * @return void
         */
        public function initializeJQuery() {
            $this->page->initializeJQuery();
        }

        /**
         * Initializes all imports for Selectors on the page.
         *
         * @return void
         */
        public function initializeSelectors() {
            $this->page->initializeSelectors();
        }

        /**
         * Adds a style sheet to this page.
         *
         * @param $filePath string the path to the style sheet.
         * @return void
         */
        public function addStyleSheet($filePath) {
            $this->page->addStyleSheet($filePath);
        }

        /**
         * Adds a javascript file to this page.
         *
         * @param $filePath string the path to the javascript file.
         * @return void
         */
        public function addJSFile($filePath) {
            $this->page->addJSFile($filePath);
        }

        /**
         * Adds style sheets to this page.
         *
         * @param $filePaths string[] file paths of style sheets.
         * @return void
         */
        public function addStyleSheets($filePaths) {
            $this->page->addStyleSheets($filePaths);
        }

        /**
         * Adds javascript files to this pages.
         *
         * @param $filePaths string[] file paths of javascript files.
         * @return void
         */
        public function addJSFiles($filePaths) {
            $this->page->addJSFiles($filePaths);
        }
    }