<?php
	include('../utilities.php'); 
	$user = new users();
	$session = new user_session();

?>
<!doctype html> 
<html>
<head>
	<meta charset="utf-8" />
    <!--REDIRECT TO POSTS AFTER LOGIN-->
    <meta http-equiv="refresh" content="1;url=../posts/view.php" />
	<title>PHP Reddit - 1</title>
	<!--Stylesheets-->
	<link rel='stylesheet' href="../style.css" />
	<!--Fonts-->
	<link href='http://fonts.googleapis.com/css?family=Exo+2:300' rel='stylesheet' type='text/css'>

</head>
<body>
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
			<a href="index.html"><img src="../images/reddit.png" alt="reddit!"/></a>
			<h2>Assignment 1</h2>
			<ul>
				<li class="active"><a href="#">hot</a></li>
				<li><a href="#">new</a></li>
				<li><a href="#">rising</a></li>
				<li><a href="#">controversial</a></li>
				<li><a href="#">top</a></li>
				<li><a href="#">gilded</a></li>

			</ul>
			<ul class="profile">
				<li>Want to join? <a href="#">register in seconds</a></li>
			</ul>
		</div>
	</header><!--END HEADER-->
	<!--START MAIN CONTENT-->
	<div id="wrapper">
		<div class="main-content">
		<?php 
			$user_login = $user->check_login($_POST["username"], $_POST["password"]);
			if($user_login != NULL){
				echo "Success! " . $user_login["username"] . " has logged in."; 
				$session->start_user_session($user_login['userid']);
			} else {
				echo "login failed";
			}
			?>
		</div>
	</div>
</body>
</html>
