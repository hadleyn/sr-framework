<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of URIHelper
 *
 * @author smarkoski
 */
define('URI_COMMAND', 1);
define('URI_METHOD', 2);
define('URI_ARG0', 3);
define('URI_ARG1', 4);
define('URI_ARG2', 5);

class URIHelper {

    /**
     * Get a sanitized version of the request URI
     * 
     * @return string The request URI filtered by FILTER_SANITIZE_URL 
     */
    public static function getRequestURI() {
        return filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    }

    public static function getURIArray() {
        $uri = self::getRequestURI();
        $uri = preg_replace('@^\/|\/$|\?.*$@', '', $uri);
        $array = explode('/', $uri);

        return $array;
    }

    /**
     *
     * @param int $index
     * @throws URIAccessException If the specified element index does not exist
     */
    public static function getURIElementAtIndex($index) {
        $uri = self::getURIArray();
        try {
            $element = $uri[$index];
        } catch (Exception $e) {
            throw new URIAccessException('The URI element ' . $index . ' does not exist');
        }
        return $element;
    }

}
?>
