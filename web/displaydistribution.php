<?php 
require_once("includes/header.php");
require_once("includes/config.inc.php");

#echo "<pre>"; print_r($_POST); echo "</pre>"; 
$nn=$site="";
$option = !empty($_POST['optionsRadios']) ? $_POST['optionsRadios'] : ''; #echo "**$option OO**<br>";
$body = !empty($_POST['body']) ? $_POST['body'] : ''; ##echo "**$body BB**<br>";
$city = !empty($_POST['city']) ? $_POST['city'] : ''; ##echo "**$city CC**<br>";
$state = !empty($_POST['state']) ? $_POST['state'] : ''; ##echo "**$state SS**<br>";
$country = !empty($_POST['country']) ? $_POST['country'] : '';  ##echo "**$country CO**<br>";
#if ($city == "--Select City--" && $state == "--Select State--" && $country == "--Select Country--") { echo "<h2 align='center'>    <i><b>Please select a country, state or a city 1</b></i></h2>"; die; }

$bodyx = !empty($_POST['bodyx']) ? $_POST['bodyx'] : ''; ##echo "**$bodyx BB **<br>";
$cityx = !empty($_POST['cityx']) ? $_POST['cityx'] : ''; ##echo "**$cityx CC **<br>";
$statex = !empty($_POST['statex']) ? $_POST['statex'] : ''; ##echo "**$statex SS **<br>";
$countryx = !empty($_POST['countryx']) ? $_POST['countryx'] : '';  ##echo "**$countryx CO **<br>";
#if ($cityx == "--Select City--" && $statex == "--Select State--" && $countryx == "--Select Country--") { echo "<h2 align='center'>    <i><b>Please select a country, state or a city 2</b></i></h2>"; die; }

#if ($city != "" || $state != "" || $country != "" || $body != "" && $cityx == "--Select City--" && $statex == "--Select State--" && $countryx == "--Select Country--" && $bodyx == "--Select Body--") { echo "Module 1"; 
if($option == "option1"){
$sql=$query="";
	if (empty($city) && empty($state) && $country != "") { $select = $country; $term = "country"; }
	if (empty($city) && $state != "" && $country != "") { $select = $state; $term = "state";  }
	if ($city != "" && $state != "" && $country != "") { $select = $city; $term = "city";  }
$selname=$term."_name"; $selid=$term."_id";
$sql=$query="";
$sql = "SELECT body_name FROM body WHERE body_id = '$body'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$site=$ids[0];

if ($city != "" && $state != "" && $country != "") {
$sql=$query="";
$sql = "SELECT city_name FROM city WHERE city_id = '$city'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$nn=$ids[0].".html";
#echo "$nn";
} 

if (empty($city) && $state != "" && $country != "") {
$sql=$query="";
$sql = "SELECT state_name FROM state WHERE state_id = '$state'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$nn=$ids[0].".html";
#echo "$nn";
}

if (empty($city) && empty($state) && $country != "") {
$sql=$query="";
$sql = "SELECT country_name FROM country WHERE country_id = '$country'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$nn=$ids[0].".html";
}
#echo "$site/$nn";
if(empty($nn)){ echo "<h2><i><b>Please select a country, state or a city</b></i></h2>";} 
include("data/$site/$nn");
}

if($option == "option2"){
	if (empty($cityx) && empty($statex) && $countryx != "") { $select = $countryx; $term = "country";  }
	if (empty($cityx) && $statex != "" && $countryx != "") { $select = $statex; $term = "state";  }
	if ($cityx != "" && $statex != "" && $countryx != "") { $select = $cityx; $term = "city";  }
$termx=$term."x";
$selname=$term."_name"; $selid=$term."_id";
$sql=$query="";
$sql = "SELECT body_name FROM bodyx WHERE body_id = '$bodyx'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$site=$ids[0];

if ($cityx != "" && $statex != "" && $countryx != "") {
$sql=$query="";
$sql = "SELECT city_name FROM cityx WHERE city_id = '$cityx'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$nn=$ids[0].".html";
#echo "$nn";
}
if (empty($cityx) && $statex != "" && $countryx != "") {
$sql=$query="";
$sql = "SELECT state_name FROM statex WHERE state_id = '$statex'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$nn=$ids[0].".html";
#echo "$nn";
}

if (empty($cityx) && empty($statex) && $countryx != "") {
$sql=$query="";
$sql = "SELECT country_name FROM countryx WHERE country_id = '$countryx'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$nn=$ids[0].".html";
}
#echo "$site/$nn";
if(empty($nn)){ echo "<h2><i><b>Please select a country, state or a city</b></i></h2>";}
include("data/$site/$nn");
}
require_once("includes/footerx.php");
?>
