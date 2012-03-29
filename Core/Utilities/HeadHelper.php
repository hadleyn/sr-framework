<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HeadHelper
 *
 * @author smarkoski
 */
class HeadHelper {

    private static $instance;
    private $css;
    private $script;
    private $meta;
    private $htmlTitle;

    protected function __construct() {
        $this->css = array();
        $this->script = array();
        $this->meta = array();
        $this->htmlTitle = '';
    }

    /**
     *
     * @return HeadHelper 
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new HeadHelper();
        }
        return self::$instance;
    }

    public function setTitle($title) {
        $this->htmlTitle = $title;
    }

    public function addCSS($css, $absolute = FALSE) {
        if (!$absolute) {
            $this->css[] = Configuration::read('basepath').'/Webroot/css/' . $css . '.css';
        } else {
            $this->css[] = $css;
        }
    }

    /**
     * Add a script to the header.
     * 
     * @param string $script The name of the script to add or the path if using a script outside of the default script location.
     * @param int $priority [Optional] The priority of the script. 0 is higest meaning it will be loaded first. Default is -1 
     *                      meaning add the script onto the end of the list.
     * @param boolean $absolute [Optional] Whether or not the script name is absolute. Default is false.
     */
    public function addScript($script, $priority = -1, $absolute = FALSE) {
        if (!$absolute) {
            if ($priority >= 0) {
                $priority = min(array($priority, count($this->script)));
                array_splice($this->script, $priority, 0, Configuration::read('basepath').'/Webroot/js/' . $script . '.js');
            } else {
                $this->script[] = Configuration::read('basepath').'/Webroot/js/' . $script . '.js';
            }
        } else {
            if ($priority >= 0) {
                $priority = min(array($priority, count($this->script)));
                array_splice($this->script, $priority, 0, $script);
            } else {
                $this->script[] = $script;
            }
        }
    }

    public function addMeta($name, $data) {
        $this->meta[$name] = $data;
    }

    public function generateHead() {
        ob_start();
        $temp = array();
        $temp['css'] = $this->css;
        $temp['script'] = $this->script;
        $temp['meta'] = $this->meta;
        $temp['title'] = $this->htmlTitle;
        extract($temp);
        include 'Core/HTML/HeadBlock.php';
        $head = ob_get_contents();
        ob_end_clean();

        return $head;
    }

}

?>
