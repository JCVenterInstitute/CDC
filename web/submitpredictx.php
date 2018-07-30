<?php include 'includes/header.php';?>
					<div class="container">
<?php
$file1name=$file2name="";
$file1name = basename($_FILES['file1']['name']); #echo "** $file1name <br>";
$file2name = basename($_FILES['file2']['name']); #echo "** $file2name <br>";
$file1=str_replace("otu.","",$file1name); #echo "** $file1 <br>**";
$file2=str_replace("otu.","",$file2name); #echo "** $file2 <br>**";

if($file1 != "shared" || $file2 != "taxonomy"){ echo "<h2 align='center'><b><i>Please upload the correct shared and taxonomy files</i></b></h2>"; die;}
		#$ran= "80824";
		$ran= rand(100, 100000);
                $dir = "/www/tmp/$ran";
                #$dir = "/export/apache/htdocs/fmd/temp/$ran";
                exec("mkdir $dir");
                exec("chmod 777 $dir");
                if (is_uploaded_file($_FILES['file1']['tmp_name']))
                $filedata1 = file_get_contents($_FILES['file1']['tmp_name']);
                $array1=array_map('trim',explode("\n",$filedata1));
                $FP1=fopen("$dir/test.shared","w+");
                foreach($array1 as $data1) {
                fwrite($FP1,$data1."\n");
                }
                fclose($FP1);

                if (is_uploaded_file($_FILES['file2']['tmp_name']))
                $filedata2 = file_get_contents($_FILES['file2']['tmp_name']);
                $array2=array_map('trim',explode("\n",$filedata2));
                $FP2=fopen("$dir/test.taxonomy","w+");
                foreach($array2 as $data2) {
                fwrite($FP2,$data2."\n");
                }
                fclose($FP2);
		system("dos2unix $dir/test.shared $dir/test.taxonomy");
		system("head -2 $dir/test.shared > $dir/testx.shared");
                system("/usr/bin/Rscript /www/projects/fmd/scripts/generate-otu.R $dir/testx.shared $dir/test.taxonomy $dir/norm-data.txt");
                #system("perl /www/projects/fmd/scripts/grep.pl $dir/norm-data.txt $dir/test.taxonomy $dir/test.txt");
                system("python /www/projects/fmd/scripts/get_bc_vals.py $dir/norm-data.txt /www/projects/fmd/data/complete.txt > $dir/score.txt");
                $files = file("$dir/score.txt");
                $data = explode(",", $files[0]);
		$distance=trim($data[5]);
#		$distance=trim($distance);
		system ("cp /www/projects/fmd/scripts/map.html $dir/");
		system ("perl -pi -e 's/NNN/$data[2]/g' $dir/map.html");
		system ("perl -pi -e 's/DDD/$distance/g' $dir/map.html");
		system ("perl -pi -e 's/CCC/$data[3],$data[4]/g' $dir/map.html");
	#	echo "<br>$data[2]****$data[3]$data[4]***$data[5]****$distance***<br>";
		echo "<meta  http-equiv='refresh' content='1;url=/map.php?ran=$ran' />";

?> 
		</div>

