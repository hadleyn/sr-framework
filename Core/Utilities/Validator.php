<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validator
 *
 * @author smarkoski
 */
class Validator {

    private $rules;
    private $errors;

    public function __construct() {
        $this->rules = array();
        $this->errors = array();
    }

    public function addRule($rule) {
        $this->rules[] = $rule;
    }

    public function run() {
        foreach ($this->rules as $rule) {
            if (!$rule->run()) {
                $this->errors[] = $rule->getError();
            }
        }
        return $this->errors;
    }

}

?>
