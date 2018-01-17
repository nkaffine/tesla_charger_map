<?php

    /**
     * Created by PhpStorm.
     * user: Nick
     * Date: 12/20/17
     * Time: 10:51 PM
     */
    class Table {
        private $columns;
        private $rows;
        private $title;

        public function __construct() {
            $this->columns = array();
            $this->rows = array();
            $this->title = null;
        }

        /**
         * Adds a title to the table
         *
         * @param $title string the title of the table.
         */
        public function addTitle($title) {
            $this->title = $title;
        }

        /**
         * Adds columns to the table.
         *
         * @param string[] ...$columns
         */
        public function addColumns(...$columns) {
            foreach ($columns as $column) {
                array_push($this->columns, $column);
            }
        }

        /**
         * Adds rows to the table.
         *
         * @param array [string[]] ...$rows
         */
        public function addRows(...$rows) {
            $this->addRowsArray($rows);
        }

        /**
         * Adds rows to the table.
         *
         * @param array [string[]] $rows
         */
        public function addRowsArray($rows) {
            foreach ($rows as $row) {
                if (sizeof($row) == sizeof($this->columns)) {
                    array_push($this->rows, $row);
                }
            }
        }

        /**
         * Returns the html for the head of the table.
         *
         * @return string the html for the head of the table.
         */
        private function getHeadHtml() {
            $html = "<thead>";
            foreach ($this->columns as $column) {
                $html .= "<th>$column</th>";
            }
            return $html . "</thead>";
        }

        /**
         * Returns the html for the body of the table.
         *
         * @return string the html for the body of teh table.
         */
        private function getBodyHtml() {
            $html = "<tbody>";
            foreach ($this->rows as $row) {
                $html .= "<tr>";
                foreach ($row as $value) {
                    $html .= "<td>" . $value . "</td>";
                }
                $html .= "</tr>";
            }
            return $html . "</tbody>";
        }

        /**
         * Gets the html for the table.
         *
         * @param $width int the column width of the table.
         * @param $offset int the column offset of the table.
         * @return string the html for the table.
         */
        public function getHtml($width, $offset) {
            $html =
                "<div class='col-lg-{$width} col-lg-offset-{$offset}' style='background-color: white; margin-top: 2%; padding-left:0; padding-right:0;'>";
            if ($this->title !== null) {
                $html .= "<h1 class='time'>{$this->title}</h1>";
            }
            $html .=
                "<table class='col-lg-12 table table-striped' style='margin-bottom: 0;'>";
            $html .= $this->getHeadHtml() . $this->getBodyHtml();
            return $html . "</table></div>";
        }
    }