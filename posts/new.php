<!doctype html> 
<html>
<head>
	<meta charset="utf-8" />
    <?php
        include ('../utilities.php');
        $session = new user_session();
        if(isset($_SESSION['userid'])){
            $user = new get_user_info($_SESSION['userid']);
            $comments = new comments();
        } else {
            $user = "";
        }
        $link_type = (isset($_GET['type'])) ? $_GET['type'] : 'self';
    ?>
    <title>PHP Reddit - New <?php echo $link_type; ?> post </title>
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
                if($link_type == 'link'):
            ?>
                    <div class="selfpost">
                        <!--NEW LINK POST FORM-->
                        <form action="submit.php" method="POST" >
                            <label for="title"><span class="bigger">Title</span></label>
                            <textarea type="text" id="title" name="title"></textarea>

                            <label for="link"><span class="bigger">Link</span></label>
                            <input type="text" id="link" name="url" />

                            <label for="subreddit"><span class="bigger">Subreddit</span></label>
                            <input type="text" id="subreddit" name="subreddit" />

                            <button style="margin:5px auto; display: block;" class="large-button" type="submit" >Save</button>
                        </form>
                    </div>
            <?php
                else:
            ?>
			<div class="selfpost">
				<!--NEW SELF POST FORM-->
				<form action="submit.php" method="POST" >
					<label for="title"><span class="bigger">Title</span></label>
					<textarea type="text" id="title" name="title"></textarea>
					
					<label for="content"><span class="bigger">Content</span>(optional)</label>
					<textarea id="content" name="content" style="height:250px;"></textarea>
					
					<label for="subreddit"><span class="bigger">Subreddit</span></label>
					<input type="text" id="subreddit" name="subreddit" />

					<button style="margin:5px auto; display: block;" class="large-button" type="submit" >Save</button>
				</form>
			</div>
            <?php endif; ?>
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