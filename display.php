<?php
	function displayTweets()
	{
		$text = mysql_query("SELECT user, text, sentiment FROM {$_SESSION['twitter_id']}");
		$tweets = array();

		while($row = mysql_fetch_assoc($text))
		{
			$tweets[] = $row;
		}	
		
		foreach($tweets as $tweet)
		{
			$sentiment = $tweet['sentiment'];
			
			echo '<div id="'.$sentiment.'">';
				echo 'User <span style="color:#6CD9D9;"><a href="http://www.twitter.com/'.$tweet['user'].'">@'.$tweet['user'].'</a></span><br>';
				echo $tweet['text'];
			echo '</div>';
		}
	}
?>