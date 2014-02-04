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
			<!--STORY 1-->
			<div class="story">
				<div class="rank">
					<p style="float:left;">1</p> 
					<ul style="float:right;">
						<li><img src="images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
						<li>290</li>
						<li><img src="images/downvote.PNG" alt="downvote"/></li>

					</ul>
				</div>
				<div class="info">
					<ul>
						<li class="title"><a href="#">Are you ready for the truth?</a>(self.assignment1)
							<ul>
								<li class="post-info">(<span class="orange">300</span>|<span class="blue">10</span>) submitted 4 hours ago by <a href="#">user</a>
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
			</div>
			<!--STORY 2-->
			<div class="story">
				<div class="rank">
					<p style="float:left;">2</p> 
					<ul style="float:right;">
						<li><img src="images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
						<li>182</li>
						<li><img src="images/downvote.PNG" alt="downvote"/></li>

					</ul>
				</div>
				<div class="info">
					<ul>
						<li class="title"><a href="#">Hold on to your butts</a>(imgur.com)
							<ul>
								<li class="post-info">(<span class="orange">190</span>|<span class="blue">8</span>) submitted 4 hours ago by <a href="#">user</a>
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
			</div>
			<!--STORY 3-->
			<div class="story">
				<div class="rank">
					<p style="float:left;">3</p> 
					<ul style="float:right;">
						<li><img src="images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
						<li>20</li>
						<li><img src="images/downvote.PNG" alt="downvote"/></li>

					</ul>
				</div>
				<div class="info">
					<ul>
						<li class="title"><a href="#">Now that we know who you are, I know who I am. </a>(self.assignment1)
							<ul>
								<li class="post-info">(<span class="orange">20</span>|<span class="blue">0</span>) submitted 4 hours ago by <a href="#">user</a>
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
			</div>
			<!--STORY 4-->
			<div class="story">
				<div class="rank">
					<p style="float:left;">4</p> 
					<ul style="float:right;">
						<li><img src="images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
						<li>290</li>
						<li><img src="images/downvote.PNG" alt="downvote"/></li>

					</ul>
				</div>
				<div class="info">
					<ul>
						<li class="title"><a href="#">Are you ready for the truth?</a>(self.assignment1)
							<ul>
								<li class="post-info">(<span class="orange">300</span>|<span class="blue">10</span>) submitted 4 hours ago by <a href="#">user</a>
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
			</div>
			<!--STORY 5-->
			<div class="story">
				<div class="rank">
					<p style="float:left;">5</p> 
					<ul style="float:right;">
						<li><img src="images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
						<li>182</li>
						<li><img src="images/downvote.PNG" alt="downvote"/></li>

					</ul>
				</div>
				<div class="info">
					<ul>
						<li class="title"><a href="#">Hold on to your butts</a>(imgur.com)
							<ul>
								<li class="post-info">(<span class="orange">190</span>|<span class="blue">8</span>) submitted 4 hours ago by <a href="#">user</a>
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
			</div>
			<!--STORY 6-->
			<div class="story">
				<div class="rank">
					<p style="float:left;">6</p> 
					<ul style="float:right;">
						<li><img src="images/upvote.PNG" alt="upvote" style="margin-left:1px;"/></li>
						<li>20</li>
						<li><img src="images/downvote.PNG" alt="downvote"/></li>

					</ul>
				</div>
				<div class="info">
					<ul>
						<li class="title"><a href="#">Now that we know who you are, I know who I am. </a>(self.assignment1)
							<ul>
								<li class="post-info">(<span class="orange">20</span>|<span class="blue">0</span>) submitted 4 hours ago by <a href="#">user</a>
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
			</div>
		</div>
		<!--SIDEBAR-->
		<div class="sidebar">
			<input type="text" placeholder="search reddit">
			<div class="big-button">
				<a href="#">Submit a new link</a>
			</div>
			<div class="big-button">
				<a href="#">Submit a new text post</a>
			</div>
			<h2>Assignment 1</h2>
			<p><input type="checkbox"> Use subreddit style</p> 
			<a href="#" class="button">subscribe</a>
			<a href="#" class="button">+shortcut</a>
			<a href="#" class="button">+dashboard</a>
			<p>222 readers<br/>~10 users hear now</p>
			<p><input type="checkbox">Show my flair on this subreddit</p> 
			<p style="padding:8px;">Now that there is the Tec-9, a crappy spray gun from South Miami. This gun is advertised as the most popular gun in American crime. Do you believe that shit? It actually says that in the little book that comes with it: the most popular gun in American crime. Like they're actually proud of that shit. </p>
			<h3>Recently Viewed Links</h3>
			<div class="previous-links">
				<ul>
					<li><a href="#">[Assignment 1] Here is my website for assignment 1</a>
						<ul>
							<li>6 points | <a href="#">2 comments</a></li>
						</ul>
					</li>
					<li><a href="#">Introduce yourself!</a>
						<ul>
							<li>14 points | <a href="#">83 comments</a></li>
						</ul>
					</li>
					<li><a href="#">What's a fun website you go on when your bored?</a>
						<ul>
							<li>1279 points | <a href="#">1892 comments</a></li>
						</ul>
					</li>
				</ul>
			</div>
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