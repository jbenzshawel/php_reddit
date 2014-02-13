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
		$this->db = new db_connect('localhost', 'site_admin', 'hoenikkerice09', 'reddit_project');
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
		$this->db = new db_connect('localhost', 'site_admin', 'hoenikkerice09', 'reddit_project');
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

	private function make_query($query){
		if ( $result = $this->db->query($query) ) {
			while ($row = $result->fetch_assoc()) {
				return $row;
			}
		} else {
				return NULL;
		}

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

	public function __construct(){
		$this->db = new db_connect('localhost', 'site_admin', 'hoenikkerice09', 'reddit_project');
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
	
	// the post_content method allows you to look up posts by userid ($idtype = userid) or 
	// postid ($idtype = postid). 
	public function post_content($id, $idtype) {
		$id = $this->db->real_escape_string($id);
		$idtype = $this->db->real_escape_string($idtype);
		if($idtype == 'userid'){
			$query = "SELECT content FROM posts WHERE userid = '" . $id . "'";
		} elseif($idtype == 'postid'){
			$query = "SELECT content FROM posts WHERE postid = '" . $id . "'";
		} else {
			return "Invalide parameters";
		}
		$res = $this->make_query($query);
		if($res != NULL){
			return $res[0]['content'];
		} else {
			return "sorry something went wrong";
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
	// IMPORTANT: edit_post method arguments defined as follows: 
	// $postid = integer, $values = name, $name = name is the table's 
	// column name and value is the updated value. 
	public function edit_post($postid, $values, $name){
		$this->postid = intval($postid);
		$values = $values;
		$name = $name;
		$stmt = $this->db->prepare("UPDATE posts SET " . $key . " = ? WHERE postid = ?");
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

	public function __construct(){
		$this->db = new db_connect('localhost', 'site_admin', 'hoenikkerice09', 'reddit_project');	
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
			$query = "SELECT * FROM `users` JOIN `comments` ON `users`.`userid` = `comments`.`userid`";
            $res = $this->make_query($query);
            foreach($res as $entry){
              if($entry['userid'] == $this->userid){
                  $values[] = array('content' =>$entry['content'], 'username' => $entry['username']);
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

$posts = new posts();

echo var_dump($posts->post_content(13, 'userid'));

?>