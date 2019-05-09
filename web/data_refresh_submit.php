<?php 
include 'includes/header.php'; 
include 'includes/config.inc.php';

if(!isset($_SESSION['userID'])){
  echo '<br><br><br><br><h2 align="center">Please Log in as an admin</h5><br><br><br><br><br><br><br>';
include 'includes/footerx.php';
  die;
}
 	echo "<br><br><br><br><br><br><br>";
?>


<?php
$path =  `pwd`;

$path = "/usr".$path;
$path=trim($path);
// when submit need to commmon the line below.
$path = str_replace("ifs2_projdata","projdata",$path);



$path = trim($path);
$path = str_replace("htdocs","cgi-bin/AMR-Finder-master/dbs/amr_dbs",$path);



  $sql = "SELECT idt.Source, idt.ID, idt.Source_ID, ids.End5, ids.End3, idt.Gene_Symbol, idt.Allele, idt.Parent_Allele_Family, ids.Feat_Type, ids.NA_Sequence, ids.AA_Sequence, idt.Parent_Allele FROM Identity idt, Identity_Sequence ids  WHERE  idt.ID = ids.Identity_ID"; 
  $query=mysql_query($sql);
	// echo ">Source DB | AMR-DB ID | GenBank ID : CDS Coordinates | Gene Symbol | Allele | Drug Class | Drug Family | Drugs | Parent Allele | Parent Allele Family | SNP Info |<br>";

 	$rrna_file = fopen($path."/amrdb_rRNA.fasta", "w") or die("Unable to open file!");
 	$ppt_file = fopen($path."/amrdb_peptides.fasta", "w") or die("Unable to open file!");
 	$nucl_file = fopen($path."/amrdb_nucleotides.fasta", "w") or die("Unable to open file!");

  while($data=mysql_fetch_array($query)) {
		 $class_sql = "SELECT cl.Drug_Class, cl.Drug_Family, cl.Drug, cl.ID FROM Classification cl  WHERE  $data[ID] = cl.Identity_ID"; 
  		 $class_query=mysql_query($class_sql);
  		 $class_data= mysql_fetch_array($class_query);

  		 $va_sql = "SELECT va.SNP FROM Variants va  WHERE  $class_data[ID] = va.Classification_ID"; 
  		 $va_query=mysql_query($va_sql);
  		 $va_data= mysql_fetch_array($va_query);



		 $data_string = ">$data[Source]|$data[ID]|$data[Source_ID]:$data[End5]-$data[End3]|$data[Gene_Symbol]|$data[Allele]|$class_data[Drug_Class]|$class_data[Drug_Family]|$class_data[Drug]|$data[Parent_Allele]|$class_data[Parent_Allele_Family]|$va_data[SNP]|\n";
		 $data_string = preg_replace('/ /', '_', $data_string);

  		 if(strtolower(trim($data[Feat_Type]))=="rrna"){
  	//		write only on “amrdb_rRNA.fasta” file and you have only NA_sequence
  		 	if(trim($data[NA_Sequence])!=""){

	  			$amrdb_rrna = $data_string."$data[NA_Sequence]\n";
		  		fwrite($rrna_file,$amrdb_rrna);
		  	}
  		 }else{
  	// 	 	Peptide (AA_sequence).  -- amrdb_peptides.fasta
			// Nucleotide (NA_sequence). -- amrdb_nucleotides.fasta
  		 	if(trim($data[AA_Sequence])!=""){
	  			$amrdb_peptides = $data_string."$data[AA_Sequence]\n";
	  			fwrite($ppt_file,$amrdb_peptides);
  		 	}
  		 	if(trim($data[NA_Sequence])!=""){
  		 		$amrdb_nucleotides = $data_string."$data[NA_Sequence]\n";
	  			fwrite($nucl_file,$amrdb_nucleotides);
  		 	}
  		 }
	} 
 	fclose($rrna_file);
 	fclose($nucl_file);
 	fclose($ppt_file);
	echo "<h1 align='center'>Reference Database Refreshed with the current MySQL database<h1>";
 	echo "<br><br><br><br><br><br><br>";
include 'includes/footer.php'; 

?>
