<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author smarkoski
 */
class index extends CoreController {

    public function __construct() {
        parent::__construct();
    }

    public function invoke() {
        $this->loadView('index');
    }

}

?>
