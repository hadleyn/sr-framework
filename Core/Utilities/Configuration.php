<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Configuration
 *
 * @author smarkoski
 */
class Configuration {

    private static $config;

    public static function write($name, $value) {
        self::$config[$name] = $value;
    }

    public static function read($name) {
        return self::$config[$name];
    }

    public static function dump() {
        print_r(self::$config);
    }

}
?>
