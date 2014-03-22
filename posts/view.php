<!doctype html>
<?php
    require_once('../utilities.php');
    $session = new user_session();
    $posts_page_title = new posts();
    $page_title = (isset($indi_postid)) ? $posts_page_title->post_title($indi_postid, 'postid') : 'All posts';
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>PHP Reddit - <?php echo $page_title;?></title>
	<!--Stylesheets-->
	<link rel='stylesheet' href="../style.css" />
	<!--Fonts-->
	<link href='http://fonts.googleapis.com/css?family=Exo+2:300' rel='stylesheet' type='text/css'>
    <!--INCLUDE JQUERY-->
    <scripts src="../scripts/jquery-1.11.0.min.js" type="text/javascript"></scripts>
    <?php
        if(isset($_SESSION['userid'])){
            $user = new get_user_info($_SESSION['userid']);
        }
        if(isset($_GET['postid'])){
            $indi_postid = $_GET['postid'];
        }
        $users = new users();
        $comments = new comments();
        $posts = new posts();
    ?>
</head>
<body>
	<?php

		if($_POST){
            /**
             * The logic below is used to keep track of page refresh preventing sending the same comment twice
             * Multiple comments are allowed without page reload however if the timestamp of two comments are
             * the same an error alert will pop up and the page will refresh. (updated to be based on time rather than
             * number of comments)
             */
            // if you haven't made a comment or are making a second comment
            if(!isset($_SESSION['last_comment_timestamp'] ) or isset($_SESSION['new_comment_content'])){
                $date = new DateTime('now');
                // if a user is making a new comment, make it for them
                if(!isset($_SESSION['new_comment-content']) and !isset($_SESSION['last_comment_timestamp'])){
                    $_SESSION['last_comment_timestamp'] = $date->getTimestamp();
                    echo '<div class="dev-output"><ul><li>$_POST variables used. Possible duplicate entries!</li>';
                    $new_comment_content = htmlspecialchars($_POST['commentContent'], ENT_QUOTES);
                    $comment_result = $comments->new_comment($new_comment_content, $_SESSION['userid'], $indi_postid);
                    echo "<li>$comment_result</li>";
                    $_SESSION['new_comment_content'] = $new_comment_content;
                    echo "</ul></div>";
                 // if the previous comment does not equal the new comment and this is your second comment
                } elseif($_SESSION['new_comment_content'] != $_POST['commentContent'] and isset($_SESSION['last_comment_timestamp'])){
                    if($date->getTimestamp() != $_SESSION['last_comment_timestamp']){
                        echo '<div class="dev-output"><ul><li>$_POST variables used. Possible duplicate entries!</li>';
                        $new_comment_content = htmlspecialchars($_POST['commentContent'], ENT_QUOTES);
                        $comment_result = $comments->new_comment($new_comment_content, $_SESSION['userid'], $indi_postid);
                        echo "<li>$comment_result</li>";
                        $_SESSION['last_comment_timestamp'] = $date->getTimestamp();
                        echo "</ul></div>";
                    } else {
                        echo '<script type="text/javascript">';
                        echo 'alert("You have been commenting a lot recently! Please wait and try again. ");';
                        echo 'location.reload();';
                        echo '</script>';
                    } // End error statement
                } // End second comment statement
            } // End new comment statement
        } // End check for POST variables
	?>
	<!--HEADER-->
	<header class="main-header">
		<div class="subreddits">
			<ul>
				<li><a href="#">My Subreddits</a></li>
				<li>-</li>
				<li><a href="#">Dashboard</a></li>
				<li>-</li>
				<li><a href="#">front</a></li>
				<li>-</li>
				<li><a href="#">all</a></li>
				<li>-</li>
				<li><a href="#">random</a></li>
				<li>-</li>
				<li><a href="#">friends</a></li>
				<li>|</li>
				<li><a href="#">AskReddit</a></li>
				<li>-</li>
				<li><a href="#">AskScience</a></li>
				<li>-</li>
				<li><a href="#">40daysofruby</a></li>
				<li>-</li>
				<li><a href="#">Aww</a></li>
				<li>-</li>
				<li><a href="#">Battlefield3</a></li>
				<li>-</li>
				<li><a href="#">Trees</a></li>
				<li>-</li>
				<li><a href="#">TrueReddit</a></li>
				<li>-</li>
				<li><a href="#">Science</a></li>
				<li class="subreddit-nav"><a href="#">Edit >></a></li>
			</ul>
		</div>
		<div class="top-nav view">
			<a href="../index.html"><img src="../images/reddit.png" alt="reddit!"/></a>
			<ul>
				<li class="active"><a href="#">hot</a></li>
				<li><a href="#">new</a></li>
				<li><a href="#">rising</a></li>
				<li><a href="#">controversial</a></li>
				<li><a href="#">top</a></li>
				<li><a href="#">gilded</a></li>
			</ul>
			<ul class="profile">
				<li>
					<?php if(isset($_SESSION['userid'])):?>
                    <a href="../user/profile.php?user=<?php echo $_SESSION['userid'];?>">
                    <?php
					    echo $user->username();

                    ?></a> ()</li>
				<li>|</li>
				<li><a href="#"><img src="../images/message.png" alt="message" /></a></li>
				<li>|</li>
				<li><a href="#"><strong>preferences</strong></a></li>
				<li><?php
                    endif;
                    if(isset($_SESSION['userid'])): ?><a href="../user/logout.php">logout</a><?php else: ?>Want to join? <a href="../register">register in seconds</a><?php endif;?></li>
			</ul>
		</div>
	</header><!--END HEADER-->

	<!--CONTENT WRAPPER-->
	<div id="wrapper">
	<!--MAIN CONTENT-->
		<div class="main-content">
            <?php
				// If individual postid set show that post 
				if(isset($indi_postid)):
					$post = $posts->post_content($indi_postid, 'postid');
                    $new_users = new users();
			?>
                <!--START INDI POST-->
				<div class="indi_post">
                    <article class="story">
                        <div class="rank">
                            <!--<p style="float:left; display:none;">1</p>hidden-->
                            <ul style="float:right;">
                                <li><img src="../images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
                                <li>290</li>
                                <li><img src="../images/downvote.PNG" alt="downvote"/></li>

                            </ul>
                        </div>
                        <div class="info">
                            <ul>
                                <li class="title"><a href="#"><?php echo $post[0]['title']; ?></a>(self.all)
                                    <ul>
                                        <li class="post-info">(<span class="orange">300</span>|<span class="blue">10</span>) <?php  echo $posts->age($post[0]['postid']) . " "; ?><a href="../user/profile.php?user=<?php echo $users->id($post[0]['username']); ?>"><?php echo $post[0]['username']; ?></a>
                                            <ul class="story-links">
                                                <li><a href="#">10 comments</a></li>
                                                <li><a href="#">share</a></li>
                                                <li><a href="#">save</a></li>
                                                <li><a href="#">hide</a></li>
                                                <li><a href="#">report</a></li>
                                                <li><a href="#">[l=c]</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                            </ul>
                        </div>
                    </article>
                    <?php if(strlen($post[0]['content']) > 0): ?>
					<p><?php echo $post[0]['content']; ?></p>
                    <?php endif; ?>
				</div><!--END INDI POST INFO SECTION-->
                <!--START INDI COMMENTS-->
                <div class="indi_comments">
                    <?phP if(isset($user)): ?>
                        <div class="new_indi_comment">
                            <p>commenting as: <?php echo $user->username(); ?></p>
                            <form action="" method="POST">
                                <label name="commentContent"></label>
                                <textarea id="commentContent" name="commentContent"></textarea>
                                <button class="large-button" type="submit">Save</button>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php
                        // Get all comments
                        $comments_fix = new comments(); // needed no comments class to run comment methods inside loop
                        $comments_content = $comments_fix->all_comments($indi_postid);
                        if(is_array($comments_content)):
                            foreach($comments_content as $key => $comment):
                                $comments_fix = new comments(); // needed no comments class to run comment methods inside loop
                              /*  if(isset($new_indi_comment_id)){
                                    if($comment['`comments`.`commentid`'] == $new_indi_comment_id){
                                        break;
                                    }
                                }*/
                    ?>
                                <ul class="comment">
                                    <li><a href="../user/profile.php?user=<?php echo $comment['`comments`.`userid`']; ?>" ><?php echo $users->username($comment['`comments`.`userid`']); ?></a><?php echo $comments_fix->age($comment['`comments`.`commentid`']); ?></li>
                                    <li><p><?php echo $comment['`comments`.`content`']; ?></p></li>
                                    <?php if(isset($user)): ?><li><a href="#" onclick="javascript:newComment(); return false;" id="newCommentReply">reply</a></li><?php endif; ?>

                                    <!--COMMENT REPLY-->
                                    <div id="commentReply">
                                        <div class="new_indi_comment_reply">
                                            <form action="" method="POST">
                                                <label name="commentContent"></label>
                                                <textarea id="commentContent" name="commentContentReply"></textarea>
                                                <button class="large-button" type="submit">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </ul>

                    <?php
                            endforeach;
                        // If no comments tell user there isn't anything there
                        else:
                            echo "<p>$comments_content</p>";
                        endif;
                    ?>
				</div><!--END COMMENTS SECTION-->
            <?php
				// Else show all posts 	
				else:
                    echo "<!--ALL POSTS SECTION-->\n";
					foreach($posts->all_posts('all') as $post):
                        $all_posts = new posts();
                        // for each post get author info
                        $post_author = new get_user_info($post['userid']);
                        $post_comments = new comments();
                        $post_url = (strlen($post['url']) > 5) ? $post['url'] : "view.php?postid=" . $post['postid'];
                        // echo comment
                        echo '<!--POST ID ' . $post['postid'] . ' -->'."\n"; ?>
                        <article class="story">
                            <div class="rank">
                                <!--<p style="float:left; display:none;">1</p>hidden-->
                                <ul style="float:right;">
                                    <li><img src="../images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
                                    <li>290</li>
                                    <li><img src="../images/downvote.PNG" alt="downvote"/></li>

                                </ul>
                            </div>
                            <!--POST INFO-->
                            <div class="info">
                                <ul>
                                <li class="title"><a href="<?php echo $post_url; ?>" target="_blank"><?php echo $post['title']; ?></a>(self.all)
                                        <ul>
                                            <li class="post-info">(<span class="orange">300</span>|<span class="blue">10</span>) <?php echo $all_posts->age($post['postid']) . " "; ?><a href="../user/profile.php?user=<?php echo $post['userid']; ?>"><?php echo $post_author->username(); ?></a>
                                                <ul class="story-links">
                                                    <?php
                                                        // fetch number of comments
                                                        $number_comments = count($post_comments->all_comments($post['postid']));
                                                        $number_comments = ($number_comments > 0 ) ? $number_comments : 0;
                                                    ?>
                                                    <li><a href="<?php echo "view.php?postid=" . $post['postid']; ?>"><?php echo $number_comments; ?>&nbsp;comments</a></li>
                                                    <li><a href="#">share</a></li>
                                                    <li><a href="#">save</a></li>
                                                    <li><a href="#">hide</a></li>
                                                    <li><a href="#">report</a></li>
                                                    <li><a href="#">[l=c]</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                </ul>
                            </div>
                        </article><!--END POST-->
				<?php endforeach; 
					  endif;
				?>
		</div>
		<!--SIDEBAR-->
		<div class="sidebar">
            <input type="text" placeholder="search reddit">
            <?php if(!isset($_SESSION['userid'])): ?>
                <!--LOGIN-->
                <form  action="../login/validate.php" method="post" class="login">
                    <input type="text" name="username" placeholder="username">
                    <input type="password" name="password" placeholder="password"><br/>
                    <input type="checkbox" name="rememberme" id="rememberme"/>Remember me
                    <input type="submit" value="login" >
                </form>
            <?php else: ?>

            <div class="big-button">
                <a href="new.php?type=link">Submit a new link</a>
            </div>
            <div class="big-button">
                <a href="new.php">Submit a new text post</a>
            </div>
            <?php endif;
            ?>
            <div>
                <h4>Previously Viewed Links</h4>
                <ul>
                </ul>
            </div>
		</div><!--END SIDEBAR-->
	</div> <!--END MAIN WRAPPER-->
	<!--FOOTER-->
	<footer>
		<div class="footer-info">
			<div class="four-col">
				<h4>about</h4>
					<ul>
						<li><a href="#">blog</a></li>
						<li><a href="#">about</a></li>
						<li><a href="#">team</a></li>
						<li><a href="#">source code</a></li>
						<li><a href="#">advertise</a></li>
					</ul>
			</div>
			<div class="four-col">
				<h4>help</h4>
					<ul>
						<li><a href="#">wiki</a></li>
						<li><a href="#">FAQ</a></li>
						<li><a href="#">reddiqute</a></li>
						<li><a href="#">rules</a></li>
						<li><a href="#">contact us</a></li>
					</ul>				
			</div>
			<div class="four-col">
				<h4>tools</h4>
					<ul>
						<li><a href="#">mobile</a></li>
						<li><a href="#">firefox extension</a></li>
						<li><a href="#">chrome extension</a></li>
						<li><a href="#">buttons</a></li>
						<li><a href="#">widget</a></li>
					</ul>				
			</div>
			<div class="four-col">
				<h4>3</h4>
					<ul>
						<li><a href="#">reddit gold</a></li>
						<li><a href="#">store</a></li>
						<li><a href="#">reddit gifts</a></li>
						<li><a href="#">reddit.tv</a></li>
						<li><a href="#">radio reddit</a></li>
					</ul>					
			</div>
		</div>
        <!--SITE DISCLAIMER-->
		<p style="text-align:center; font-size:14px; padding:5px;">This website is in no way affiliated with Reddit.com </p>p>
    </footer>
    <!--SITE SCRIPTS-->
    <script>
        var count = 1;
        console.log("javscript");
        function newComment(){
            count +=1;
            if(count%2==0){
                console.log("click");
                    var css = document.createElement("style");
                    css.type = "text/css";
                    css.innerHTML = "#commentReply { display: block }";
                    document.body.appendChild(css);
            } else {
                console.log("click");

                document.getElementById("newCommentReply").style.display = "none";
            }
            return false;
        }
    </script>

</body>
</html>