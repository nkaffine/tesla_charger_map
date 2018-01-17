<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/ADecoratorPage.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/Page.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/DefaultPage.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/21/17
     * Time: 8:54 AM
     */
    class FacebookPage extends ADecoratorPage {

        /**
         * FacebookPage constructor.
         *
         * @param $pageName string the name of the page.
         */
        public function __construct($pageName) {
            parent::__construct(new DefaultPage($pageName));
        }

        /**
         * Returns the formatted html page.
         *
         * @return string the formatted html page.
         */
        public function generateHtml() {
            $this->addToBody("<div id=\"fb-root\"></div>", Page::TOP);
            return parent::generateHtml();
        }
    }