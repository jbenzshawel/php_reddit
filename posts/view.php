<!doctype html> 
<html>
<head>
	<meta charset="utf-8" />
	<title>40 Days of Ruby-Assignment 1</title>
	<!--Stylesheets-->
	<link rel='stylesheet' href="../style.css" />
	<!--Fonts-->
	<link href='http://fonts.googleapis.com/css?family=Exo+2:300' rel='stylesheet' type='text/css'>
</head>
<body>
	<?php
		require_once('../utilities.php');
		$session = new user_session();
		if(isset($_SESSION['userid'])){
			$user = new get_user_info($_SESSION['userid']);
		} 
		if(isset($_GET['postid'])){
			$indi_postid = $_GET['postid'];
		}
		$users = new users();
		$comments = new comments();
		$posts = new posts();

		if($_POST){
			$new_comment_content = $_POST['commentContent'];
			$comment_result = $comments->new_comment($new_comment_content, $_SESSION['userid'], $indi_postid);
		}
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
		<div class="top-nav">
			<a href="../index.html"><img src="../images/reddit.png" alt="reddit!"/></a>
			<h2><?php if(isset($_SESSION['userid'])){ 
						echo $user->username(); 
					  } else { 
					  	echo ""; 
				}
				?></h2>
			<ul>
				<li class="active"><a href="#">hot</a></li>
				<li><a href="#">new</a></li>
				<li><a href="#">rising</a></li>
				<li><a href="#">controversial</a></li>
				<li><a href="#">top</a></li>
				<li><a href="#">gilded</a></li>

			</ul>
			<ul class="profile">
				<li><a href="#">
					<?php if(isset($_SESSION['userid'])): ?>
					<?php echo $user->username(); 
						  endif; ?></a> (2646)</li>
				<li>|</li>
				<li><a href="#"><img src="../images/message.png" alt="message" /></a></li>
				<li>|</li>
				<li><a href="#"><strong>preferences</strong></a></li>
				<li><a href="logout.php">logout</a></li>
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
                                        <li class="post-info">(<span class="orange">300</span>|<span class="blue">10</span>) <?php echo $posts->age($post[0]['postid']) . " "; ?><a href="../user/profile.php?user=<?php echo $users->id($post[0]['username']); ?>"><?php echo $post[0]['username']; ?></a>
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
					<p><?php echo $post[0]['content']; ?></p>
				</div>
                <div class="indi_comments">
                    <div class="new_indi_comment">
                        <p>commenting as: <?php echo $user->username(); ?></p>
                        <form action="" method="POST">
                            <label name="commentContent"></label>
                            <textarea id="commentContent" name="commentContent"></textarea>
                            <button class="large-button" type="submit">Save</button>
                        </form>
                    </div>
				<!--<?php
					// Individual post new comment section
					/*if(isset($new_comment_content) and isset($comment_result)):
						$new_indi_comment = new comments();
						$new_indi_comment_content = $new_indi_comment->comment_content($_SESSION['userid'], 'userid');
                        $new_indi_comment_id = $new_indi_comment_content[count($new_comment_content)-1]['commentid'];
						*/?>-->
						<!--<ul class="comment">-->

				<?php
                  //  endif;
                    // Get all comments
                    $comments_fix = new comments(); // needed no comments class to run comment methods inside loop
                    $comments_content = $comments_fix->all_comments($indi_postid);
					if(is_array($comments_content)):
						foreach($comments_content as $key => $comment):
                            $comments_fix = new comments(); // needed no comments class to run comment methods inside loop
                            if(isset($new_indi_comment_id)){
                                if($comment['`comments`.`commentid`'] == $new_indi_comment_id){
                                    break;
                                }
                            }
                            ?>
                        <ul class="comment">
							<li><a href="../user/profile.php?user=<?php echo $comment['`comments`.`userid`']; ?>" ><?php echo $users->username($comment['`comments`.`userid`']); ?></a><?php echo $comments_fix->age($comment['`comments`.`commentid`']); ?></li>
							<li><p><?php echo $comment['`comments`.`content`']; ?></p></li>
						</ul>
				<?php 
						endforeach;
					else:
						echo "<p>$comments_content</p>";
					endif;
				?>
				</div>
			<?php
				// Else show all posts 	
				else:
					foreach($posts->all_posts('all') as $post): 
					$post_author = new get_user_info($post['userid']);
			?>
				<!--STORY 1-->
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
						<li class="title"><a href="view.php?postid=<?php echo $post['postid']; ?>"><?php echo $post['title']; ?></a>(self.all)
								<ul>
									<li class="post-info">(<span class="orange">300</span>|<span class="blue">10</span>) <?php echo $posts->age($post['postid']) . " "; ?><a href="../user/profile.php?user=<?php echo $post['userid']; ?>"><?php echo $post_author->username(); ?></a>
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
				<?php endforeach; 
					  endif;
				?>
		</div>
			<!--SIDEBAR-->
		<div class="sidebar">
			<?php if(isset($_SESSION['userid'])): ?>
			<h2><?php echo $user->username(); ?> 
			<p><span class="superstrong">2,646</span> link karma</p>
			<p><span class="superstrong">3,605</span> comment karma</p>
			<div class="trophy">

			</div>
			<?php	endif; ?></h2>

		</div>
	</div>
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
				<h4><3</h4>
					<ul>
						<li><a href="#">reddit gold</a></li>
						<li><a href="#">store</a></li>
						<li><a href="#">reddit gifts</a></li>
						<li><a href="#">reddit.tv</a></li>
						<li><a href="#">radio reddit</a></li>
					</ul>					
			</div>
		</div>
		<p style="text-align:center; font-size:14px; padding:5px;">This website is in no way affiliated with Reddit.com and was created for an assignment for the 40DaysOfRuby reddit group</p>
	</footer>
</body>
</html>