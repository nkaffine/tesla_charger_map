<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/IPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/Page.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/17/17
     * Time: 7:18 PM
     */
    abstract class APage implements IPage {
        protected $htmlHead;
        protected $htmlBody;

        protected function __construct($title) {
            $this->htmlHead = "";
            $this->htmlBody = "";
            $this->setTitle($title);
        }

        protected function generateHtmlHead() {
            return "<head>" . $this->htmlHead . "</head>";
        }

        protected function generateHtmlBody() {
            return "<body>" . $this->htmlBody . "</body>";
        }

        /**
         * Prints the html page that this page represents.
         *
         * @return string the hmtl formatted page.
         */
        public function generateHtml() {
            return "<!DOCTYPE HTML><html lang=\"en\">" . $this->generateHtmlHead() . $this->generateHtmlBody() .
                "</html>";
        }

        /**
         * Initializes all imports for Bootstrap on the page.
         *
         * @return void
         */
        public function initializeBootstrap() {
            $this->addToHead("<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">",
                Page::BOTTOM);
            $this->addToHead("<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>",
                Page::BOTTOM);
            $this->addToHead("<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">",
                Page::BOTTOM);
        }

        /**
         * Initializes all imports for JQuery on the page.
         *
         * @return void.
         */
        public function initializeJQuery() {
            $this->addToHead("<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>",
                Page::BOTTOM);
        }

        /**
         * Initializes all imports for Selectors on the page.
         *
         * @return void
         */
        public function initializeSelectors() {
            $this->addToHead("<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css\">",
                Page::BOTTOM);
            $this->addToHead("<script src=\"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js\"></script>",
                Page::BOTTOM);
        }

        /**
         * Adds a javascript file to this page.
         *
         * @param $filePath string the path to the javascript file.
         * @return void
         */
        public function addJSFile($filePath) {
            $this->addToHead("<script src=\"" . $filePath . "\"></script>", Page::BOTTOM);
        }

        /**
         * Adds a style sheet to this page.
         *
         * @param $filePath string the path to the style sheet.
         * @return void
         */
        public function addStyleSheet($filePath) {
            $this->addToHead("<link rel=\"stylesheet\" href=\"" . $filePath . "\">",
                Page::BOTTOM);
        }

        /**
         * Adds the given html to the bottom of the head.
         *
         * @param $html string the html being added to the bottom of the head tag.
         * @param $placement int top = 0 bottom = 1
         * @return void
         */
        public function addToHead($html, $placement) {
            if ($placement == Page::BOTTOM) {
                $this->htmlHead .= $html;
            } else if ($placement == Page::TOP) {
                $this->htmlHead = $html . $this->htmlHead;
            } else {
                throw new InvalidArgumentException("Invalid placement value");
            }
        }

        /**
         * Adds the given html to the bottom of the body.
         *
         * @param $html string the html being added to the bottom of the body tag.
         * @param $placement int top = 0 bottom = 1
         * @return void
         */
        public function addToBody($html, $placement) {
            if ($placement == Page::BOTTOM) {
                $this->htmlBody .= $html;
            } else if ($placement == Page::TOP) {
                $this->htmlBody = $html . $this->htmlBody;
            } else {
                throw new InvalidArgumentException("Invalid placement value");
            }

        }

        /**
         * Adds javascript files to this pages.
         *
         * @param $filePaths string[] file paths of javascript files.
         * @return void
         */
        public function addJSFiles($filePaths) {
            foreach ($filePaths as &$filePath) {
                $this->addJSFile($filePath);
            }
        }

        /**
         * Adds style sheets to this page.
         *
         * @param $filePaths string[] file paths of style sheets.
         * @return void
         */
        public function addStyleSheets($filePaths) {
            foreach ($filePaths as &$filePath) {
                $this->addStyleSheet($filePath);
            }
        }

        /**
         * Adds a title to this page.
         *
         * @param $title string the title of the page.
         * @return void
         */
        protected function setTitle($title) {
            $this->addToHead("<title>{$title}</title>", Page::TOP);
        }
    }
