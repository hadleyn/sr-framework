<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of matches
 *
 * @author smarkoski
 */
class matches extends Rule {

    private $fieldA;
    private $fieldB;
    private $fieldAName;
    private $fieldBName;

    /**
     * The matches rule takes 4 arguments. It will verify that the two values
     * provided match each other exactly in type and value.
     * 
     * @param mixed $fieldA The first value
     * @param mixed $fieldB The second value
     * @param string $fieldAName The name of the first value
     * @param string $fieldBName The name of the second value
     */
    public function __construct($fieldA, $fieldB, $fieldAName, $fieldBName) {
        $this->fieldA = $fieldA;
        $this->fieldB = $fieldB;
        $this->fieldAName = $fieldAName;
        $this->fieldBName = $fieldBName;
    }

    public function getError() {
        return 'The value for ' . $this->fieldAName . ' did not match the value for ' . $this->fieldBName;
    }

    public function run() {
        if ($this->fieldA !== $this->fieldB){
            return FALSE;
        }
        return TRUE;
    }

}

?>
