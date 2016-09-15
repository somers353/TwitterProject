<?php

session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) 
{
    header('Location: http://www.keithandnikkiswedding.com/TwitterV2/clearsessions.php');
}

/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* Get user details for session. */
$content = $connection->get('account/verify_credentials');
$_SESSION['name']=$content->name;
$_SESSION['twitter_id']=$content->screen_name;
$_SESSION['image']= ' https://twitter.com/'.$_SESSION['twitter_id'].'/profile_image?size=original';

$conn = mysql_connect(dbhost, dbusername, dbpass);

if(!$conn)
{
	echo 'Cannot connect to database';	
}
else
{
	$dbselect = mysql_select_db("soke96_tweets");
	if(!$dbselect)
	{
		echo mysql_errno($conn) . ": " . mysql_error($conn). "\n";
		//$table = mysql_query("CREATE TEMPORARY TABLE tweets");
		//var_dump($table);
	}
	$table = mysql_query(createdb);
	if(!$table)
	{
		//echo mysql_errno($conn) . ": " . mysql_error($conn). "\n";
	}
}


/* Include HTML to display on the page */
include('sidebar.html');
include('search.php');
