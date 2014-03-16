<?php
/**
 * Reddit Project Posts Class
 * Author: Addison Benzshawel
 * Date: 3/16/14
 */

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

            return 'Sorry something went wrong. Please try again. <br/>Error: ' . $stmt->error ;
        }
    }
    // the post_name method allows you to look up posts by userid ($idtype = userid) or
    // postid ($idtype = postid).
    public function post_title ($id, $idtype) {
        $idtype = $this->db->real_escape_string($idtype);
        $select_set = "title";
        $column = array('posts');
        $table = 'posts';
        if($idtype == 'userid'){
            $whereArgs = 'userid = ?';
            $argTypes = array('s');
            $argVars = array($id);
            $prepared_query = $this->make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars);
        } elseif($idtype == 'postid'){
            $whereArgs = 'postid = ?';
            $argTypes = array('s');
            $argVars = array($id);
            $prepared_query = $this->make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars);
        } else {
            return "Invalide parameters";
        }

        if(is_array($prepared_query)){
            return $prepared_query[0]['posts'];
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
        // Decalre prepared query parameters
        $select_set = '*';
        $table = '`posts`';
        $column = array('`posts`.`postid`', '`posts`.`date_post`', '`posts`.`title`', '`posts`.`url`', '`posts`.`content`', '`posts`.`score`', '`posts`.`userid`');
        if($subreddit == 'all'){
           $all_comments = $this->make_query("SELECT $select_set FROM $table");
        } else {
           $whereArgs = 'subreddit =  ?';
            $argVars = array($subreddit);
            $argTypes = array('s');
            // return results as array of arrays with "column_name" => "value"
            $all_comments = $this->make_prepared_query($select_set, $column, $table, $whereArgs,$argTypes,$argVars);
        }

        if(is_array($all_comments)){
            return array_reverse($all_comments);
        } else {
            return $all_comments;
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
    // comment age
    public function age($postid){
        $this->postid = intval($postid);
        // Declare prepared query parameters
        $select_set = 'date_post';
        $column = array('date_post');
        $table = 'posts';
        $whereArgs = 'postid = ?';
        $argTypes = array('i');
        $argVars = array($this->postid);
        // Get date created
        $date_created = $this->make_prepared_query($select_set, $column, $table, $whereArgs, $argTypes, $argVars);
        // Get today's date
        $todays_date = $this->today->format('Y-m-d H:i:s');//getTimestamp();
        // Convert dates to timestamps
        $res[0] = strtotime($todays_date);
        $res[1] = strtotime($date_created[0]['date_post']);
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

    // Depricated use make_prepared_query!
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
