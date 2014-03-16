<!doctype html> 
<html>
<head>
	<meta charset="utf-8" />
    <!--REDIRECT TO POSTS PAGE AFTER LOGOUT-->
    <meta http-equiv="refresh" content="2;url=../posts/view.php" />
	<title>PHP Reddit - </title>
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
			<a href="../index.html"><img src="../images/reddit.png" alt="reddit!"/></a>
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
				<li><a href="#">iowa116</a> (2646)</li>
				<li>|</li>
				<li><a href="#"><img src="../images/message.png" alt="message" /></a></li>
				<li>|</li>
				<li><a href="#"><strong>preferences</strong></a></li>
				<li><a href="#">logout</a></li>
			</ul>
		</div>
	</header><!--END HEADER-->
	<!--START MAIN CONTENT-->
	<div id="wrapper">
		<div class="main-content">
			<?php
				include ('../utilities.php');
				$session = new user_session();
				echo '<p>';
				echo $session->logout_user();
				echo '</p>';

			?>


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