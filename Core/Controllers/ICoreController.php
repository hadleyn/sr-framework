<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author smarkoski
 */
interface ICoreController {
    public function preajax();
    public function precontroller();
    public function invoke();
    public function postcontroller();
}
?>
