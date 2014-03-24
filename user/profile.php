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
		include ('../utilities.php');
		$session = new user_session();
		$profile = $_GET['user'];
		$user = new get_user_info(intval($profile));
		$comments = new comments();
		$posts = new posts();
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
			<!--DISPLAY USER COMMENTS-->
			<?php

				$user_comments = $comments->comment_content($profile, 'userid');
				$user_posts = $posts->post_content($profile, 'userid');
				if(is_array($user_comments)){
					foreach($user_comments as $comment){
						echo '<ul class="comment">';
						echo '<li><a href="">' . $comment['title'] . '</a> by author</li>'; //$posts->post_content($comment['username'], 'username')</li>';
						echo '<li><a href="profile.php?user=" >' . $comment['username'] . '</a>' . $comments->age($comment['commentid']) . '</li>';
						echo '<li><p>' . $comment['content'] . '</p></li>';
						echo '</ul>';
					}
				} 
				if(is_array($user_posts)){
					foreach($user_posts as $post){
						echo '<div class="comment">';
						echo '<a>' . $post['title'] . '</a>';
						echo '<p>' . $post['content'] . '</p>';
						echo '</div>';
					}
				} else {
					echo "<br/>something went wrong retrieving posts..."; 	
				}

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
		<img src="../images/balloons.PNG" alt="balloons" />
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
				<h4><3</h3>
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