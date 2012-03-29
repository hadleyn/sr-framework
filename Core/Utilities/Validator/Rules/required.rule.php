<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of required
 *
 * @author smarkoski
 */
class required extends Rule {
    
    public function getError() {
        return 'The '.$this->fieldname.' field is required';
    }

    public function run() {
        if (empty($this->value)){
            return FALSE;
        }
        return TRUE;
    }

}

?>
