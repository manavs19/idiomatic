<?php

include "connect.php";

/**
 * Return the coefficient of two items based on Jaccard index
 * http://en.wikipedia.org/wiki/Jaccard_index
 *
 * Example:
 * 
 * $tags1 = "code, php, jaccard, test, items";
 * $tags2 = "test, code";
 * echo getSimilarityCoefficient( $tags1, $tags2 ); // 0.28
 *
 * $str1 = "similarity coefficient of two items";
 * $str2 = "two items are cool";
 * echo getSimilarityCoefficient( $str1, $str2, " " ); // 0.44
 *
 * @param   string $item1 
 * @param   string $item2 
 * @param   string $separator 
 * @return  float
 */
function getSimilarityCoefficient( $item1, $item2, $separator = " " ) {
    
    $item1 = explode( $separator, $item1 );
    $item2 = explode( $separator, $item2 );
    $arr_intersection = array_intersect( $item1, $item2 );
    $arr_union = array_unique(array_merge( $item1, $item2 ));
    $coefficient = count( $arr_intersection ) / count( $arr_union );
    
    return $coefficient;
}

function echoTweet($tweet)
{
	$id_str = $tweet['id_str'];
	$text = $tweet['text'];
	$created_at = $tweet['created_at'];
	$entities = $tweet['entities'];

	$name = $tweet['name'];
	$screen_name = $tweet['screen_name'];
	$profile_image_url = $tweet['profile_image_url'];
	$verified = $tweet['verified'];

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

    $encoded_entities = json_decode($entities, true);
    $user_mentions = $encoded_entities['user_mentions'];
    $hashtags = $encoded_entities['hashtags'];
    $urls = $encoded_entities['urls'];
    

	$text = "<p class='tweet'>".$text;
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
	    foreach ($media as $key => $item) {
		if($item['type'] != 'photo')continue;
	    	$search = $item['url'];
		$media_url = $item['media_url'];
		$replace = "<img src='$media_url:small' alt='Image not available!'></img>";//<a href='$search' rel='external'>$search</a>";
	    	$text = str_replace($search, $replace, $text);
	    }
	}
    $text = $text."</p>";

    echo $text;

    $tweet_timestamp = strtotime($created_at);
    $current_timestamp = time();
    $hourdiff = intval(($current_timestamp - $tweet_timestamp)/3600);

	$str = "<div class='stats'>";
//        		<a href='http://twitter.com/$screen_name/statuses/$id_str'>
//         		         Tweeted about $hourdiff hours ago
//            		</a>
	
    echo $str;

    $str = "<div class='buttons'>
		<script type='text/javascript' async src='//platform.twitter.com/widgets.js'></script>
		<a href='https://twitter.com/intent/tweet?in_reply_to=$id_str'>Reply</a>
		<a href='https://twitter.com/intent/retweet?tweet_id=$id_str'>Retweet</a>
		<a href='https://twitter.com/intent/favorite?tweet_id=$id_str'>Favorite</a>
	   </div></div>";
    echo $str;

	echo "</li>";
}

$idiom = $_GET["idiom"];
//$idiom="hash";
if($idiom!=''){
    echo "<h2 style=\"text-align:center\">Showing tweets for #".$idiom."</h2>";    
}

$query = "SELECT * FROM idiomatic_tweets WHERE `idiom` = '$idiom' ORDER BY retweet_count DESC;";// LIMIT 50;";
if($query_run = mysql_query($query))
{
	if(mysql_num_rows($query_run)!=0)
	{
        $unique_tweets = array();
        echo "<ul class='tweet-list'>";
	while (($tweet = mysql_fetch_assoc($query_run)) &&  ( count($unique_tweets) < 50 )  )
	{
	    $text = $tweet["text"];
            $text = str_replace("RT", "", $text);
            $text = str_replace("via", "", $text);
            $flag = 0;
            foreach ($unique_tweets as $value) {
                if(getSimilarityCoefficient($text, $value, " ") > 0.8)
                {
                    $flag = 1;
                    break;
                }
            }
            if($flag == 0)
            {
                array_push($unique_tweets, $text);
                echoTweet($tweet);    
            }
			
	}
        echo "</ul>";
	}
	else
	{
		echo "No tweets found!";
	}
}
else
{
	echo mysql_error();
}


?>
