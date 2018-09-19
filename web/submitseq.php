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


// read the upload file if there is one
// 
function valid_file_upload($fileName){
    $name = explode('.', $_FILES[$fileName]['name']);
    if($name[count($name)-1]=='txt'||$name[count($name)-1]=='fasta'||$name[count($name)-1]=='fa'){
     	return 1;
     }
     	return 0;
}

// var_dump(valid_file_upload('seq_file'));
// $haha = file_get_contents($_FILES['seq_file']['tmp_name']); 
// var_dump(isset($_FILES['seq_file']));
// echo "<br>".$haha;
// var_dump(($_FILES['size']==0));


if(isset($_FILES['seq_file'])){
	
	// check input file type
	if(valid_file_upload('seq_file')){



		// need to move it to local file then read it 
		// $seq = file_get_contents($_FILES['seq_file']['tmp_name']);
		// var_dump($seq);
		
		// $seq = readfile($_FILES['seq_file']['tmp_name']);
		$file = fopen($_FILES['seq_file']['tmp_name'],"r");
		// var_dump(fgets($file));
		$seq = fread($file,filesize($_FILES['seq_file']['tmp_name']));
		// die;
		// var_dump($seq);
		// die;

	}else{
		 echo "<h2 align='center'><b><i>Please upload correct sequence file type </i></b></h2>";
		die;
	}
}else{
	$seq = !empty($_POST['seq']) ? $_POST['seq'] : ''; //echo "**$seqx* $ran<br>";

}



// die();

$seq = str_replace("\r", "", $seq);
//process input. get ride of empty space and end line char start from second line till last. 

preg_match("/^>.*\n/",$seq,$match1);
preg_match("/(?ms)(?!\A)^\S.*/",$seq,$match2);
preg_match("/.*\n(.*)/",$seq,$matchx);

// var_dump($matchx[1]);


// check if the sequence context is empty
if(trim($match2[0])==''||!preg_match("/^>.*\n/",$seq)){
echo "<h2 align='center'><b><i>Please input a sequence in the text area</i></b></h2>";
echo "<h2 align='center'><b><i>Or check your input</i></b></h2>"; die;
}

$part2=preg_replace("/\r/", "", $match2[0]); 
$part2=trim(str_replace(" ","",$part2));
$part2=trim(str_replace(" ","",$part2));
$part2=trim(str_replace(" ","",$part2));
$seq=$match1[0].$part2;

// echo "1 ";

// var_dump(trim($match2[0]));

// var_dump($seq);

// Declearing db and sequence
// var_dump($match2);
$rt_ck = check_seq_input($seq, $matchx[1]);
$seq =  $rt_ck[1]; 
$db = $rt_ck[2];
// echo "2 ";


//var_dump($rt_ck);
// die;

// $db= is_protein($match2);
#Determine the sequece type in $seq and based on than assigned $db value
// $db = !empty($_POST['db']) ? $_POST['db'] : ''; #echo "**$db OO**<br>";
$method = !empty($_POST['method']) ? $_POST['method'] : ''; #echo "**$option OO**<br>";
// echo "2.1  ";

$file1name = basename($_FILES['seq_file']['name']); #echo "** $file1name <br>";
// echo "3";
// seq_file
#$email = !empty($_POST['email']) ? $_POST['email'] : ''; #echo "**$option OO**<br>";

# Check for input. ignore the firs line.
// $arr1 = str_split($seq);
if($seq == ""){ echo "<h2 align='center'><b><i>Please input a sequence in the text area</i></b></h2>"; die;}
// echo "4";






# Creating tmp directory
		#$ran= "61899";
	$ran= rand(100, 100000);
        $dir = "/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$ran";
        exec("mkdir $dir");
        exec("chmod 777 $dir"); 
        // echo "***$ran***"; 
#*********************Parsing of input sequenc*************
// die();
//used to be ^>\w+ 
// echo "<br>";
// var_dump(isset($_FILES['seq_file']['name']));
// var_dump($_FILES);

// die;
       
	if(isset($_FILES['seq_file']['name'])){
		$tmp_name = $_FILES["seq_file"]["tmp_name"];
		$name = basename($_FILES["seq_file"]["name"]);
		$succ= move_uploaded_file($tmp_name, "$dir/input_user.fasta");
	}else {

	$FP1=fopen("$dir/input_user.fasta","w");
	fwrite($FP1,$seq);
	#print "RAN:$ran";
	fclose($FP1);
	}


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
		$details=$op="";
	#	$command="/bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran p";
               $pid =  shell_exec("nohup /bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran p > /dev/null 2>/dev/null &  echo $!");
	#	$details=exec("/bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran p & echo $!",$op);
#	shell_exec("/local/ifs2_projdata/8500/projects/CDC/server/amr_db_python_env/bin/python /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/scripts/amr-finder/amr-finder.py -i $dir/input_user.fasta -p -c 40 -b blastp -db /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_peptides_id.fasta --alignments -o $dir/outx > /dev/null &");
		}
		if($db == "nu") {

               $pid =  shell_exec("nohup /bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran n > /dev/null 2>/dev/null &  echo $!");
		//echo "/bin/sh /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/run_amr_finder.sh $ran n ";
		}

	echo '<img src="images/wait.gif"  class="center" alt="Please Wait"><br>';	
	echo "<h2 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes.</h2> "; echo "<h2 align='center'> OR <br>"; echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran&pid=$pid </a> </h2>";
	echo "<meta  http-equiv='refresh' content='3;url=blastoutput.php?ran=$ran&pid=$pid' />";		
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
	function check_seq_input($sep_input,$sq){
// echo "string3";
	
		// echo $sep_input;
		$rt[]="";
		// check if it is fasta file if not convert it into fasta file
		if(preg_match("/^>.*/",$sep_input)){
// echo "string4";

			$tmp_seq=$sq;
			$rt[]=$sep_input;
			$rt[]=is_protein($sq);
		}else{
			// echo "It is not a fasta file.<br> Converting...<br>";
	echo "<h2 align='center'><b><i>Please check your input</i></b></h2>"; die;
		}
		return $rt;
	}
	/*This function will determind if a sequence is a protein or not 
	  Return 1 if it is a protein sequence 
	  Return 0 if it is not a Protein sequence */
	function is_protein($sq){
		if(count( array_unique(str_split($sq)))==4){
			return 'nu';			
		}else{
			return 'pr';
		}
		
	}
?> 
</div>
<?php include 'includes/footer.php';?>

