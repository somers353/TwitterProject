<?php
	function search()
	{
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		mysql_query("DELETE FROM {$_SESSION['twitter_id']}");

		$q = $_POST['keyword'];
		$max_id = 0;
		$oldest_tweet = '';
		$tweets_found = 0;
		$loop = 0;

		while($loop < 15)
		{
			if($max_id == 0)
			{
				$tweets = $connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($q).
										'&lang=en&result_type=recent&count=100&include_entities=true');
				++$loop;
			}
			else
			{
				$tweets = $connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($q).
										'&lang=en&result_type=recent&count=100&include_entities=true&max_id='.$max_id.'');
				++$loop;
			}
			
			if(sizeof($tweets) == 0)
			{
				echo 'result is empty';
				break;
			}
			
			if(isset($tweets->statuses) && is_array($tweets->statuses)) 
			{
				if(count($tweets->statuses)) 
				{
					foreach($tweets->statuses as $tweet) 
					{	
						$id = $tweet->id;
						$created_at = $tweet->created_at;
						$created_at = strtotime($created_at);
						$mysqldate = date('Y-m-d H:i:s',$created_at);
						$user = $tweet->user->name;
						$screen_name = $tweet->user->screen_name;
						$user_loc = $tweet->user->location;
						$user_img = $tweet->user->profile_image_url;
						$user_friend_count = $tweet->user->friends_count;
						$text = $tweet->text;
						$text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);
						$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
						$text = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $text); // Usernames
						$text = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $text); // Hash Tags
						
						$retweet_count = $tweet->retweet_count;
						$fav_count = $tweet->favourite_count;
						
						$query = mysql_query("INSERT INTO {$_SESSION['twitter_id']} VALUES ('$id', '$mysqldate', '$screen_name', '$user_loc', '$user_img', '$user_friend_count', '$text', '$retweet_count', '$fav_count', NULL)");
						
						if(!$query)
						{
							continue;
						}
						
						++$tweets_found;
						if($max_id == 0)
						{
							$max_id = $id;
						}
						elseif($id > max_id)
						{
							$max_id = $id;
						}
						$oldest_tweet = $tweet->created_at;
					}
					
				}
				
			}		
		}
		
		echo '<div id="resultsInfo"><br>'.$tweets_found.' tweets found for '.$_POST['keyword'];
	}
?>