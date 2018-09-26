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

        if(!isset($_POST['method'])){
        	 echo "<br><br><br><br><h2 align='center'><b><i>".$messages."</i></b></h2><br><br><br><br><br><br><br><br><br><br><br><br>";
        include 'includes/footer.php';
            die();
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


        if(trim($_FILES['reference_fastafile']['name'])!=''){
            if(!valid_file_upload('reference_fastafile')){
            exit_die("Please input correct file type for reference");
            }
        }

      
        $date_rand='primer_'.date("Y_m_d_H_i_s");
		$ran=$date_rand.rand(0, 10000);

        // $ran= rand(100, 100000);
        // $ran = 
        $dir = "/usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$ran";
        $output_path="$dir/out";
        exec("mkdir $dir");
        exec("chmod 777 $dir"); 
         exec("mkdir $output_path");
        exec("chmod 777 $output_path"); 
    
	    // use a string to check the input type. 
	    $f_prime_input_file_type =trim($_POST['primer_f_seq_txtfield'])!='' ?'text':'file';
	    $r_prime_input_file_type =trim($_POST['primer_r_seq_txtfield'])!=''?'text':'file';

	    // create input forward file and input reverse file 
        // and  the reference input file entered(if there is any) 
	    $green_light_to_excute=0;
        // check if input forward seq is a file 
		    if($f_prime_input_file_type=='file'){
		        $tmp_name = $_FILES["forward_seq_fileToUpload"]["tmp_name"];
		        $name = basename($_FILES["forward_seq_fileToUpload"]["name"]);
		        $succ= move_uploaded_file($tmp_name, "$dir/$name");
		        $green_light_to_excute++;
		        $my_file_f="$dir/".$_FILES['forward_seq_fileToUpload']['name'];
		    }else{
		        $my_file_f = "$dir/frd_primer.fasta";
		        $handle = fopen($my_file_f, 'w') or die('Cannot open file:  '.$my_file_f);
		        $data =$_POST['primer_f_seq_txtfield'];
		        fwrite($handle, $data);
		        $green_light_to_excute++;
		    }   
        // check if input reverse seq is a file 

		     if($r_prime_input_file_type=='file'){
		        $tmp_name = $_FILES["rev_seq_fileToUpload"]["tmp_name"];
		        $name = basename($_FILES["rev_seq_fileToUpload"]["name"]);
		        $my_file_r="$dir/".$name;
		        move_uploaded_file($tmp_name, "$dir/$name");
		        $green_light_to_excute++;
		    }else{
		        $my_file_r = "$dir/rvs_primer.fasta";
		        $handle = fopen($my_file_r, 'w') or die('Cannot open file:  '.$my_file_r);
		        $data =$_POST['primer_r_seq_txtfield'] ;
		        fwrite($handle, $data);
		    }
        // check if input reference is a file.

		    if(count($_FILES['reference_fastafile']['name'])!=0){
                $tmp_name = $_FILES["reference_fastafile"]["tmp_name"];
                $name = basename($_FILES["reference_fastafile"]["name"]);
                $reference_fastafile_path="$dir/".$name;
                move_uploaded_file($tmp_name, "$dir/$name");
		    }

	// echo "$_POST($reference_genebank)";
	echo '<img src="images/wait.gif"  class="center" alt="Please Wait"><br>';	
	echo "<h2 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes.</h2> "; echo "<h2 align='center'> OR <br>"; echo "Visit the following link <br><a href='https://cdc-1.jcvi.org:8081/validate_primer_submit.php?ran=$ran'>https://cdc-1.jcvi.org:8081/validate_primer_submit.php?ran=$ran </a> </h2>";
	echo "<meta  http-equiv='refresh' content='2;url=validate_primer_submit.php?ran=$ran' />";		

?> 

<script type="text/javascript">

$.ajax({ url: 'validate_primer_ajax.php',
         data: {	output_path: '<?php echo "$output_path";?>', 
     				reference_fastafile:'<?php echo isset($reference_fastafile_path)?$reference_fastafile_path:"empty"; ?>',
     				reference_genebank:'<?php echo isset($_POST["reference_genebank"])?$_POST["reference_genebank"]:"empty"; ?>',
     				dir1:'<?php echo "$dir"; ?>',
                    my_file_r:'<?php echo isset($my_file_r)?$my_file_r:"empty";?>',
                    my_file_f:'<?echo isset($my_file_f)?$my_file_f:"empty";?>'
     		     },
         type: 'post',
         success: function(output) {
                  }
});

</script>
</div>
<?php include 'includes/footer.php';?>

