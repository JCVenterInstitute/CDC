<?php
/*	author: Ye Wang
	This script is to fetch data from solr
	and pass it to browse.php
*/
$search ="http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=*:*&rows=2000&wt=json";
$jsonString = file_get_contents($search);
preg_match('/("docs"[\s\S]*)/', $jsonString, $m);
$d_string="{\n".$m[0];
$d_string= str_replace("docs","data",$d_string);
$d_string[strlen($d_string)-1]="";
$d_string[strlen($d_string)-2]="";
$d_string = trim($d_string); 
//not a thing after echo not even comments.!!!!!
echo $d_string;

?>
