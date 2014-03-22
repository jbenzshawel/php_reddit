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

// Include users, posts, and comments classes
require_once('users.php');
require_once('posts.php');
require_once('comments.php');

// Sessions class
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

echo $posts->edit_post('4', 'Comment on postid 4 has been edited*', '`title`');