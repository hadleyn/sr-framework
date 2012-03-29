<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Core/Controllers/CoreController.php';
require_once 'Core/Utilities/ConfigModule.php';
require_once 'Core/Utilities/Configuration.php';
require_once 'Core/Utilities/Loader.php';


/**
 * Description of Bootstrap
 *
 * @author smarkoski
 */
class Bootstrap {

    private $uri;
    private $controller;
    private $method;
    private $arguments;
    private $controllerFile;
    private $controllerObject;

    public function __construct() {

        $this->createSPLAutoloaders();

        $this->loadConfigs();
        
        $this->uri = URIHelper::getURIArray();

        $this->prepareURI();

        $this->buildCMA(); //Controller, method and arguments

        $this->instantiateController();
        
        define('BASEPATH', Configuration::read('basepath'));
        define('HOST', $_SERVER['SERVER_NAME']);
    }

    public function run() {

        if (Configuration::read('sessions_enabled')) {
            session_start();
        }

        $this->executePreLaunchTasks();

        $this->executeAjax();

        $this->executePrecontroller();

        $this->executeInvoke();

        $this->executePostcontroller();

        $this->executePostLaunchTasks();

        $this->storeSRReferer();
    }

    private function createSPLAutoloaders() {
        spl_autoload_register(null, false);
        spl_autoload_extensions('.php');
        spl_autoload_register(array('Loader', 'loadGeneric'));
        spl_autoload_register(array('Loader', 'loadModule'));
    }

    private function prepareURI() {
        //Shift off the basepath and discard it
        if (Configuration::read('basepath') != '') {
            $devnull = array_shift($this->uri);
        }

        //Deal with lack of mod-rewrite
        if (!Configuration::read('mod_rewrite_enabled')) {
            $devnull = array_shift($this->uri);
        }
    }

    private function buildCMA() {
        $this->controller = array_shift($this->uri);
        $this->method = array_shift($this->uri);
        $this->arguments = $this->uri;
    }

    private function instantiateController() {
        $this->controllerFile = Configuration::read('controller_path') . $this->controller . '.controller.php';

        if (file_exists($this->controllerFile)) {
            $this->controllerObject = new $this->controller();
        } else if (empty($this->controller)) {
            //Load the default controller
            $defaultController = Configuration::read('default_controller');
            $this->controllerObject = new $defaultController();
        } else {
            die('Missing controller: ' . $this->controllerFile);
        }
    }
    
    private function executePreLaunchTasks() {
        /*
         * Look for any pre-launch tasks
         */
        $preLaunch = scandir(Configuration::read('task_path'));
        foreach ($preLaunch as $pre) {
            if (preg_match('/prelaunch\.php$/', $pre) > 0) {
                $pre = preg_replace('/\..*$/', '', $pre);
                $task = new $pre();
                $task->execute();
            }
        }
    }
    
    private function executeAjax() {
        if (method_exists($this->controllerObject, $this->method . '_ajax')) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                //Call the preajax method first if we've got one
                if (method_exists($this->controllerObject, 'preajax')) {
                    $this->wrap_call_user_func_array($this->controllerObject, 'preajax', $this->arguments);
                }
                $this->wrap_call_user_func_array($this->controllerObject, $this->method . '_ajax', $this->arguments);
                die();
            } else {
                die('Invalid request!');
            }
        }
    }

    private function executePrecontroller() {
        $validMethod = 'precontroller';
        if (method_exists($this->controllerObject, $this->method . '_precontroller')) {
            $validMethod = $this->method . '_precontroller';
        }
        $this->wrap_call_user_func_array($this->controllerObject, $validMethod, $this->arguments);
    }

    private function executeInvoke() {
        $validMethod = 'invoke';
        if (method_exists($this->controllerObject, $this->method . '_invoke')) {
            $validMethod = $this->method . '_invoke';
        }
        $this->wrap_call_user_func_array($this->controllerObject, $validMethod, $this->arguments);
    }

    private function executePostcontroller() {
        $validMethod = 'postcontroller';
        if (method_exists($this->controllerObject, $this->method . '_postcontroller')) {
            $validMethod = $this->method . '_postcontroller';
        }
        $this->wrap_call_user_func_array($this->controllerObject, $validMethod, $this->arguments);
    }

    private function executePostLaunchTasks() {
        //nothing here yet
    }

    private function storeSRReferer() {
        $session = new Session();
        $session->write('sr_referer', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TRUE);
    }

    private function wrap_call_user_func_array($c, $a, $p) {
        switch (count($p)) {
            case 0: $c->{$a}();
                break;
            case 1: $c->{$a}($p[0]);
                break;
            case 2: $c->{$a}($p[0], $p[1]);
                break;
            case 3: $c->{$a}($p[0], $p[1], $p[2]);
                break;
            case 4: $c->{$a}($p[0], $p[1], $p[2], $p[3]);
                break;
            case 5: $c->{$a}($p[0], $p[1], $p[2], $p[3], $p[4]);
                break;
            default: call_user_func_array(array($c, $a), $p);
                break;
        }
    }

    private function loadConfigs() {
        $coreConfigModules = scandir('Core/ConfigModules');
        $appConfigModules = scandir('App/ConfigModules');
        $allModules = array_merge($coreConfigModules, $appConfigModules);
        foreach ($allModules as $module) {
            if (preg_match('/module\.php$/', $module) > 0) {
                $module = preg_replace('/\..*$/', '', $module);
                $configModule = new $module();
                $configModule->readConfig();
            }
        }
    }

}

?>
