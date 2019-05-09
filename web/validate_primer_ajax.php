<?php
// this script creats the job
$dir = $_POST['dir1'];
$ran = $_POST['ran1'];
$user_file= $_POST['user_file'];
$output_path= $_POST['output_path'];
$green_light_to_excute= $_POST['green_light_to_excute'];




$path =  `pwd`;
$path = "/usr".trim($path);
$path = str_replace("ifs2_projdata","projdata",$path);
$path = trim($path);



$path1 = str_replace("apache/htdocs","pcr_validator",$path);
$path2 = str_replace("htdocs","",$path);
$path1 = trim($path1);
$path2 = trim($path2);

	// output files 
    if(trim($_POST['reference_genebank'])!='empty'){
	     //execute  when user provide genbank id 
	      $cmdresponse=shell_exec("python $path1/pcr_validator.py --genbank_id ".trim($_POST['reference_genebank'])." --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR $path1/simulate_PCR.pl --output $output_path/ --email cdctestsup@gmail.com");
	     
    }elseif(trim($_POST['reference_fastafile'])!='empty'){
    	// execute when user provide reference file
     	 $cmdresponse=shell_exec("python $path1/pcr_validator.py --database $dir/$user_file --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR $path1/simulate_PCR.pl --output $output_path/ --email cdctestsup@gmail.com");
    }else{
	     	// default using AMR DB
	     $cmdresponse=shell_exec("python $path1/pcr_validator.py --database $path2/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_nucleotides.fasta --forward_primers $dir/frd_primer.fasta --reverse_primers $dir/rvs_primer.fasta --simulate_PCR $path1/simulate_PCR.pl --output $output_path/ --email cdctestsup@gmail.com");
	     
	}

	$dirx ="tmp/$ran";
	$myfilew = fopen("$dir/data", "w") or die("Unable to open file!");
	fwrite($myfilew, "Identity_ID\tamplicon_len\tHitName\tFP_ID\tFP_mismatches\tRP_ID\tRP_mismatches\tStart\tEnd\tSubjectFullLength\n");
	$myfile = file("$dir/out/Tabular_Output.txt") or die("Unable to open file!");
	array_shift($myfile);
	// Output one line until end-of-file
	foreach ($myfile as $file) {
		rtrim($file);
		//echo "$file **<br>";
	  //$data=array();
		$datax=$datay=$dataz=$alldata="";
		#$datax=explode('\t', $line);
		$datax = preg_split('/\s+/', $file);
		$datay = preg_split('/\|/', $datax[1]);
		array_shift($datay);
		$id = array_shift($datay);
		$hit = implode(" ",$datay);
		#print_r($datax); echo "**<br>";
		#print_r($datay); echo "**<br>";
		$dataz=$datax[1];
		$dataz = str_replace("|","_",$dataz);
		$link=$dataz."_hit.html";
		fwrite($myfilew, "$id\t$datax[0]\t<a href='$dirx/out/$dataz/$link' target=_blank>$hit</a>\t$datax[2]\t$datax[5]\t$datax[6]\t$datax[9]\t$datax[11]\t$datax[12]\t$datax[13]\n");
	}
	fclose($myfilew);
   
    shell_exec("perl csv2json.pl $dir/data > $dir/out/data.json");
 	
?>
