<?php

// not done
// data inconsistency with the solr data 

$dir = "tmp/primer_2018_09_14_14_36_383621/out/x";


$local_x_file = fopen($dir, "r") or die("Unable to open file!");
// Output one character until end-of-file
while(!feof($local_x_file)) {
  
  $line = fgets($local_x_file);
  $source = explode('|', $line);
  $source=explode(':', $source[1]);
  $source=explode('.', $source[0]);
  echo "\"*$source[0]";
  // search for the ID using source ID 
  $post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "Source_ID:$source[0]"  }'`;
		$js= json_decode($post_fetch);

}
fclose($local_x_file);

?>