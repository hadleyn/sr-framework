<?php

define('SELECT', 'SELECT');
define('INSERT', 'INSERT');
define('UPDATE', 'UPDATE');
define('DELETE', 'DELETE');
define('CREATE', 'CREATE');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author smarkoski
 */
class DB {

    private $instance;
    private static $connections;
    private $mysqli;
    private $stmt;
    private $insertID;
    private $result;

    protected function __construct() {
        $this->mysqli = mysqli_init();
        if (!$this->mysqli->real_connect(Configuration::read('db_host'), Configuration::read('db_username'), Configuration::read('db_password'), Configuration::read('db_name'))) {
            throw new MysqliConnectionException('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
    }

    private static function addConnection($db) {
        if (isset(self::$connections) && !array_search($db, self::$connections)) {
            self::$connections[] = $db;
        }
    }

    public function cleanupConnection() {
        self::addConnection($this);
    }

    /**
     *
     * @return DB
     */
    public static function instance() {
        if (count(self::$connections) <= 0) {
            $db = new DB();
            return $db;
        } else {
            return array_shift(self::$connections);
        }

//        if (self::containsConnection(self::$instance) || !isset(self::$instance)) {
//            self::$instance = new DB();
//            self::addConnection(self::$instance);
//        }
//        return self::$instance;
    }

    public function getMysqli() {
        return $this->mysqli;
    }
    
    public function __get($name){
        if ($name == 'insert_id'){
            return $this->insertID;
        }
    }

    /**
     *
     * @param string $query The query string to run.
     * @param array $parameterTypes [Optional] An array of parameter types for mysqli
     * @param array $parameters [Optional] The array of parameters.
     * @param string $queryType [Optional] A query type constant may be specified. If it is 
     *                          omitted, the query type will be determined by the first word in
     *                          the query.
     * @throws MysqliMalformedQueryException 
     */
    public function query($query, $parameterTypes = array(), $parameters = array(), $queryType = '') {
        if ((array) $parameterTypes !== $parameterTypes) {
            $parameterTypes = explode(',', $parameterTypes);
        }
        
        if (isset($this->stmt)) {
            $this->stmt->close();
        }
        if ($queryType == '') {
            $queryType = $this->autoTypeQuery($query);
        }
        $this->stmt = $this->mysqli->prepare($query);
        if (!$this->stmt) {
            throw new MysqliMalformedQueryException($this->mysqli->error);
        }
        $this->bindParameters($parameterTypes, $parameters);
        $this->result = TRUE;
        switch ($queryType) {
            case SELECT:
                $this->result = $this->bindResults();
                break;
        }
        $this->stmt->execute();
        if ($this->stmt->error) {
            throw new MysqliQueryExecutionException('Execution of statement failed: ' . $this->stmt->error);
        }
        $this->insertID = $this->stmt->insert_id == 0 ? $this->mysqli->insert_id : $this->stmt->insert_id;
        if ($queryType != SELECT) {
            //return the connection to the pool
            $this->cleanupConnection();
        }
    }

    public function fetchResult() {
        if ($this->stmt->fetch()) {
            return $this->result;
        } else {
            //return the connection to the pool
            $this->cleanupConnection();
        }
    }

    private function bindParameters($parameterTypes, $parameters) {
        if (count($parameterTypes) > 0) {
            $bind_names = array();
            $bind_names[] = implode($parameterTypes);
            for ($i = 0; $i < count($parameters); $i++) {
                $bind_name = 'bind' . $i;
                $$bind_name = $parameters[$i];
                $bind_names[] = &$$bind_name;
            }
            call_user_func_array(array($this->stmt, 'bind_param'), $bind_names);
        }
    }

    private function bindResults() {
        //Get information on the query result
        $resultMetadata = $this->stmt->result_metadata();

        //Create an array where this query result will live
        $resultArray = array();

        //Now loop through the field names and build out an array with those names
        while ($field = $resultMetadata->fetch_field()) {
            $resultArray[$field->name] = NULL;
            $bindParameters[] = &$resultArray[$field->name];
        }

        //Now call the bind_result function on $stmt like we normally would, except now the
        //parameters are all built for us automatically!
        call_user_func_array(array($this->stmt, 'bind_result'), $bindParameters);

        return $resultArray;
    }

    private function autoTypeQuery($query) {
        $queryTerms = preg_split('/\s+/', $query);
        return strtoupper(array_shift($queryTerms));
    }

}

?>
