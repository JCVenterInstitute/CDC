<?php include 'includes/header.php';?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<style type="text/css" class="init">
	
td.details-control {
	background: url('images/details_open.png') no-repeat center center;
	cursor: pointer;
}
tr.shown td.details-control {
	background: url('images/details_close.png') no-repeat center center;
}
.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
}
</style>


<div class="container">
<?php
$file1name=$seq=$email=$method="";
$seq = !empty($_POST['seq']) ? $_POST['seq'] : ''; //echo "**$seqx* $ran<br>";
$seq = str_replace("\r", "", $seq);
//process input. get ride of empty space and end line char start from second line till last. 
//add 
preg_match("/^>.*\n/",$seq,$match1);
preg_match("/(?ms)(?!\A)^\S.*/",$seq,$match2);


// check if the sequence context is empty
if(trim($match2[0])==''||!preg_match("/^>.*\n/",$seq)){
echo "<h2 align='center'><b><i>Please input a protein sequence in the text area</i></b></h2>";
echo "<h2 align='center'><b><i>Or check your input</i></b></h2>"; die;
}

$part2=preg_replace("/\r/", "", $match2[0]); 
$part2=trim(str_replace(" ","",$part2));
$part2=trim(str_replace(" ","",$part2));
$part2=trim(str_replace(" ","",$part2));
$seq=$match1[0].$part2;

// var_dump(trim($match2[0]));

// var_dump($seq);

// Declearing db and sequence
$rt_ck = check_seq_input($seq);
$seq =  $rt_ck[1]; 
$db = $rt_ck[2];
     
#Determine the sequece type in $seq and based on than assigned $db value
// $db = !empty($_POST['db']) ? $_POST['db'] : ''; #echo "**$db OO**<br>";
$method = !empty($_POST['method']) ? $_POST['method'] : ''; #echo "**$option OO**<br>";
$file1name = basename($_FILES['file1']['name']); #echo "** $file1name <br>";

#$email = !empty($_POST['email']) ? $_POST['email'] : ''; #echo "**$option OO**<br>";

# Check for input. ignore the firs line.
$arr1 = str_split($seq);
if($seq == ""){ echo "<h2 align='center'><b><i>Please input a protein sequence in the text area</i></b></h2>"; die;}


# Creating tmp directory
		#$ran= "61899";
	$ran= rand(100, 100000);
        $dir = "/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$ran";
        exec("mkdir $dir");
        exec("chmod 777 $dir"); 
         
#*********************Parsing of input sequenc*************
// die();
//used to be ^>\w+ 

 
	$FP1=fopen("$dir/input_user.fasta","w");
	fwrite($FP1,$seq);
	#print "RAN:$ran";
	fclose($FP1);

/*              if (is_uploaded_file($_FILES['file1']['tmp_name']))
                $filedata1 = file_get_contents($_FILES['file1']['tmp_name']);
                $array1=array_map('trim',explode("\n",$filedata1));
                $FP1=fopen("$dir/test.shared","w+");
                foreach($array1 as $data1) {
                fwrite($FP1,$data1."\n");
                }
                fclose($FP1);
				die;
                if (is_uploaded_file($_FILES['file2']['tmp_name']))
                $filedata2 = file_get_contents($_FILES['file2']['tmp_name']);
                $array2=array_map('trim',explode("\n",$filedata2));
                $FP2=fopen("$dir/test.taxonomy","w+");
                foreach($array2 as $data2) {
                fwrite($FP2,$data2."\n");
                }
                fclose($FP2);
		if($array2[0] != "OTU\tTaxonomy"){echo "Please add 'OTU\tTaxonomy' in the header of the Taxonomy file. The OTU and Taxonomy should be seprated by a Tab '\t'"; die; } 
		////////////////////writing seq job file///////////

	$filec = "/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/amrjob";
	$fa = fopen($filec, 'a') or die("can't open file");
	fwrite($fa, "$ran"." ".$email."\n"); #}
	fclose($fa);
*/
#	if($method == "RUN BLAST against the AMRdb custome database"){ 
		if($db == "pr") {
		shell_exec("/bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran p peptides_id.fasta");
		#print"/bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran";
#	shell_exec("/local/ifs2_projdata/8500/projects/CDC/server/amr_db_python_env/bin/python /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/scripts/amr-finder/amr-finder.py -i $dir/input_user.fasta -p -c 40 -b blastp -db /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_peptides_id.fasta --alignments -o $dir/outx > /dev/null &");
#	print "python /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/scripts/amr-finder/amr-finder.py -i $dir/input_user.fasta -p -c 40 -b blastp -db /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_peptides_id.fasta --alignments -o $dir/outx";
		}
		if($db == "nu") {
                shell_exec("/bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran n nucleotides_id.fasta");
		#shell_exec("python /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/scripts/amr-finder/amr-finder.py -i $dir/input_user.fasta  -n -c 40 -t 8 -b blastn -db /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_nucleotides_id.fasta --alignments  -o $dir/outx");
		}
	echo '<img src="images/wait.gif"  class="center" alt="Please Wait"><br>';	
	echo "<h2 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes.</h2> "; echo "<h2 align='center'> OR <br>"; echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran </a> </h2>";
	echo "<meta  http-equiv='refresh' content='10;url=blastoutput.php?ran=$ran' />";		
#	sleep(10);
#	echo "<pre>";	include ("$dir/output.html"); echo "</pre>";
#	}
 
/*	if($method == "RUN RGI software against the database"){
		if($db == "pr") {
		shell_exec("python /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/rgi-4.0.2/rgi main -i $dir/input_user.fasta -o $dir/out -t protein -n 4 --clean --include_loose &");
		}
		if($db == "nu") {
		shell_exec("python /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/rgi-4.0.2/rgi main -i $dir/input_user.fasta -o $dir/out -t contig -n 4 --clean --include_loose &");
		}   
#	shell_exec("perl /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/convert_ajax.pl $dir/out.txt | perl -pi -e \"s/'/\\\"/g\"> $dir/ajax.txt");
#	sleep(10);
	echo '<img src="images/wait.gif"  class="center" alt="Please Wait"><br>';
	echo "<h2 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes. </h2>"; echo "<h2 align='center'> OR <br>"; echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/rgioutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/rgioutput.php?ran=$ran </a> </h2>";
	echo "<meta  http-equiv='refresh' content='10;url=rgioutput.php?ran=$ran' />";	
	}
## Running BLAST

	#	echo "<br>$data[2]****$data[3]$data[4]***$data[5]****$distance***<br>";
	#	echo "<meta  http-equiv='refresh' content='1;url=check.php?ran=$ran' />";
*/		

	/*This function will convert the input file into fasta if it is not one.
		Return an Arrary [0]: sequence 
		 				 [1] is the database that associate with either Nucleotide sequence or Protein sequence => nu or pr  */
	function check_seq_input($sep_input){
		// echo $sep_input;
		$rt[]="";
		// check if it is fasta file if not convert it into fasta file
		if(preg_match("/^>[a-zA-z\s]*[\r\n]+/",$sep_input)){
			// echo" It is a Fasta File<br>";
			if(is_protein($sep_input)){
				$rt[]=$sep_input;
				$rt[]="pr";
				
			}else{
				$rt[]=$sep_input;
				$rt[]="nu";
			}
		}else{
			// echo "It is not a fasta file.<br> Converting...<br>";
			#$temp =">Test Sequence\n";
			$sep_input=$temp.$sep_input;
			// echo $sep_input;
			if(is_protein($sep_input)){
				$rt[]=$sep_input;
				$rt[]="pr";
			}else{
				$rt[]=$sep_input;
				$rt[]="nu";
			}
		}
		return $rt;
	}
	/*This function will determind if a sequence is a protein or not 
	  Return 1 if it is a protein sequence 
	  Return 0 if it is not a Protein sequence */
	function is_protein($sq){
		return preg_match("/^>[a-zA-z\s]*[\r\n]+M/", $sq);		
	}
?> 
</div>
<?php include 'includes/footer.php';?>

