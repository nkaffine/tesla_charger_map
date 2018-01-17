<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/page/APage.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/20/17
     * Time: 4:51 PM
     */
    class DefaultPage extends APage {
        public function __construct($pageName) {
            parent::__construct($pageName);
            $this->initializeJQuery();
            $this->initializeBootstrap();
            $this->initializeSelectors();
        }
    }