<?php 
session_start();
require('searchFunction.php'); 
require('removeStopWords.php');
require('sentiment.php');
require('display.php');
require_once('config.php');
?>

<div id="search_bar">
			<form action="" method="POST">
				<input type="text" id="search_text" placeholder="Search Twitter..." name="keyword">
				<input type="submit" id = "submit" value="Search">
			</form>
		</div>
		<div id="results">
<?php

$query = mysql_query("SELECT id FROM {$_SESSION['twitter_id']}");

if($query)
{
	displayTweets();
}

if(isset($_POST['keyword']))
{
?>
<script>
	document.getElementById("results").innerHTML = "";
</script>
<?
	search();
	$text = mysql_query("SELECT id, text FROM {$_SESSION['twitter_id']}");
	$tweets = array();

	while($row = mysql_fetch_assoc($text))
	{
		$tweets[] = $row;
	}
	
	$tweets = removeStopWords($tweets);
	
	findSentiment($tweets);

	displayTweets();

}
?>
		</div>
	</body>
</html>