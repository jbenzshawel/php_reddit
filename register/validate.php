<!doctype HTML>
<html>
<head>
	<meta charset="utf-8" />
	<title>Register</title>
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
		<div class="register">
			<?php
				// include utilities
				include('../utilities.php');
				$users = new users();
				$valid = true;
				// validate email, compare passwords, and check if username unique (for some readon elseifs weren't working)
				if(!$users->is_unique($_POST['username'], 'username')){
					$errors[] = "Sorry the username " . $_POST['username'] . " is already taken";
					$valid = false;
				} 
				if ($_POST['password'] != $_POST['verifyPassword']){
					$errors[] = "Your passwords did not match!";
					$valid = false;
				} 
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
					$errors[] = "Please enter a valid email address!";
					$valid = false;
				}

				// if valid input create new user
				if($valid){
					$res = $users->new_user($_POST['name'], $_POST['username'], $_POST['password'], $_POST['email']);
					echo $res;
					echo '<a href="../user">Click here to login!</a></p>';
				} else {
			?>
			<h3>Something's not right:</h3>
			<p style="color:red;">
			<?php	foreach($errors as $error){
						echo $error . "<br/>";
					}
					echo '<br/><a href="index.php">Try again?</a></p>';
				}
			?>
		</div>

	</div>
</body>
</html>