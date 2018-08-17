<?php



echo $_POST['output_path'];
// echo $_POST['entry'];



    if(isset($_POST['reference_genebank'])){
	     if($green_light_to_excute==2){
	      $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --genbank_id ".trim($_POST['reference_genebank'])." --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
	     }
    }elseif(isset($_POST['reference_fastafile'])){
     	 $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database $dir/$user_file --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
    }else{
	     if($green_light_to_excute==2){
	     $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/dbs/amr_dbs/amrdb_nucleotides.fasta --forward_primers $dir/frd_primer.fasta --reverse_primers $dir/rvs_primer.fasta --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output $output_path/ --email yewang@jcvi.org"); 
	     }
    }
    // echo back the PID to track
// echo "";
?>