<?php 
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$conn = mysql_connect(dbhost, dbusername, dbpass);
if(!$conn)
{
	echo 'Cannot connect to database';	
}
mysql_select_db("soke96_tweets");

$map_query = mysql_query("SELECT id, user, text, user_loc, sentiment FROM {$_SESSION['twitter_id']}");
$map_results = array();

while($row = mysql_fetch_assoc($map_query))
{
	$map_results[] = $row;
}

include('sidebar.html');
?>

<script>
	var locations_array = <?php echo json_encode($map_results); ?>;
	console.log(locations_array);
	var map, marker, geocoder;
	
	function initialize() 
	{
		var mapOptions = {
							zoom: 3,
							center: new google.maps.LatLng(34.933982, -13.535156)
						};
		map = new google.maps.Map(document.getElementById('map_canvas'),
		mapOptions);
		
		geocoder = new google.maps.Geocoder();
		codeAddress();
	}
	
	function codeAddress()
	{
		var counter = 0;
		
		(function addressLoop()
		{
			if(counter++ >= locations_array.length) return;
			
			setTimeout(function()
			{
				var address = locations_array[counter].user_loc;
				
				geocoder.geocode({ 'address': address}, function(results, status)
				{
					if(status == google.maps.GeocoderStatus.OK)
					{
						var icon;
						
						switch(locations_array[counter].sentiment)
						{
							case "pos": icon = "http://maps.google.com/mapfiles/ms/icons/green-dot.png"; break;
							case "neg": icon = "http://maps.google.com/mapfiles/ms/icons/red-dot.png"; break;
							case "neutral": icon = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"; break;
						}
							
						marker = new google.maps.Marker
						({
							map: map,
							icon: icon,
							position: results[0].geometry.location
						});
						
						marker.content = "<div class ='map_tweet'><p><a href = 'http://www.twitter.com/'"+locations_array[counter].user+">@"+locations_array[counter].user+"</a><br>"+locations_array[counter].text+"</p></div>";
						
						var infowindow = new google.maps.InfoWindow({maxWidth: 400});
						
						google.maps.event.addListener(marker, 'click', function()
						{
							infowindow.setContent(this.content);
							infowindow.open(this.getMap(), this);
							
						});
					}
					else if(status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT)
					{
						counter--;
					}

				});
				addressLoop();
			}, 100);
		})();
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div id="map_canvas">

</div>
</body>
</html>