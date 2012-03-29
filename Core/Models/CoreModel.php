<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreModel
 *
 * @author smarkoski
 */
class CoreModel {
    
    public function __get($name){
        if (property_exists($this, $name)){
            return $this->$name;
        }
        throw new ModelProperyNotFoundException('Could not find the '.$name.' property on the '.get_class($this).' model');
    }
    
    public function __set($name, $value){
        if (property_exists($this, $name)){
            $this->$name = $value;
        } else {
            throw new ModelPropertyNotFoundException('Could not find the '.$name.' property on the '.get_class($this).' model');
        }
    }
    
    protected function populate($dbResult){
        foreach ($dbResult as $key => $value){
            $this->$key = $value;
        }
    }
}

?>
