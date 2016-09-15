<?php
	function findSentiment($tweets)
	{
		$positive = file("positive.txt", FILE_IGNORE_NEW_LINES);
		$negative = file("negative.txt", FILE_IGNORE_NEW_LINES);
		
		$totalNeg = 0;
		$totalPos = 0;
		$totalNeutral = 0;
		
		$posVal = "pos";
		$negVal = "neg";
		$neutralVal = "neutral";
		
		foreach($tweets as $tweet)
		{
			$pos = 0;
			$neg = 0;
			
			foreach($positive as $posWord)
			{
				if(preg_match($posWord, $tweet['text']))
				{
					$pos++;
				}
			}
			
			foreach($negative as $negWord)
			{
				if(preg_match($negWord, $tweet['text']))
				{
					$neg++;
				}
			}

			$sentiment = $pos - $neg;
			$id = $tweet['id'];
			
			if($sentiment < 0)
			{
				$query = mysql_query("UPDATE {$_SESSION['twitter_id']} SET sentiment = '$negVal' WHERE id = '$id'");
				$totalNeg++;
			}
			elseif($sentiment > 0)
			{
				$query = mysql_query("UPDATE {$_SESSION['twitter_id']} SET sentiment = '$posVal' WHERE id = '$id'");
				$totalPos++;
			}
			else
			{
				$query = mysql_query("UPDATE {$_SESSION['twitter_id']} SET sentiment = '$neutralVal' WHERE id = '$id'");
				$totalNeutral++;
			}
			
			unset($id);
		}
		
		echo '<br>'.$totalPos.' positive, '.$totalNeg.' negative and '.$totalNeutral.' neutral.<br></div>';
	}
?>