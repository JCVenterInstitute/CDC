<?php
include 'includes/header.php'; 
include 'includes/config.inc.php';

$database = new Database();
$db = $database->dbConnection();
$conn = $db;


#$stmt = $conn->prepare("SELECT Count( DISTINCT cl.id)as dc FROM Identity cl ");


$sql = "SELECT cl.Drug_Family, cl.Drug_Class,cl.Mechanism_of_Action FROM Classification cl ";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
#echo "$sql";
echo "<br>";
print_r($ids);



$sql=$query="";
#$sql = "Select iss.Is_Active FROM CDC.Classification ise,CDC.Identity i ";

?>