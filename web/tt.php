<?php
/*
	shell_exec("cut -f1 $dir/out/Tabular_Output.txt > $dir/x1");
	shell_exec("cut -f2 $dir/out/Tabular_Output.txt | cut -d '|' -f3- | perl -pi -e 's/\|\|\|//g' | perl -pi -e 's/\|\|//g' | perl -pi -e 's/\|/ | /g' > $dir/x2");
	shell_exec("cut -f2 $dir/out/Tabular_Output.txt | cut -d '|' -f2 | perl -pi -e 's/HitName/Identity_ID/g' > $dir/x21");
	shell_exec("cut -f3,6,7,10,12,13,14 $dir/out/Tabular_Output.txt > $dir/x3");
    shell_exec("paste $dir/x21 $dir/x1 $dir/x2 $dir/x3 > $dir/x");
*/
$dir ="/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/primer_2018_09_26_14_55_425153";
$dirx ="tmp/primer_2018_09_26_14_55_425153";

#$myfile = fopen("/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/primer_2018_09_26_14_06_265915/out/Tabular_Output.txt", "r") or die("Unable to open file!");
$myfile = file("$dir/out/Tabular_Output.txt") or die("Unable to open file!");
array_shift($myfile);
// Output one line until end-of-file
foreach ($myfile as $file) {
    rtrim($file);
    //echo "$file **<br>";
  //$data=array();
    #$datax=explode('\t', $line);
    $datax = preg_split('/\s+/', $file);
    $datay = preg_split('/\|/', $datax[1]);
    array_shift($datay);
    $id = array_shift($datay);
    $hit = implode(" ",$datay);
    #print_r($datax); echo "**<br>";
    #print_r($datay); echo "**<br>";
    $link=$datax[1]."_hit.html";
    print "$id\t$datax[0]\t<a href='$dirx/out/$datax[1]/$link target=_blank'>$hit</a>\t$datax[2]\t$datax[5]\t$datax[6]\t$datax[9]\t$datax[11]\t$datax[12]\t$datax[13]<br>";
}

?>