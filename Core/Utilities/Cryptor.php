<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cryptor
 * 
 * The cryptor class provides encryption/decryption functionality using PHP's 
 * mcrypt. It also provides functionality for things such as secure strings. Secure
 * strings are strings where the data is plainly visible, but that can be verified
 * for authenticity.
 *
 * @author smarkoski
 */
class Cryptor {
    
    private $secretKey;
    
    /**
     *
     * @param string $key The name of the key to use for this instance 
     */
    public function __construct($key='random_salt'){
        $this->secretKey = Configuration::read($key);
    }
    
    /**
     * Create a secure string. This will leave the values being secured in plaintext,
     * but will allow the string to be verified as authentic. 
     * 
     * @param mixed $value A single value or array of values to secure.
     * @param string $method The hashing method to use. Default is md5
     * @return string The secure string value
     */
    public function createSecureString($value, $method='md5'){
        $temp = '';
        if (is_array($value)){
            $temp = implode('~~', $value);
        } else {
            $temp = $value;
        }
        $hash = hash_hmac($method, $temp, $this->secretKey);
        $result = urlencode(base64_encode($temp.'~~'.$hash));
        
        return $result;
    }
    
    
    /**
     *
     * @param string $secureString
     * @param type $method The hashing method to use. Default is md5
     */
    public function verifySecureString($secureString, $method='md5'){
        $temp = base64_decode(urldecode($secureString));
        $pieces = explode('~~', $temp);
        $hash = array_pop($pieces);
        $data = implode('~~', $pieces);
        
        return (hash_hmac($method, $data, $this->secretKey) == $hash);
    }
    
    /**
     * A utility for getting the secure data out of the secure string.
     * 
     * @param string $secureString
     * @return array The array of strings in the secure hash 
     */
    public function getSecureData($secureString) {
        $temp = base64_decode(urldecode($secureString));
        $pieces = explode('~~', $temp);
        array_pop($pieces); //remove the hash
        return $pieces;
    }
    
    
}

?>
