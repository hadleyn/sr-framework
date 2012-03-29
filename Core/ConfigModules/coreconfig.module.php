<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of coreconfig
 *
 * @author smarkoski
 */
class coreconfig implements ConfigModule {
    
    public function __construct() {
        $this->selfname = get_class($this);
    }
    
    public function readConfig() {
//        $filename = '../../../sr_configuration.xml';
        $filename = 'sr_configuration.xml';
        $fp = fopen($filename, 'r');
        $xmlstr = fread($fp, filesize($filename));
        fclose($fp);
        $sxml = new SimpleXMLElement($xmlstr);
        $this->pathsConfig($sxml);
    }
    
    private function pathsConfig($sxml) {
        foreach ($sxml->{$this->selfname}->paths->path as $path) {
            Configuration::write((string) $path['name'], (string)$path);
        }
    }
    
}

?>
