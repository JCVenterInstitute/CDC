<?php
$hostname = "cdc-1.jcvi.org";
$username = "cdc_app";
$password = "Guest649745f";
$dbname = "CDC";

$dbhandle = @mysql_connect($hostname, $username, $password)
  or die("Unable to connect to MySQL");

//echo "Connected to MySQL<br>";

?>
<?php
//select a database to work with
$selected = mysql_select_db($dbname,$dbhandle)
  or die("Could not select examples");
?>

<?php
/*$result = mysql_query("SELECT * FROM country");
while ($row = mysql_fetch_array($result)) {
   echo " id:".$row{'country_id'}."
   ".$row{'country_name'}."<br>";
}
die();*/
?>