<?php
/**
 * Reddit Project Users Classes
 * Author: Addison Benzshawel
 * Date: 3/16/14
 */

// class to view user info
class get_user_info {
    private $db;
    private $today;
    private  $userid;

    public function __construct($id){
        $this->db = new db_connect('localhost', 'site_admin', '5QNuvacQHLS74a8E', 'reddit_project');
        $this->today = new DateTime();
        $this->userid = $this->db->real_escape_string($id);
    }

    public function username(){
        $query = "SELECT username FROM users WHERE userid = '" . $this->userid . "'";
        return $this->make_query($query);
    }

    public function name(){
        $query = "SELECT name FROM users WHERE userid = '" . $this->userid . "'";
        return $this->make_query($query);
    }

    public function email(){
        $query = "SELECT email FROM users WHERE userid = '" . $this->userid . "'";
        return $this->make_query($query);
    }

    public function account_age(){
        $query = "SELECT date_created FROM users WHERE userid = '" . $this->userid . "'";
        $date_created = $this->make_query($query);
        $todays_date = $this->today->format('Y-m-d H:i:s');//getTimestamp();
        $res[0] = strtotime($todays_date);
        $res[1] = strtotime($date_created);
        $res[2] = (string)round(($res[0]-$res[1])/60/60/24, 2) . " days";
        return $res[2];

    }
    private function make_query($query){
        if ($result = $this->db->query($query)) {
            //fetch and return object array
            foreach($result->fetch_row() as $row) {
                return $row;
            }
            $result->close();
        }
    }
} # END CLASS

// This class is used to create new users
class users {
    private $userid;
    private $name;
    private $username;
    private $password;
    private $salt;
    private $email;
    private $db;

    public function __construct() {
        $this->db = new db_connect('localhost', 'site_admin', '5QNuvacQHLS74a8E', 'reddit_project');
        $this->salt = 'd353c3ae22a25401d257643836d7231a9a95f953';
    }

    public function new_user($name, $username, $password, $email){
        $this->name = $this->db->real_escape_string($name);
        $this->username = $this->db->real_escape_string($username);
        $this->password = hash('sha256', $this->db->real_escape_string($password) . $this->salt);
        $this->email = $this->db->real_escape_string($email);
        $query = "INSERT INTO users (name, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssss', $this->name, $this->username, $this->password, $this->email);
        $status = $stmt->execute();
        if ($status != false){
            $stmt->close();
            return "Success! your account has been created.";
        } else {
            $stmt->close();
            return 'Sorry something went wrong. Please try again. <br/>Error:' . $stmt->error ;
        }
    }

    // The check login method compares a username and password. If success returns array with userid,
    // name, username, and email. If failure returns NULL
    public function check_login($username, $password){
        $this->username = $this->db->real_escape_string($username);
        $this->password = hash('sha256', $this->db->real_escape_string($password) . $this->salt);
        $query = "SELECT userid, name, username, email FROM users WHERE username = '" . $this->username. "'
						AND password = '" .$this->password . "'";
        if ( $result = $this->db->query($query) ) {
            while ($row = $result->fetch_assoc()) {
                return $row;
            }
        } else {
            return NULL;
        }

    }
    // IMPORTANT: edit_user method arguments defined as follows:
    // $userid = integer, $values = string, $name = name
    // for values array 'key' is the column name and value is the updated value. The $key variable equals
    // the same 'key'/column name used in the values array.
    public function edit_user($userid, $value, $name) {
        $this->userid = intval($this->db->real_escape_string($userid));
        $value = $this->db->real_escape_string($value);
        $name = $this->db->real_escape_string($name);
        $stmt = $this->db->prepare("UPDATE users SET " . $name . " = ? WHERE userid = ?");
        $stmt->bind_param('si', $value, $this->userid);
        $status = $stmt->execute();
        if( $status != false){
            $stmt->close();
            return 'Success - Your account has been edited!';
        } else {
            $stmt->close();
            return 'Sorry something went wrong. Please try again. <br/>Error:' . $stmt->error ;
        }
    }
    // this method determines if a value in the USERS table is unique.
    // $userinput = string tested and $type = string type (username, email, name, etc)
    public function is_unique($userinput, $type){
        $value = $this->db->real_escape_string($userinput);
        $type = $this->db->real_escape_string($type);
        $query = "SELECT " . $type . " FROM users WHERE " . $type . " = '" . $value . "'";
        if($this->make_query($query) === NULL){
            return true;
        } else {
            return false;
        }
    }
    // Lookup userid by username
    public function id($username){
        $this->username = $this->db->real_escape_string($username);
        $query = "SELECT `userid` FROM `users` WHERE `username` = ? ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $this->username);
        $status = $stmt->execute();
        if ($status != false){
            $stmt->bind_result($this->userid);
            $stmt->close();
            return $this->userid;

        } else {
            $stmt->close();
            return 'Sorry something went wrong. Please try again. <br/>Error: ' . $stmt->error ;
        }
    }

    // Lookup username by userid
    public function username($userid){
        $this->userid = $this->db->real_escape_string($userid);
        $query = "SELECT username FROM users WHERE userid = '" . $this->userid . "'";
        $res = $this->make_query($query);
        return $res['username'];
    }
    // OLD WAY in process of phasing out. Use make_prepared_query in the future
    private function make_query($query){
        if ( $result = $this->db->query($query) ) {
            while ($row = $result->fetch_assoc()) {
                return $row;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Private method that makes a prepared query. Parameters defined below:
     * @param $select_set = string of column names/values to be selected. For example "`username`, `userid`,"
     * @param $column = array of column names in $select_set
     * @param $table = string name of table or tables to be selected from
     * @param $whereArgs = string value of where arguments if needed. can be empty string
     * @param $argTypes = array with each letter i, s, f, etc of variable type for arguments as a string
     * @param $argVars = array of variables for whereArgs.
     *  Returns results ($query_result) in array of arrays with "column_name" => "value"
     */
    private function make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars ){
        // Make sure all parameters are proper type
        $select_set = (is_string($select_set)) ? $select_set : false;
        $column = (is_array($column)) ? $column : false;
        $table = (is_string($table)) ? $table : false;
        $whereArgs = (is_string($whereArgs)) ? $whereArgs : ($whereArgs == '' ) ? $whereArgs : false;
        $argTypes = (is_array($argTypes)) ? $argTypes : false;
        $argVars = (is_array($argVars)) ? $argVars : false;
        // If parameter format wrong return error
        if(!$select_set or !$column or !$table or !$whereArgs or !$argTypes or !$argVars){
            return "Error: Improper parameter formats see method declaration for parameter information!";
        } else {
            // Proper procedure query order: prepare -> bind_param -> execute -> store_result -> bind_result -> fetch
            // prepare
            $stmt = $this->db->prepare("SELECT $select_set FROM $table WHERE $whereArgs") ;
            // bind parameters by reference
            $params = array_merge($argTypes, $argVars);
            call_user_func_array(array(&$stmt, 'bind_param'), $this->refValues($params));
            // execute
            $stmt->execute();
            // store results
            $stmt->store_result();
            // bind results (similar to bind parameter)
            $data = array() ; // Array that accepts the data.
            $params = array() ; // Parameter array passed to 'bind_result()'
            foreach($column as $col_name){
                // 'fetch()' will assign fetched value to the variable '$data[$col_name]'
                $params[] =& $data[$col_name] ;
            }
            $res = call_user_func_array(array($stmt, "bind_result"), $params) ;
            // if success fetch results
            if(!$res){
                $query_result =  "bind_result() failed: " . $this->db->error . "\n" ;
            } else {
                $i=0;
                // fetch all rows of result and store in $query_result
                while($stmt->fetch()){
                    $query_result[$i] = array();
                    foreach($data as $key=>$value)
                        $query_result[$i][$key] = $value;
                    $i++;
                }
            }
            // close statement and db connection
            $stmt->close() ;
            $this->db->close() ;
            // return query results
            return $query_result;
        }
    }
    // Returns inputted array by reference
    private function refValues($arr){
        $refs = array();
        foreach ($arr as $key => $value){
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }

} # END CLASS
