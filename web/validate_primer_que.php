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



    function exit_die($messages){
        
        echo "<br><br><br><br><h2 align='center'><b><i>".$messages."</i></b></h2><br><br><br><br><br><br><br><br><br><br><br><br>";
        include 'includes/footer.php';
            die();
    }
    function valid_file_upload($fileName){
        $name = explode('.', $_FILES[$fileName]['name']);
        if($name[count($name)-1]=='txt'||$name[count($name)-1]=='fasta'||$name[count($name)-1]=='fa'){
            return 1;
            }
            return 0;
        }
    if (!isset($_POST['method'])) {

    	exit_die("Please go to validate primer page");
        }


//  check if the process is done then 

        if(isset($_POST['method'])){
        	// echo "string";
        	// die();
        }

        # code...

        if(trim($_FILES['forward_seq_fileToUpload']['name'])==''&&trim($_POST['primer_f_seq_txtfield'])==''){

            exit_die("Please either upload a file or paste paste primary sequence in the textarea box");
        }
        // check file upload inputs
    
        if(!valid_file_upload('forward_seq_fileToUpload')&&trim($_FILES['forward_seq_fileToUpload']['name'])!=''){
            exit_die("Please upload correct file format");
        }
        // check if file upload has error
        if(trim($_FILES['rev_seq_fileToUpload']['name'])==''&&trim($_POST['primer_r_seq_txtfield'])==''){

            exit_die("Please either upload a file or paste paste primary sequence in the textarea box");
        }
        // check file upload inputs
    
        if(!valid_file_upload('rev_seq_fileToUpload')&&trim($_FILES['rev_seq_fileToUpload']['name'])!=''){
            exit_die("Please upload correct file format");
        }

        if(isset($_POST['reference_fastafile'])){
        	if(!valid_file_upload('reference_fastafile')){
            exit_die("Please input correct file type for reference");

        	}

        }

        $ran= rand(100, 100000);
        $dir = "/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/primer_tmp/$ran";
        $output_path="$dir/out";
        exec("mkdir $dir");
        exec("chmod 777 $dir"); 
         exec("mkdir $output_path");
        exec("chmod 777 $output_path"); 
    
	    // use a string to check the input type. 
	    $f_prime_input_file_type =trim($_POST['primer_f_seq_txtfield'])!='' ?'text':'file';
	    $r_prime_input_file_type =trim($_POST['primer_r_seq_txtfield'])!=''?'text':'file';


	    //when forward input is a file point to the file. 
	    // if not create a local file using input text. then point to the file.
	    $f_input_path='';
	    $r_input_path='';
	    $green_light_to_excute=0;
		    if($f_prime_input_file_type=='file'){

		        $tmp_name = $_FILES["forward_seq_fileToUpload"]["tmp_name"];

		        // var_dump( $_FILES);
		        // further validation/sanitation of the filename may be appropriate
		        $name = basename($_FILES["forward_seq_fileToUpload"]["name"]);
		        // echo "move ---------    ".$name.' to :  -------  '."/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/".$name.'<br>';
		        $succ= move_uploaded_file($tmp_name, "$dir/$name");
		        $green_light_to_excute++;
		        $f_input_path="$dir".$_FILES['forward_seq_fileToUpload']['tmp_name'];
		    }else{
		        $my_file_f = "$dir/frd_primer.fasta";
		        $handle = fopen($my_file_f, 'w') or die('Cannot open file:  '.$my_file_f);
		        $data =$_POST['primer_f_seq_txtfield'];
		        fwrite($handle, $data);
		        $green_light_to_excute++;

		    }   
		    if($r_prime_input_file_type=='file'){
		        $tmp_name = $_FILES["rev_seq_fileToUpload"]["tmp_name"];
		        $name = basename($_FILES["rev_seq_fileToUpload"]["name"]);
		        $r_input_path="$dir".$_FILES['rev_seq_fileToUpload']['tmp_name'];
		        move_uploaded_file($tmp_name, "$dir/$name");
		        $green_light_to_excute++;
		    }else{
		        $my_file_r = "$dir/rvs_primer.fasta";
		        $handle = fopen($my_file_r, 'w') or die('Cannot open file:  '.$my_file_r);
		        $data =$_POST['primer_r_seq_txtfield'] ;
		        fwrite($handle, $data);
		        $green_light_to_excute++;

		    }

    // var_dump($_POST);
    // excute.
    // if(isset($_POST['reference_genebank'])){
	   //   if($green_light_to_excute==2){
	   //    $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --genbank_id ".trim($_POST['reference_genebank'])." --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
	   //   }
    // }elseif(isset($_POST['reference_fastafile'])){
    //  	 $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database $dir/$user_file --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
    // }else{
	   //   if($green_light_to_excute==2){
	   //   $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/dbs/amr_dbs/amrdb_nucleotides.fasta --forward_primers $dir/frd_primer.fasta --reverse_primers $dir/rvs_primer.fasta --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
	   //   }
    // }



    // create json file here
	 //    function txt_to_Json($file,$delimiter){
		//     if (($handle = fopen($file, "r")) === false)
		//     {
		//             die("can't open the file.");
		//     }

		//     $csv_headers = fgetcsv($handle, 4000, $delimiter);
		//     $csv_json = array();

		//     while ($row = fgetcsv($handle, 4000, $delimiter))
		//     {
		//             $csv_json[] = array_combine($csv_headers, $row);
		//     }

		//     fclose($handle);
		//     return json_encode($csv_json);
		// }


	// $data = txt_to_Json("$output_path/Tabular_Output.txt", ",");
	// // echo $data;

	// $data = str_replace("{","[",$data);
	// $data = str_replace("}","]",$data);


	echo '<img src="images/wait.gif"  class="center" alt="Please Wait"><br>';	
	echo "<h2 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes.</h2> "; echo "<h2 align='center'> OR <br>"; echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran </a> </h2>";
	echo "<meta  http-equiv='refresh' content='2;url=validate_primer_submit.php?ran=$ran' />";		
	








?> 

<script type="text/javascript">
	
$.ajax({ url: 'validate_primer_ajax.php',
         data: {	output_path: '<?php echo "$output_path";?>', 
     				entry:''},
         type: 'post',
         success: function(output) {
                      alert(output);
                  }
});

</script>
</div>
<?php include 'includes/footer.php';?>

