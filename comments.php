<?php
/**
 * Reddit Project Comments Class
 * Author: Addison Benzshawel
 * Date: 3/16/14
 */
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
        $this->today = new DateTime('America/Chicago');;
    }

    public function new_comment($content, $userid, $postid, $parent_commentID = 0){
        $this->content = $content;
        $this->userid = $userid;
        $this->postid = $postid;
        $query = "INSERT INTO comments (content, userid, postid, parent_commentid) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('siii', $this->content, $this->userid, $this->postid, $parent_commentID);
        $status = $stmt->execute();
        if($status){
            $stmt->close();
            return 'Success - your comment has been created!';
        } else {
            return 'Sorry something went wrong. Please try again. <br/>Error: ' . $stmt->error ;
        }

    }

    // IMPORTANT: edit_post method arguments defined as follows:
    // $postid = integer, $values = name, $name = name is the table's
    // column name and value is the updated value.
    public function edit_comment($postid, $values, $name){
        $this->postid = intval($postid);
        $values = $values;
        $name = $name;
        $stmt = $this->db->prepare("UPDATE comments SET " . $name . " = ? WHERE postid = ?");
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
    /**
     * Method to return either ALL of a users comments or ALL comments on a post
     * @param $id = integer value of userid OR postid
     * @param $type = type entered (usrid or postid)
     * Note only userid OR postid needed
     * @return if valid arguments returns multidimensional array['int index'] = array('content' => ''. 'username' => '')
     *          else returns "Invalid arguemnts"
     */
    public function comment_content($id, $type, $fullArray = null){
        $this->userid = intval($this->db->real_escape_string($id));
        $type = $type;
        $fullArray = (isset($fullArray)) ? $fullArray : false;
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
        } elseif($type == 'commentid' and $fullArray == "true"){
            $query = "SELECT * FROM `users` JOIN `comments` ON `users`.`userid` = `comments`.`userid`";
            $res = $this->make_query($query);

            foreach($res as $entry){
                if($entry['commentid'] == $this->userid){
                    $values = $entry;
                }
            }
            if(!isset($values)){
                $values = "There doesn't seem to be anything here.";
            }
            return $values;
        } elseif($type == 'parent_commentid' and $fullArray == "true"){
            $query = "SELECT * FROM `users` JOIN `comments` ON `users`.`userid` = `comments`.`userid`";
            $res = $this->make_query($query);

            foreach($res as $entry){
                if($entry['parent_commentid'] == $this->userid){
                    $values = $entry;
                }
            }
            if(!isset($values)){
                $values = "There doesn't seem to be anything here.";
            }
            return $values;
        }elseif($type == 'commentid'){
            $query = "SELECT * FROM `users` JOIN `comments` ON `users`.`userid` = `comments`.`userid`";
            $res = $this->make_query($query);

            foreach($res as $entry){
                if($entry['commentid'] == $this->userid){
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


    // has parent comment

    public function has_parent_comment($commentid){
        $query = "SELECT * FROM `comments` where `commentid` = $commentid";
        $query_res = $this->make_query($query);
        $result = (is_array($query_res))? true : false;
        return $result;
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
      /*  $select_set = '*';
        $table = '`posts` JOIN `comments` ON `posts`.`postid` = `comments`.`postid`';
        $column = array('`posts`.`postid`', '`posts`.`date_post`', '`posts`.`title`', '`posts`.`url`', '`posts`.`content`', '`posts`.`score`', '`posts`.`userid`', '`comments`.`commentid`', '`comments`.`content`', '`comments`.`userid`', '`comments`.`postid`' , '`comments`.`date_commented`', '`comments`.`score`');
        $whereArgs = '`posts`.`postid` = ?';
        $argTypes = array('s');
        $argVars = array($this->postid);
        // return results as array of arrays with "column_name" => "value"
        $all_comments = $this->make_prepared_query($select_set, $column, $table, $whereArgs,$argTypes,$argVars); */
        $query = "SELECT * FROM `posts` JOIN `comments` ON `posts`.`postid` = `comments`.`postid` WHERE  `posts`.`postid` = $this->postid";
        $all_comments = $this->make_query($query);
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