<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maxlength
 *
 * @author smarkoski
 */
class maxlength extends Rule {
    
    private $maxlength;
    
    public function __construct($value, $maxlength, $fieldname){
        $this->maxlength = $maxlength;
        parent::__construct($value, $fieldname);
    }
    
    public function getError() {
        return 'The value for '.$this->fieldname.' must be less than '.$this->maxlength .' characters in length';
    }

    public function run() {
        if (strlen($this->value) > $this->maxlength) {
            return FALSE;
        }
        return TRUE;
    }

}

?>
