<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of alphanumeric
 *
 * @author smarkoski
 */
class alphanumeric extends Rule {

    public function getError() {
        return 'The '.$this->fieldname .' field must contain only alpha numeric characters';
    }

    public function run() {
        if (preg_match('/[^\w]/i', $this->value)) {
            return FALSE;
        }
        return TRUE;
    }

}

?>
