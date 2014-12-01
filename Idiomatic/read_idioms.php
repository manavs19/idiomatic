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
$table_name = "idioms_".$duration;
if($duration == ''){
	$table_name = "idioms";
}
$query = "SELECT * FROM $table_name ORDER BY RAND() LIMIT 10;";

if($query_run = mysql_query($query))
{
	if(mysql_num_rows($query_run)!=0)
	{
		echo "<center><h2 >Idioms</h2></center>";
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