<?php
include "connect.php";

$idiom=$_POST["idiom"];
$feedback = $_POST["feedback"];

$query = "";
if($feedback==1){
	$query = "UPDATE idiomatic_idioms SET `yes`=`yes`+1 where `idiom`='$idiom';";	
}
else if($feedback==-1){
	$query = "UPDATE idiomatic_idioms SET `no`=`no`+1 where `idiom`='$idiom';";	
}
else{
	$query = "UPDATE idiomatic_idioms SET `cant`=`cant`+1 where `idiom`='$idiom';";	
}

//echo "$query";
if($query_run = mysql_query($query))
{
	echo "<h2>Thank you for your feedback!! You are welcome to try more queries</h2>";
	echo "<a href=\"index.php\"><h3>Back to Mainpage</h3></a>";
}
else
{
	echo mysql_error();
	echo "<br/>Could not submit feedback!";
}

?>
