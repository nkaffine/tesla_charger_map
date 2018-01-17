<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/mysql/querying/where/IWhereCombiner.php");

    /**
     * Created by PhpStorm.
     * User: Nick
     * Date: 12/19/17
     * Time: 11:54 AM
     */
    abstract class AWhereCombiner implements IWhereCombiner {
        /**
         * @var IWhere[]
         */
        protected $wheres;

        /**
         * AWhereCombiner constructor.
         */
        public function __construct() {
            $this->wheres = array();
        }

        /**
         * Adds a where to the combiner.
         *
         * @param $where IWhere the where being added to the combiner.
         * @return AWhereCombiner
         */
        public function addWhere($where) {
            array_push($this->wheres, $where);
            return $this;
        }

        /**
         * Generates the string where statement of this where.
         *
         * @return string the where statement.
         */
        public function generateWhere() {
            switch (sizeof($this->wheres)) {
                case 0:
                    return "";
                    break;
                case 1:
                    return $this->wheres[0]->generateWhere();
                    break;
                default:
                    $whereString = "";
                    for ($i = 0; $i < sizeof($this->wheres); $i++) {
                        $whereString .= "(" . $this->wheres[$i]->generateWhere() . ")";
                        if ($i < (sizeof($this->wheres) - 1)) {
                            $whereString .= " " . $this->getCombiner() . " ";
                        }
                    }
                    return $whereString;
                    break;
            }
        }

        /**
         * Gets the expression to combine the wheres.
         *
         * @return string the sql expression to combine the wheres.
         */
        abstract protected function getCombiner();


        /**
         * Determines if there is a where statement in this IWhere.
         *
         * @return boolean
         */
        public function isWhere() {
            return sizeof($this->wheres) > 0;
        }
    }