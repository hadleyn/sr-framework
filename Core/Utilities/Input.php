<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Input
 *
 * @author smarkoski
 */
class Input {

    public static function post($index, $sanitize = TRUE) {
        if (!isset($_POST[$index])) {
            throw new InputIOException('Post index ' . $index . ' not found!');
        }
        $post = $_POST[$index];
        if ($sanitize) {
            $post = self::sanitize($post);
        }
        return $post;
    }

    public static function get($index, $sanitize=TRUE) {
        if (!isset($_GET[$index])) {
            throw new InputIOException('Get index ' . $index . ' not found!');
        }
        $get = $_GET[$index];
        if ($sanitize) {
            $get = self::sanitize($post);
        }
        return $get;
    }

    public static function referer($sanitize=TRUE) {
        if (!isset($_SESSION['sr_referer'])) {
            throw new InputIOException('Referer not set');
        }
        if ($sanitize)
        {
            return filter_var($_SESSION['sr_referer'], FILTER_SANITIZE_URL);
        }
        return $_SESSION['sr_referer'];
    }

    private static function sanitize($input) {
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

}

?>
