<?php

class new_posts{
    public $id;
    public $idtype;
    public $result;
    private$db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=reddit_project;charset=utf8', 'site_admin', '5QNuvacQHLS74a8E');
    }

    public function content($id, $idtype){
        $this->id = intval($id);
        $this->idtype = $idtype;

        if($idtype == 'userid'){
        $query = "SELECT `posts`.`title`, `posts`.`content`, `users`.`username` FROM `users` JOIN `posts` ON `users`.`userid` = ?";
        } elseif($idtype == 'postid'){
            $query = "SELECT `posts`.`title`, `posts`.`content`, `users`.`username` FROM `users` JOIN `posts` ON `posts`.`postid` = ?";
        } elseif($idtype == 'commentid'){
            $query = "SELECT `posts`.`title`, `posts`.`content`, `users`.`username` FROM `users` JOIN `posts` ON `comments`.`postid` = ?";
        } else {
            return "Invalide parameters";
        }
        $stmt = $this->db->prepare($query);
        if ($stmt->execute( array($this->id) )){
            return $stmt->fetch();

         } else{
            return $stmt->errorInfo();
        }

    }
}


# Print prepared querey
$posts = new new_posts();
$post_content = $posts->content('20', 'postid');
echo var_dump($post_content);