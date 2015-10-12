<?php include "twitteroauth-master/twitteroauth/twitteroauth.php";?>
<?php include "config.php";?>
<?php
session_start();
$twitter= new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $accesstoken, $accesstokensecret);
$user = $twitter->get('https://api.twitter.com/1.1/account/verify_credentials.json');
echo $_SESSION['name'];
$conn= mysql_connect($host, $username, $pass);

if(!$conn)
{
	echo 'Cannot connect to database';	
}
else
{
	mysql_query("CREATE TEMPORARY TABLE Tweets");
}
?>

<html>
	<head>
		<title>Twitter Search</title>
	</head>
	<body>
		<form action="" method="POST">
			<label>Search<input type="text" name="keyword"></label>
			<input type="submit" value="Submit">
		</form>
		
<?php
if(isset($_POST['keyword']))
{
	$tweets=$twitter->get('https://api.twitter.com/1.1/search/tweets.json?q='.$_POST['keyword'].'&lang=en&result_type=recent&count=200');
	if(isset($tweets->statuses) && is_array($tweets->statuses)) 
	{
		if(count($tweets->statuses)) 
		{
			foreach($tweets->statuses as $tweet) 
			{
				echo $tweet->text.'<br>';
			}
		}
		else 
		{
			echo 'The result is empty';
		}
	}
	
	$response = file_get_contents($tweets);
	$jsonobj = json_decode($response);
	
	if($jsonobj != null)
	{
		
	}
}
?>
		
	</body>
</html>