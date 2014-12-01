<?php
include "connect.php";

function echoIdiom($idiom)
{
	echo '<li>';

	echo "<a href=\"idioms.php?idiom=$idiom\" >#$idiom</a>";
	//echo "<a href='https://twitter.com/search?q=%23$idiom'>#$idiom</a>";
	
	echo "</li>";
}

$duration = $_POST['duration'];
$table_name = "idiomatic_idioms_".$duration;
$query = "";
if($duration == ''){
	$query = "SELECT * FROM idiomatic_idioms ORDER BY RAND()";
}
else if($duration == 'week'){
	$query = "SELECT * FROM idiomatic_idioms WHERE timestamp <= NOW() AND timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
		  UNION
		  SELECT * FROM idiomatic_idioms LIMIT 10";

}
else{
	$query = "SELECT * FROM idiomatic_idioms_month LIMIT 10";
}

if($query_run = mysql_query($query))
{
	if(mysql_num_rows($query_run)!=0)
	{
		if($duration=="week")
		echo "<center><h2 >Trending Idioms of last week</h2></center>";
		else if($duration=="month")
		echo "<center><h2>Trending Idioms of last month</h2></center>";
		else			
		echo "<center><h2>All Idioms</h2></center>";

		echo "<ul>";
		while ($idiom = mysql_fetch_assoc($query_run))
		{
			$name = $idiom['idiom'];
			echoIdiom($name);
		}
		echo "</ul>";
	}
	else
	{
		echo "No idioms found!";
	}
}
else
{
	echo mysql_error();
}

?>
