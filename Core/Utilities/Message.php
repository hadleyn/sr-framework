<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Message
 * 
 * Message is a utility for displaying various errors/messages on views.
 *
 * @author smarkoski
 */
class Message {

    private $sessionHelper;

    public function __construct() {
        $this->sessionHelper = new Session();
    }

    public function showMessages($echo = TRUE) {
        $msg = '';
        try {
            $errors = $this->sessionHelper->read('sr_errors');
        } catch (SessionDataIOException $e) {
            $errors = array();
        }
        try {
            $messages = $this->sessionHelper->read('sr_messages');
        } catch (SessionDataIOException $e) {
            $messages = array();
        }
        while ($e = array_shift($errors)) {
            $msg .= '<div class="' . Configuration::read('error_div_class') . '">' . $e . '</div>';
        }
        while ($e = array_shift($messages)) {
            $msg .= '<div class="' . Configuration::read('message_div_class') . '">' . $e . '</div>';
        }
        $this->sessionHelper->destroy('sr_errors');
        $this->sessionHelper->destroy('sr_messages');
        if (!$echo) {
            return $msg;
        }
        echo $msg;
    }

    public function pushError($error) {
        try {
            $errors = $this->sessionHelper->read('sr_errors');
        } catch (SessionDataIOException $e) {
            $errors = array();
        }
        if (is_array($error)) {
            $errors = array_merge($errors, $error);
        } else {
            $errors[] = $error;
        }
        $this->sessionHelper->write('sr_errors', $errors, TRUE);
    }

    public function hasErrors() {
        try {
            $errors = $this->sessionHelper->read('sr_errors');
        } catch (SessionDataIOException $e) {
            $errors = array();
        }
        return count($errors) > 0;
    }

    public function pushMessage($message) {
        try {
            $messages = $this->sessionHelper->read('sr_messages');
        } catch (SessionDataIOException $e) {
            $messages = array();
        }
        if (is_array($message)) {
            $messages = array_merge($messages, $message);
        } else {
            $messages[] = $message;
        }
        $this->sessionHelper->write('sr_messages', $messages, TRUE);
    }

    public function hasMessages() {
        try {
            $messages = $this->sessionHelper->read('sr_messages');
        } catch (SessionDataIOException $e) {
            $messages = array();
        }
        return count($messages) > 0;
    }

}

?>
