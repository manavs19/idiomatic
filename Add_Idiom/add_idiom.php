<?php

include "connect.php";

$idi = $_POST["idi"];
$query = "INSERT INTO stored_idioms(idiom) VALUES('$idi')";
if($query_run = mysql_query($query))
{
	echo "Thanks for submitting";
	/*$python_path="C:\\Users\\Manav\\AppData\\Local\\Enthought\\Canopy\\User\\python.exe";
	$python_script="getTweetsForIdiom.py";
	$tmp = exec("$python_path $python_script $idi",$output);
	//var_dump($output);
	*/
	echo "<br/><a href = 'index.html'><b>Add Another Idiom!</b></a>";
}
else
{
	echo mysql_error();
	echo "Could not submit idiom!";
}


?>