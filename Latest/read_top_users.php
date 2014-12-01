<?php

include "connect.php";

function echoUser($user)
{
    $id_str = $user['id_str'];
	$name = $user['name'];
	$screen_name = $user['screen_name'];
    $description = $user['description'];
	//$entities = $user['entities'];
    //$followers_count = $user['followers_count']
    //$listed_count = $user['listed_count']
    $profile_image_url = $user['profile_image_url'];
    $verified = $user['verified'];

	echo "<li id=\"$id_str\">";
	$str = "<div class='avatar' align = 'left'>
        		<a href='http://twitter.com/$screen_name'>
            		<img src='$profile_image_url'/>
        		</a>
   			</div>";

   	echo $str;
   	
   	$str = "<div class='name'>
        		<a href='http://twitter.com/$screen_name'>
            		<strong>$name </strong>
        		</a>
        		: <b>$screen_name</b>";
    if($verified == "true")
        $str = $str."<img src='images/verified.png'>";
     $str = $str."</div>";

    echo $str;

    $str = "<div class='bio'>
                <p>$description</p>
            </div>";
    echo $str;

    /*$encoded_entities = json_decode($entities, true);
    $user_mentions = $encoded_entities['user_mentions'];
    $hashtags = $encoded_entities['hashtags'];
    $urls = $encoded_entities['urls'];
    

	$text = "<p class='tweet'>".$description;
    foreach ($user_mentions as $key => $user) {
    	$sn = $user['screen_name'];
    	$search = "@".$sn;
    	$replace = "<a href='https://twitter.com/$sn'>$search</a>";
    	$text = str_replace($search, $replace, $text);
    }

    foreach ($hashtags as $key => $hashtag) {
    	$tt = $hashtag['text'];
    	$search = "#".$tt;
    	$replace = "<a href='https://twitter.com/search?q=%23$tt'>$search</a>";
    	$text = str_replace($search, $replace, $text);
    }

    foreach ($urls as $key => $url) {
    	$search = $url['url'];
    	$replace = "<a href='$search' rel='external'>$search</a>";
    	$text = str_replace($search, $replace, $text);
    }

	if (array_key_exists('media', $encoded_entities)) {
    	$media = $encoded_entities['media'];	
	    foreach ($media as $key => $url) {
	    	$search = $url['url'];
	    	$replace = "<a href='$search' rel='external'>$search</a>";
	    	$text = str_replace($search, $replace, $text);
	    }
	}
    $text = $text."</p>";

    echo $text;
    */

	echo "<a href='https://twitter.com/$screen_name' 
            class='twitter-follow-button' 
            data-show-count='true' 
            data-size='medium'>
            Follow @$screen_name
          </a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>
        ";

	echo "</li>";
}

echo "<h3 style='text-align: center;'>Most Popular Users</h3><br/>";

$query = "SELECT * FROM idiomatic_top_users ORDER BY listed_count LIMIT 20";

if($query_run = mysql_query($query))
{
	if(mysql_num_rows($query_run)!=0)
	{
        echo "<ul>";
		while ($user = mysql_fetch_assoc($query_run))
		{
			echoUser($user);
		}
        echo "</ul>";
	}	
}
else
{
	echo mysql_error();
}


?>
