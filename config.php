<?php

define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('OAUTH_CALLBACK', 'http://www.keithandnikkiswedding.com/TwitterV2/callback.php');
define('dbhost', 'mysql1.host.ie');
define('dbusername', 'soke96_wpdb');
define('dbpass', '');
define('createdb', "CREATE TABLE IF NOT EXISTS {$_SESSION['twitter_id']} (`id` varchar(20), `created_at` datetime, `user` varchar(100), `user_loc` varchar(100), `user_img` varchar(100), `user_friend_count` int, `text` text, `retweet_count` int, `fav_count` int, `sentiment` varchar(10))");