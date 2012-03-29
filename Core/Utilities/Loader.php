<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loader
 *
 * @author smarkoski
 */
class Loader {

    public static function loadGeneric($e) {
        $file = array();
        $file[] = Configuration::read('utility_path') . $e . '.php';
        $file[] = Configuration::read('utility_path') . 'Validator/Rules/' . $e . '.rule.php';
        $file[] = Configuration::read('user_validation_rules_path') . $e . '.rule.php';
        $file[] = Configuration::read('app_utility_path') . $e . '.php';
        $file[] = Configuration::read('exception_path') . $e . '.php';
        $file[] = Configuration::read('model_path') . $e . '.model.php';
        $file[] = Configuration::read('controller_path') . $e . '.controller.php';
        $file[] = Configuration::read('core_model_path') . $e . '.php';
        $file[] = Configuration::read('task_path') . $e . '.precontroller.php';
        $file[] = Configuration::read('task_path') . $e . '.postcontroller.php';
        $file[] = Configuration::read('task_path') . $e . '.prelaunch.php';
        $file[] = Configuration::read('task_path') . $e . '.postlaunch.php';
        $f = null;
        do {
            $f = array_shift($file);
        } while ((@include_once $f) === FALSE && count($file) > 0);
    }

    public static function loadView($v, $viewData = NULL) {
        $file = Configuration::read('view_path') . $v . '.view.php';
        if (file_exists($file)) {
            extract($viewData);
            include $file;
        }
    }

    public static function loadModule($m) {

        $file = 'Core/ConfigModules/' . $m . '.module.php';

        if ((@include_once $file) === FALSE) {
            $file = 'App/ConfigModules/' . $m . '.module.php';
            @include_once $file;
        }
    }

}

?>
