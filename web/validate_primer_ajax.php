<?php

// this script creats the job
$dir = $_POST['dir1'];
$user_file= $_POST['user_file'];
$output_path= $_POST['output_path'];
$green_light_to_excute= $_POST['green_light_to_excute'];
	// output files 
    if(trim($_POST['reference_genebank'])!='empty'){
	     //execute  when user provide genbank id 
	      $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --genbank_id ".trim($_POST['reference_genebank'])." --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
	     
    }elseif(trim($_POST['reference_fastafile'])!='empty'){
    	// execute when user provide reference file
     	 $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database $dir/$user_file --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
    }else{
	     	// default using AMR DB
	     $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/dbs/amr_dbs/amrdb_nucleotides.fasta --forward_primers $dir/frd_primer.fasta --reverse_primers $dir/rvs_primer.fasta --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
	     
    }

    // then take the output file convert it to json data file. 
	// cut -f1,2,3,4,7,8 Tabular_Output.txt | perl -pi -e "s/\t/,/g" > x
	// perl ../../../csv2json.pl x > data.json
// echo $dir;
    shell_exec("cut -f1,2,3,4,7,8 $dir/out/Tabular_Output.txt | perl -pi -e 's/\t/,/g' > $dir/out/x") ;
    shell_exec("perl csv2json.pl $dir/out/x > $dir/out/data.json");
 	// echo ;

    // echo 'a';
    // echo back the PID to track
// echo "";





//     function exit_die($messages){
        
//         echo "<br><br><br><br><h2 align='center'><b><i>".$messages."</i></b></h2><br><br><br><br><br><br><br><br><br><br><br><br>";
//         include 'includes/footer.php';
//             die();
//     }
//     function valid_file_upload($fileName){
//         $name = explode('.', $_FILES[$fileName]['name']);
//         if($name[count($name)-1]=='txt'||$name[count($name)-1]=='fasta'||$name[count($name)-1]=='fa'){
//             return 1;
//             }
//             return 0;
//         }
//     if (isset($_POST['method'])) {
//         # code...

//         if(trim($_FILES['forward_seq_fileToUpload']['name'])==''&&trim($_POST['primer_f_seq_txtfield'])==''){

//             exit_die("Please either upload a file or paste paste primary sequence in the textarea box");
//         }
//         // check file upload inputs
    
//         if(!valid_file_upload('forward_seq_fileToUpload')&&trim($_FILES['forward_seq_fileToUpload']['name'])!=''){
//             exit_die("Please upload correct file format");
//         }
//         // check if file upload has error
//         if(trim($_FILES['rev_seq_fileToUpload']['name'])==''&&trim($_POST['primer_r_seq_txtfield'])==''){

//             exit_die("Please either upload a file or paste paste primary sequence in the textarea box");
//         }
//         // check file upload inputs
    
//         if(!valid_file_upload('rev_seq_fileToUpload')&&trim($_FILES['rev_seq_fileToUpload']['name'])!=''){
//             exit_die("Please upload correct file format");
//         }

//         // generate a temp folder consist random numbers to store input and output files 

//         $ran= rand(100, 100000);
//         $dir = "/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran";
//         $output_path="/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out";
//         exec("mkdir $dir");
//         exec("chmod 777 $dir"); 
//          exec("mkdir $output_path");
//         exec("chmod 777 $output_path"); 
    
    
//     // use a string to check the input type. 
//     $f_prime_input_file_type =trim($_POST['primer_f_seq_txtfield'])!='' ?'text':'file';
//     $r_prime_input_file_type =trim($_POST['primer_r_seq_txtfield'])!=''?'text':'file';


//     //when forward input is a file point to the file. 
//     // if not create a local file using input text. then point to the file.
//     $f_input_path='';
//     $r_input_path='';
//     $green_light_to_excute=0;


//     // if the user uploaded a file, then move the file to correspond directory. 
//     // if the user 

//     if($f_prime_input_file_type=='file'){

//         $tmp_name = $_FILES["forward_seq_fileToUpload"]["tmp_name"];
//         // further validation/sanitation of the filename may be appropriate
//         $name = basename($_FILES["forward_seq_fileToUpload"]["name"]);
//         // echo "move ---------    ".$name.' to :  -------  '."/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/".$name.'<br>';
//         $succ= move_uploaded_file($tmp_name, "$dir/$name");
//         $green_light_to_excute++;
//         $f_input_path="$dir".$_FILES['forward_seq_fileToUpload']['tmp_name'];
//     }else{
//         $my_file_f = "$dir/frd_primer.fasta";
//         $handle = fopen($my_file_f, 'w') or die('Cannot open file:  '.$my_file_f);
//         $data =$_POST['primer_f_seq_txtfield'];
//         fwrite($handle, $data);
//         $green_light_to_excute++;

//     }   
//     if($r_prime_input_file_type=='file'){
//         $tmp_name = $_FILES["rev_seq_fileToUpload"]["tmp_name"];
//         $name = basename($_FILES["rev_seq_fileToUpload"]["name"]);
//         $r_input_path="$dir".$_FILES['rev_seq_fileToUpload']['tmp_name'];
//         move_uploaded_file($tmp_name, "$dir/$name");
//         $green_light_to_excute++;
//     }else{
//         $my_file_r = "$dir/rvs_primer.fasta";
//         $handle = fopen($my_file_r, 'w') or die('Cannot open file:  '.$my_file_r);
//         $data =$_POST['primer_r_seq_txtfield'] ;
//         fwrite($handle, $data);
//         $green_light_to_excute++;

//     }

//     // execute cmd to run jobs

//     // if(isset($_POST['reference_genebank'])){
//     //  if($green_light_to_excute==2){
//     //   $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --genbank_id ".trim($_POST['reference_genebank'])." --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output /usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out/ --email yewang@jcvi.org"); 
//     //  }
//     // }elseif(isset($_POST['reference_fastafile'])){
//     //   $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database $dir/$user_file --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output /usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out/ --email yewang@jcvi.org"); 
//     // }else{
//     //  if($green_light_to_excute==2){
//     //           $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/dbs/amr_dbs/amrdb_nucleotides.fasta --forward_primers $dir/frd_primer.fasta --reverse_primers $dir/rvs_primer.fasta --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output /usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out/ --email yewang@jcvi.org"); 
//     //  }
//     // }


// }


?>