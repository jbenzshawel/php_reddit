<?php
/**
 * Reddit Project Utilities 
 * Author: Addison Benzshawel
 * Description: Various classes/methods used throughout the site
 */

// The following class is used to connect to mysql database reddit_project
class db_connect extends mysqli{
	public function __construct($host, $user, $pass, $db) {
		parent::__construct($host, $user, $pass, $db);

		if (mysqli_connect_error()) {
			die('Connect Error(' . mysqli_connect_errorno() . ')' . mysqli_connect_error());
		}
	}
} # END CLASS

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
            if(! $res){
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

// class for everything post related (creating, viewing, editing)
class posts {
	private $db;
	private $title;
	private $content;
	private $url;
	private $userid;
	private $postid;
    private $today;

	public function __construct(){
		$this->db = new db_connect('localhost', 'site_admin', '5QNuvacQHLS74a8E', 'reddit_project');
        $this->today = new DateTime();
	}

	public function new_post($title, $content, $url, $userid){
		$this->title = $this->db->real_escape_string($title);
		$this->content = $this->db->real_escape_string($content);
		$this->url = $this->db->real_escape_string($url);
		$this->userid = $userid;
		$query = "INSERT INTO posts (title, content, url, userid) VALUES (?, ?, ?, ?)";
		$stmt = $this->db->prepare($query);
		$stmt->bind_param('sssi', $this->title, $this->content, $this->url, $this->userid);
		$status = $stmt->execute();
		if ($status != false){
            $stmt->close();
            return "Success! your post has been created.";
		} else {
            $stmt->close();
            return 'Sorry something went wrong. Please try again. <br/>Error: ' . $stmt->error ;
		}
	}
	// the post_name method allows you to look up posts by userid ($idtype = userid) or
	// postid ($idtype = postid).
	public function post_title ($id, $idtype) {
		$id = $this->db->real_escape_string($id);
		$idtype = $this->db->real_escape_string($idtype);
		if($idtype == 'userid'){
			$query = "SELECT title FROM posts WHERE userid = '" . $id . "'";
		} elseif($idtype == 'postid'){
			$query = "SELECT title FROM posts WHERE postid = '" . $id . "'";
		} else {
			return "Invalide parameters";
		}
		$res = $this->make_query($query);
		if($res != NULL){
			return $res['title'];
		} else {
			return "sorry something went wrong";
		}
	}

    // post author
    public function author($postid){
        $this->postid = $this->db->real_escape_string($postid);
        $query = "SELECT `username` FROM `posts` JOIN `users` ON `posts`.`userid` = `users`.`userid` WHERE `postid` = ". $this->postid;
        $res = $this->make_query($query);
        return  $res[0]['username'];
    }
	// the post_content method allows you to look up posts by userid ($idtype = userid) or
	// postid ($idtype = postid).
	public function post_content($id, $idtype) {
        $this->userid = intval($this->db->real_escape_string($id));
        $type = $idtype;

        if($type == 'userid'){
            $query = "SELECT * FROM `users` JOIN `posts` ON `users`.`userid` = `posts`.`userid`";
            $res = $this->make_query($query);
            foreach($res as $entry){
                if($entry['userid'] == $this->userid){
                    $values[] = array('content' =>$entry['content'], 'username' => $entry['username'], 'title' =>$entry['title'], 'postid' =>$entry['postid']);

                }
            }
            if(!isset($values)){
                $values = "There doesn't seem to be anything here.";
            }
            return $values;
        } elseif($type == 'postid'){
            $query = "SELECT * FROM `users` JOIN `posts` ON `users`.`userid` = `posts`.`userid`";
            $res = $this->make_query($query);
            foreach($res as $entry){
                if($entry['postid'] == $this->userid){
                    $values[] = array('content' =>$entry['content'], 'username' => $entry['username'], 'title' =>$entry['title'], 'postid' =>$entry['postid']);

                }
            }
            if(!isset($values)){
                $values = "There doesn't seem to be anything here.";
            }
            return $values;
        } else {
            return "Invalid arguments!";
        }
    }
	// the post_URL method allows you to look up posts by userid ($idtype = userid) or
	// postid ($idtype = postid).
	public function post_URL($id, $idtype) {
		$id = $this->db->real_escape_string($id);
		$idtype = $this->db->real_escape_string($idtype);
		if($idtype == 'userid'){
			$query = "SELECT url FROM posts WHERE userid = '" . $id . "'";
		} elseif($idtype == 'postid'){
			$query = "SELECT url FROM posts WHERE postid = '" . $id . "'";
		} else {
			return "Invalide parameters";
		}
		$res = $this->make_query($query);
		if($res != NULL){
			return $res['url'];
		} else {
			return "sorry something went wrong";
		}
	}

    // Display all posts in a paticular subreddit
    // if subreddit = all then all posts displayed
    // @param$subreddit = string subreddit name
    public function all_posts($subreddit){
        if($subreddit == 'all'){
            $query = 'SELECT * FROM posts';
        } else {
            $query = 'SELECT * FROM posts WHERE subreddit = ' . $subreddit;
        }
        $res = $this->make_query($query);
        if($res != NULL){
            return $res;
        } else {
            return "sorry there doesn't seem to be anything here.";
        }
    }
	// IMPORTANT: edit_post method arguments defined as follows:
	// $postid = integer, $values = name, $name = name is the table's
	// column name and value is the updated value.
	public function edit_post($postid, $values, $name){
		$this->postid = intval($postid);
		$values = $values;
		$name = $name;
		$stmt = $this->db->prepare("UPDATE posts SET " . $name . " = ? WHERE postid = ?");
		$stmt->bind_param('si', $values, $this->postid);
		$status = $stmt->execute();
		if( $status != false){
            $stmt->close();
            return 'Success - Your post has been edited!';
		} else {
            $stmt->close();
            return 'Sorry something went wrong. Please try again. <br/>Error:' . $stmt->error ;
		}
	}
    // post age
    public function age($postid){
        $this->postid = intval($this->db->real_escape_string($postid));
        $query = "SELECT date_post FROM posts WHERE postid = " . $this->postid;
        $date_created = $this->make_query($query);
        $todays_date = $this->today->format('Y-m-d H:i:s');//getTimestamp();
        $res[0] = strtotime($todays_date);
        $res[1] = strtotime($date_created[0]['date_post']);
        $res[2] = (string)round(($res[0]-$res[1])/60/60/24, 2) . " days";
        return $res[2];

    }
	private function make_query($query){
		if ( $result = $this->db->query($query) ) {
			while ($row = $result->fetch_assoc()) {
				$res[] = $row;
			}
		} else {
				return NULL;
		}
        return $res;
	}
} # END CLASS

// class for everything comment related
class comments{
	private $db;
	private $content;
	private $userid;
	private $postid;
    private $commentid;
    private $today;

	public function __construct(){
		$this->db = new db_connect('localhost', 'site_admin', '5QNuvacQHLS74a8E', 'reddit_project');
        $this->today = new DateTime();
	}

	public function new_comment($content, $userid, $postid){
		$this->content = $content;
		$this->userid = $userid;
		$this->postid = $postid;
		$query = "INSERT INTO comments (content, userid, postid) VALUES (?, ?, ?)";
		$stmt = $this->db->prepare($query);
		$stmt->bind_param('sii', $this->content, $this->userid, $this->postid);
		$status = $stmt->execute();
		if($status){
            $stmt->close();
            return 'Success - your comment has been created!';
		} else {
            $stmt->close();
            return 'Sorry something went wrong. Please try again. <br/>Error: ' . $stmt->error ;
		}

	}

    /**
     * Method to return either ALL of a users comments or ALL comments on a post
     * @param $id = integer value of userid OR postid
     * @param $type = type entered (usrid or postid)
     * Note only userid OR postid needed
     * @return if valid arguments returns multidimensional array['int index'] = array('content' => ''. 'username' => '')
     *          else returns "Invalid arguemnts"
     */
   public function comment_content($id, $type){
        $this->userid = intval($this->db->real_escape_string($id));
        $type = $type;

        if($type == 'userid'){
            $query = "SELECT * FROM `users` JOIN `comments` JOIN `posts` ON `users`.`userid` = `comments`.`userid` = `posts`.`userid`";
            $res = $this->make_query($query);
            foreach($res as $entry){
                if($entry['userid'] == $this->userid){
                    $values[] = array('content' =>$entry['content'], 'username' => $entry['username'], 'title' =>$entry['title'], 'commentid' => $entry['commentid'], 'date_commented' => $entry['date_comment'], 'postid' =>$entry['postid']);
                }
            }
            if(!isset($values)){
                $values = "There doesn't seem to be anything here.";
            }
            return $values;
        } elseif($type == 'postid'){
            $query = "SELECT * FROM `users` JOIN `comments` ON `users`.`userid` = `comments`.`userid`";
            $res = $this->make_query($query);
            foreach($res as $entry){
                if($entry['postid'] == $this->userid){
                    $values[] = array('content' =>$entry['content'], 'username' => $entry['username']);
                }
            }
            if(!isset($values)){
                $values = "There doesn't seem to be anything here.";
            }
            return $values;
        } else {
            return "Invalid arguments!";
        }
	}

    /*public function comment_content($id, $type){
        $this->userid = intval($this->db->real_escape_string($id));
        $type = (is_string($type)) ? $type : false;
        $select_set = '`users`.`userid`, `users`.`username`, `comments`.`commentid`, `comments`.`content`, `comments`.`userid`,
                      `comments`.`postid`, `comments`.`date_commented`, `comments`.`score`';
        $column = array('`users`.`userid`', '`users`.`username`', '`comments`.`commentid`', '`comments`.`content`', '`comments`.`userid`',
                        '`comments`.`postid`', '`comments`.`date_commented`', '`comments`.`score`');
        $table = '`users` JOIN `comments` ON `users`.`userid` = `comments`.`userid`';

        if($type == 'userid'){
            $whereArgs = '`comments`.`userid` = ?';
            $argTypes = array('i');
            $argVars = array($this->userid);
            // Return array of array with '`table_name`.`column_name`' => 'value'
            return $this->make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars);
        } elseif($type == 'postid'){
            $whereArgs = '`comments`.`postid` = ?';
            $argTypes = array('i');
            $argVars = array($this->postid);
            // Return array of array with '`table_name`.`column_name`' => 'value'
            return $this->make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars);
        } else {
            return "Invalid arguments!";
        }
    }*/

    public function comment_author($commentid){
		$this->commentid = $commentid;

        $query = "SELECT username FROM comments JOIN users ON
                    `comments`.userid = `users`.`userid` WHERE commentid = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $this->commentid);
		$status = $stmt->execute();
		if($status){
			$stmt->bind_result($res);
			$stmt->fetch();
            $stmt->close();
            return $res;
		} else {
			return 'Sorry something went wrong. Please try again. <br/>Error: ' . $stmt->error ;
		}
	}

    // comment age
    public function age($commentid){
        $this->commentid = intval($commentid);
        // Declare prepared query parameters
        $select_set = 'date_comment';
        $column = array('date_comment');
        $table = 'comments';
        $whereArgs = 'commentid = ?';
        $argTypes = array('i');
        $argVars = array($this->commentid);
        // Get date created
        $date_created = $this->make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars);
        // Get today's date
        $todays_date = $this->today->format('Y-m-d H:i:s');//getTimestamp();
        // Convert dates to timestamps
        $res[0] = strtotime($todays_date);
        $res[1] = strtotime($date_created[0]['date_comment']);
        // Get change in time
        $etime = $res[0] - $res[1] - 7*3600; //adjusted for Central Time Zone
        // If time less than 1 second return 0 seconds
        if ($etime < 1){
            return '0 seconds';
        }
        // Prepare array for comparing/calculating time in different units
        $time_units = array( 12 * 30 * 24 * 60 * 60  =>  'year', 30 * 24 * 60 * 60 => 'month',
              24 * 60 * 60 => 'day', 60 * 60  => 'hour', 60 => 'minute', 1 =>  'second' );
        foreach ($time_units as $secs => $str){
            $time_value = $etime / $secs;
            if ($time_value <= 48){
                $rounded_time = round($time_value, 1);
                $res[2] =  $rounded_time . ' ' . $str . ($rounded_time > 1 ? 's' : '') . ' ago';
            }
        }
        // Return comment age as string
        return $res[2];
    }

    // Display all comments in a paticular post
    public function all_comments($postid, $sort="new"){
        $this->postid = $this->db->real_escape_string($postid);
        // Decalre prepared query parameters
        $select_set = '*';
        $table = '`posts` JOIN `comments` ON `posts`.`postid` = `comments`.`postid`';
        $column = array('`posts`.`postid`', '`posts`.`date_post`', '`posts`.`title`', '`posts`.`url`', '`posts`.`content`', '`posts`.`score`', '`posts`.`userid`', '`comments`.`commentid`', '`comments`.`content`', '`comments`.`userid`', '`comments`.`postid`' , '`comments`.`date_commented`', '`comments`.`score`');
        $whereArgs = '`posts`.`postid` = ?';
        $argTypes = array('s');
        $argVars = array($this->postid);
        // return results as array of arrays with "column_name" => "value"
        $all_comments = $this->make_prepared_query($select_set, $column, $table, $whereArgs,$argTypes,$argVars);
        if(is_array($all_comments)){
            return array_reverse($all_comments);
        } else {
            return $all_comments;
        }
    }
    // OLD WAY in process of phasing out. Use make_prepared_query in the future
    private function make_query($query){
        $res = array();
        if ( $result = $this->db->query($query) ) {
            while ($row = $result->fetch_assoc()) {
                $res[] = $row;
            }
        } else {
            return NULL;
        }
        return $res;
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
            if(! $res){
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
            if(isset($query_result)){
                return $query_result;
            } else {
                return "There doesn't seem to be anything here";
            }
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

class user_session {
    public $user_session_id;
    public $userid;
    private $timestamp;

    public function __construct(){
        session_start();
        $this->timestamp = time();
    }

    // Only use this method only after a user's username/password have been verified!!
    public function start_user_session($userid){
        $this->userid = $userid;
        $_SESSION['userid'] = $this->userid;
        $_SESSION['startTime'] = $this->timestamp;
    }

    public function logout_user(){
        session_destroy();
        return "You have successfully been logged out!";
    }
}

##
# Testing area
# DELETE OR COMMENT OUT IF NOT IN DEVELOPMENT
#


?>