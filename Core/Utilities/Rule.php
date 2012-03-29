<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author smarkoski
 */
abstract class Rule {
    
    protected $fieldname;
    protected $value;
    
    public function __construct($value, $fieldName) {
        $this->value = $value;
        $this->fieldname = $fieldName;
    }
    
    abstract public function run();
    abstract public function getError();
}

?>
