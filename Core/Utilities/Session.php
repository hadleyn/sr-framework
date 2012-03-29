<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 * 
 * The session class acts as a wrapper for native php sessions. It provides additional
 * functionality such as timed sessions.
 *
 * @author smarkoski
 */
class Session {

    /**
     * This is the basic wrapper for session value writing. It manages the write
     * operation and prevents overwriting of existing values without confirmation.
     * 
     * @param string $name The name of the value to write
     * @param mixed $value The value to store in the session.
     * @param boolean $overwrite [Optional] Allows session values to be overwritten. Default is false.
     * @throws SessionDataIOException if the named value already exists
     */
    public function write($name, $value, $overwrite = FALSE) {
        if ($this->peek($name) && !$overwrite) {
            throw new SessionDataIOException('Tried to write session data to ' . $name . ' that already exists');
        }
        $_SESSION[$name] = $value;
    }

    /**
     * Read a session value. The read method can read normal and timed session values.
     * 
     * @param string $name The name of the session value to read
     * @return mixed The data stored in that session variable.
     * @throws SessionDataIOException if the named session data does not exist.
     * @throws SessionDataExpiredException if the named session data has expired.
     */
    public function read($name) {
        //Decide if the session value is even set or not
        if ($this->peek($name)) {
            return $this->readSessionPackage($name);
        } else {
            throw new SessionDataIOException('Session data ' . $name . ' does not exist!');
        }
    }

    /**
     * Peek at a session name to see if it exists.
     * 
     * @param string $name The name of the session to look for.
     * @return boolean True if the session data exists, False if it doesn't. 
     */
    public function peek($name) {
        if (isset($_SESSION[$name])) {
            return TRUE;
        }
        return FALSE;
    }
    
    public function destroy($name) {
        $_SESSION[$name] = NULL;
        unset($_SESSION[$name]);
    }

    /**
     * Write a timed session value. This value will exist only for $duration 
     * milliseconds.
     * 
     * @param string $name The name of the session value
     * @param mixed $value The value of the session variable
     * @param int $duration The duration (in seconds) this session value will last.
     * @param boolean $overwrite [Optional] Allows existing session data to be overwritten. Default is false.
     * @throws SessionDataIOException If the session data already exists and the overwrite is not set.
     */
    public function writeTimed($name, $value, $duration, $overwrite = FALSE) {
        $dataPackage = base64_encode(serialize($value)) . '~' . $duration . '~' . time();
        $checksum = hash_hmac('md5', $dataPackage, Configuration::read('random_salt'));
        $dataPackage .= '~' . $checksum;
        $this->write($name, $dataPackage, $overwrite);
    }

    /**
     * This helper function will correctly decode a session package (whether it is
     * a flat package or a timed package and return the value.
     *  
     * @param string $name The name of the session data.
     */
    private function readSessionPackage($name) {
        if (is_array($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        $package = explode('~', $_SESSION[$name]);
        if (count($package) == 4) {
            list($data, $duration, $initialized, $checksum) = $package;
            $actualChecksum = hash_hmac('md5', $data . '~' . $duration . '~' . $initialized, Configuration::read('random_salt'));
            if ($actualChecksum != $checksum) {
                //Normal session value
                return $_SESSION[$name];
            } else {
                if (($initialized + $duration) < time()) {
                    throw new SessionDataExpiredException('The session data ' . $name . ' has expired!');
                } else {
                    return unserialize(base64_decode($data));
                }
            }
        } else {
            return $_SESSION[$name];
        }
    }

}

?>
