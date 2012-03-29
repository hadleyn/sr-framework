<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cookie
 *
 * The Cookie class acts as a wrapper for cookies. All options for cookies are 
 * setable using the functions except for the domain. The wrapper uses the default
 * domain value from the configuration xml unless you specify a new one using the 
 * setDomain() function.
 * 
 * @author smarkoski
 */
class Cookie {

    public function __construct() {
        $this->domain = Configuration::read('cookie_domain');
    }

    /**
     * Set the domain for cookies created by this instance of Cookie.
     * 
     * @param string $domain The domain to use for cookies. 
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * Write a cookie value.
     * 
     * @param string $name
     * @param string $value The string value to store in the cookie
     * @param int $expires [Optional] The time in seconds the cookie will be valid for. Default is 3600 (1 hour)
     * @param type $path [Optional] The path for the cookie. Default is '/'
     * @param type $overwrite [Optional] A flag to allow overwriting of existing cookies. Default is false.
     * @throws CookieDataIOException If the cookie already exists and the overwrite flag is not set.
     */
    public function write($name, $value, $expires = 3600, $path = '/', $overwrite = FALSE) {
        if ($this->peek($name) && !$overwrite){
            throw new CookieDataIOException('Cookie '.$name.' already exists!');
        }
        setcookie($name, $value, time()+$expires, $path, $this->domain);
        $_COOKIE[$name] = $value;
    }

    /**
     *
     * @param string $name The name of the cookie value to get. 
     * @return string The value within that cookie
     * @throws CookieDataIOException If the named cookie is not found.
     */
    public function read($name) {
        if ($this->peek($name)){
            return $_COOKIE[$name];
        }
        throw new CookieDataIOException('Cookie '.$name.' does not exist!');
    }

    /**
     * Returns whether or not the named cookie exists.
     * 
     * @param string $name
     * @return boolean 
     */
    public function peek($name) {
        if (isset($_COOKIE[$name])){
            return TRUE;
        }
        return FALSE;
    }

    public function clear($name) {
        setcookie($name, '', time()-200, '/', $this->domain);
        unset($_COOKIE[$name]);
    }

}

?>
