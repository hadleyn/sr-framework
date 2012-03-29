<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of email
 *
 * @author smarkoski
 */
class email extends Rule {

    public function getError() {
        return 'The value in '.$this->fieldName .' is not a valid email address';
    }

    public function run() {
        if (filter_var($this->value, FILTER_VALIDATE_EMAIL)){
            return TRUE;
        }
        return FALSE;
    }

}

?>
