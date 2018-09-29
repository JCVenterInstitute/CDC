<?php include 'includes/header.php';?>
<?php require_once('dbconfig.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

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


<section class="main-container">
   <div class="containerx">
        <div class="row">
	         <div class="main col-md-12">
			        
			        <?php


			       
					if(isset($_POST['method'])){
						  echo '<h2 align="center">Data has been submitted into the ARMdb for admin review. <br><br>Once admin approve the data, it will be visible in AMRdb</h2><br><h5 align="center">Thank you for update new information</h5>';
				       // var_dump($_POST);
				        $mydb=  new Database();
				       	$con = $mydb->dbConnection();
						$GLOBALS['con']=$con;
				        update_all_tables($con);

					}else{
						echo' <h2 align="center">Please Go back to submit form</h2>';
					}


					// functions to update each items.

					/*return table columns*/
					function tablecols($b){
						$temp='';
						foreach(array_values($b) as $i){		
							$temp = $temp.",".$i["Field"];
						}
						// trim off the extra comma
						$temp[0]='';
						return $temp;
					}
					/* return array which stores values for each tables 
					Tables: Identity, classification, Identity_Squence, Variants, Assemly, Sample_Metadata, Identity_assembly, Taxonomy
					*/
					function update_all_tables($con){
						//Provide values for Identity table
						$id_table_val = identity_table_array();
						//Provide value for classification table
						$classification_table_val = classification_table_array();
						//Provide values for Identity_Sequence table
						$id_seq_table_val = identity_Sequence_table_array();
						//Provide values forVariants
						$variant_table_val= variant_table_array();
						//Provide values for Sample_metadata table 
						$sample_meta_val= sample_metadata_table_array();
						//Provide valuesfor taxonomy table 
						$tax_table_val= taxonomy_table_array();
						//Provide valuesfor assemly table
						$assemnly_val = assemly_table_array();
						//Provide valuesforidentityassembly table
						$ida_val= identity_assembly_table_array();
						//Provide values for antibiogram 
						$antibigram_val =antibiogram_table_arry();
						//Provide values for threat table 
						$threat_val = threat_table_arry();

						// passing values for each tables to updates function to update. 
						updates_info_into_db($con, $id_table_val, $classification_table_val , $id_seq_table_val, $variant_table_val,$sample_meta_val,$tax_table_val,$assemnly_val,$ida_val,$antibigram_val,$threat_val);
						return "<br>Called values function" ; // useful debug string
					}

					function updates_info_into_db($con,$id_t, $class_t, $id_seq_t,$varian_t, $samp_t, $tax_t, $assem_t,$ida_t, $antigram_t,$threat_t){
						 // echo "Updaing ID: ";
						 $t_col = getting_colums_from_a_table($con,'Identity');
						$sth = $con->prepare('UPDATE Identity SET Gene_Symbol=? ,Allele=?,EC_Number=?,Parent_Allele_Family=?,Parent_Allele=?,Source=?,Source_ID=?,Protein_ID=?,Protein_Name=?,Pubmed_IDs=?,Is_Active=?,Status=?,Created_By=?,Modified_By=? WHERE ID =?;');
						// start at 1 since the ID wont be needed.
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							$new_i=$i+1; 
							$in_val = "\"".$id_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $id_t[$i], PDO::PARAM_STR);
							// echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$id_t[$i]."<br>";
						}
						try {
							// echo "Trying to execute Identity...";
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Identity </b> <br> : ".$e;
							// die();
							echo "<br>  <b>Error Identity:</b><br> Input must be a number for IDs.'$e'<br>".$e;
							echo $e;
							die();
						}

						 $t_col = getting_colums_from_a_table($con,'Classification');
						 // insert 
						//  $query = 'INSERT INTO Classification'.'('.implode(',', $t_col) .') VALUES('.$questions.');';
						// // echo $query;
						 $sth = $con->prepare('UPDATE Classification SET Drug_Name=? ,Drug_Class=? ,Drug_Family=? ,Drug_Sub_Class=? ,Identity_ID=? ,Is_Active=? ,Created_By=? ,Modified_By=? WHERE ID=?;');
						 // echo "<br>";
						 // var_dump( $t_col);
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$class_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $class_t[$i], PDO::PARAM_STR);
							// echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$class_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Classification </b> <br> : ".$e;
							// die();
							echo "<br>  <b>Error Classification :</b><br> Input must be a number for IDs.<br>";
							die();
						}

						$t_col = getting_colums_from_a_table($con,'Identity_Sequence');

						$sth = $con->prepare('UPDATE Identity_Sequence SET End5=? ,End3=? ,NA_Sequence=? ,AA_Sequence=? ,Feat_Type=? ,Identity_ID=? ,Created_By=? ,Modified_By=?  WHERE ID =? ;');
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$id_seq_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $id_seq_t[$i], PDO::PARAM_STR);
							 // echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$id_seq_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
								// echo "<br>  <b>Error ->Identity_Sequence </b> <br> : ".$e;
								// die();
							echo "<br>  <b>Error Identity Sequence:</b><br> Input must be a number for IDs.<br>";
							die();
						}

						// Update Variants table 
						$t_col = getting_colums_from_a_table($con,'Variants');
						$sth = $con->prepare('UPDATE Variants SET SNP=? ,Drug_Name=? ,PubMed_IDs=? ,Identity_Sequence_ID=? ,Classification_ID=? ,Is_Active=? ,Created_By=? ,Modified_By=? WHERE ID=?;');
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$varian_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $varian_t[$i], PDO::PARAM_STR);
							// echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$varian_t[$i]."<br>";
						
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Variants </b> <br> : ".$e;
							// die();
							echo "<br>  <b>Error Variant :</b><br> Input must be a number for IDs.<br>";
							die();
						}

						// echo "<br>Done Fourth update <br>";
						// die();

						 $t_col = getting_colums_from_a_table($con,'Sample_Metadata');
						  // insert_helper($t_col, $samp_t,'Sample_Metadata');
						// $questions=str_repeat(", ? ", sizeof($t_col));
						// $questions[0]="";
						// $query = 'INSERT INTO Sample_Metadata'.'('.implode(',', $t_col) .') VALUES('.$questions.');';
						// echo $query;
						// echo "<br>";
						
						$sth = $con->prepare('UPDATE Sample_Metadata SET Source=? ,Source_ID=? ,Isolation_site=? ,Serotyping_Method=? ,Source_Common_Name=? ,Specimen_Collection_Date=? ,Specimen_Collection_Location_Country=? ,Specimen_Collection_Location=? ,Specimen_Collection_Location_Latitude=? ,Specimen_Collection_Location_Longitude=? ,Specimen_Source_Age=? ,Specimen_Source_Developmental_Stage=? ,Specimen_Source_Disease=? ,Specimen_Source_Gender=? ,Health_Status=? ,Treatment=? ,Specimen_Type=? ,Symptom=? ,Host=? ,Created_By=? ,Modified_By=? WHERE ID=?;');
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$samp_t[$i]."\"";
							// echo $i+1;
							$sth->bindValue($i+1, $samp_t[$i], PDO::PARAM_STR);
							// echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$samp_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Sample_Metadata </b> <br> : ".$e;
							// die();
							echo "<br>  <b>Error Sample Metadata:</b><br> Input must be a number for IDs.<br>";
							die();
						}
						// echo "<br>Done Fifth update <br>";
						// die();

						 $t_col = getting_colums_from_a_table($con,'Taxonomy');
						
						$sth = $con->prepare('UPDATE Taxonomy SET Taxon_ID=? ,Taxon_Kingdom=? ,Taxon_Phylum=? ,Taxon_Bacterial_BioVar=? ,Taxon_Class=? ,Taxon_Order=? ,Taxon_Family=? ,Taxon_Genus=? ,Taxon_Species=? ,Taxon_Sub_Species=? ,Taxon_Pathovar=? ,Taxon_Serotype=? ,Taxon_Strain=?, Taxon_Sub_Strain=? ,Created_By=? ,Modified_By=?  WHERE ID=? ; ');
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$tax_t[$i]."\"";
							// echo $i+1;
							$sth->bindValue($i+1, $tax_t[$i], PDO::PARAM_STR);
							// echo $i;
							// echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$tax_t[$i]."<br>";

						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Taxonomy </b> <br> : ".$e;
							// die();
							echo "<br>  <b>Error Taxonomy:</b><br> Input must be a number for IDs.<br>";
							die();	
						}
						// echo "<br>Done sixth update <br>";
						// die();


						 $t_col = getting_colums_from_a_table($con,'Assemly');
						
						$sth = $con->prepare('UPDATE Assemly SET Is_Reference=? ,Sample_Metadata_ID=?,Source=?,Source_ID=?,PubMed_IDs=?,BioProject_ID=?,Taxonomy_ID=?,Plasmid_Name=?,Created_By=?,Modified_By=? WHERE ID=?;');
						for ($i=0; $i<=sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$assem_t[$i]."\"";
							// echo $i+1;
							$sth->bindValue($i+1, $assem_t[$i], PDO::PARAM_STR);
							// echo " - ".(isset($t_col[$i]) ? $t_col[$i]: "id")." =>  ".$assem_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Assemly </b> <br> : ".$e;
							// die();

							echo "<br>  <b>Error:</b><br> Input must be a number for IDs.<br>";
							die();
						}
						
						$sth = $con->prepare('UPDATE Identity_Assembly SET Mol_Type=? WHERE ID=?;');
						$sth->bindValue(1, $ida_t[1], PDO::PARAM_STR);
						$sth->bindValue(2, $ida_t[0], PDO::PARAM_STR);
						try {
							$sth->execute();
						} catch (Exception $e) {
							// echo "<br>  <b>Error ->Assemly </b> <br> : ".$e;
							// die();

							echo "<br>  <b>Error:</b><br> Input must be a number for IDs.<br>";
							die();
						}

						//update Antibiogram 
						$t_col = getting_colums_from_a_table($con,'Antibiogram');
						// var_dump($antigram_t);
						// die();
						$sth = $con->prepare('UPDATE Antibiogram SET Antibiotic=? ,Drug_Symbol=?, Laboratory_Typing_Method=?, Laboratory_Typing_Method_Version_or_Reagent=?, Laboratory_Typing_Platform=?, Measurement=?,Measurement_Sign=? ,Measurement_Units=? ,Resistance_Phenotype=? ,Testing_Standard=? ,Vendor=? WHERE ID=?;');
						for ($i=0; $i<sizeof($t_col)-2; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$antigram_t[$i]."\"";
							// echo $i+1;
							$sth->bindValue($i+1, $antigram_t[$i], PDO::PARAM_STR);
							// echo " - ".(isset($antigram_t[$i]) ? $t_col[$i]: "id")." =>  ".$antigram_t[$i]."<br>";
							// echo $i;
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							echo "<br>  <b>Error ->Assemly </b> <br> : ";
							// die();

							echo "<br>  <b>Error:</b><br> Input must be a number for IDs.<br>";
							die();
						}

						//update Antibiogram 
						$t_col = getting_colums_from_a_table($con,'Threat_Level');
						// var_dump($antigram_t);
						// die();
						$sth = $con->prepare('UPDATE Threat_Level SET Level=? WHERE ID=?;');
						for ($i=0; $i<2; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$threat_t[$i]."\"";
							// echo $i+1;
							$sth->bindValue($i+1, $threat_t[$i], PDO::PARAM_STR);
							// echo $i;
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							echo "<br>  <b>Error ->Threat </b> <br> : ";
							// die();
							echo "<br>  <b>Error:</b><br> Input must be a number for IDs.<br>";
							die();
						}
					}
					function identity_assembly_table_array(){
						$returnVal[]=$_POST['idaa_id'];
						$returnVal[]=$_POST['mol_type'];
						return $returnVal;
					}

					function identity_table_array(){
						$returnVal[]=$_POST['gene_symbol'];
						$returnVal[]=$_POST['allele'];
						$returnVal[]=$_POST['ec_number'];//EC_Number
						$returnVal[]=$_POST['parent_allele_family'];
						$returnVal[]=$_POST['parent_allele'];
						$returnVal[]=$_POST['source'];//Source => GenBank 
						$returnVal[]=$_POST['genbank_id'];//SourceID =>gen Bank ID 
						$returnVal[]=$_POST['protein_id'];
						$returnVal[]=$_POST['protein_name'];//Protein_name
						// $retrunVal[]=3;//$_POST['plasmid'];// Plasmid?? 
						$returnVal[]=$_POST['pubmed_id'];
						$returnVal[]="1";// is_active 0 or1   default is 1  
						$returnVal[]="Manual";//Status
						$returnVal[]="0";//created_By
						$returnVal[]="0";//ModifiedBy
						$returnVal[]=$_POST['id_id'];
						return $returnVal;
					}

					function classification_table_array() {
						$returnVal[]=$_POST['drug_name'];//drug_name
						$returnVal[]=$_POST['drug_class'];//drug_class
						$returnVal[]=$_POST['drug_family'];//drug_family
						$returnVal[]=$_POST['sub_drug_class'];//drug_sub_class
						$returnVal[]=$_POST['identity'];//id from identity table
						$returnVal[]="1";// is_active 0 or1   default is 1  
						$returnVal[]="0";//created_By
						$returnVal[]="0";//ModifiedBy
						$returnVal[]=$_POST['ca_id'];//id
						return $returnVal;
					}

					function identity_Sequence_table_array(){
						$returnVal[]=$_POST['end5'];
						$returnVal[]=$_POST['end3'];
						$returnVal[]=$_POST['nucleotide_sequence'];//NA Sequence
						$returnVal[]=$_POST['protein_squence'];//AA sequence
						$returnVal[]=$_POST['feat_type'];
						$returnVal[]=$_POST['identity'];// id from Identity Table 
						$returnVal[]="0";//created_By
						$returnVal[]="0";//ModifiedBy
						$returnVal[]=$_POST['id_seq_id'];
						return $returnVal;
					}

					function variant_table_array(){
						$returnVal[]=$_POST['snp'];//SNP
						// $returnVal[]=$_POST['drug_name'];//drug name 
						$returnVal[]=$_POST['pubmed_id'];//PubMed_IDs  Plasmid
						$returnVal[]=$_POST['id_seq_id'];//id from id_seq table 
						$returnVal[]=$_POST['ca_id'];//id from classification table
						$returnVal[]=1;//is_activate
						$returnVal[]="0";//created_By
						$returnVal[]="0";//ModifiedBy
						$returnVal[]=$_POST['va_id'];//id
						return $returnVal; 
					}

					function sample_metadata_table_array(){
						$returnVal[]=$_POST['source'] ;//source =>gen bank 	
						$returnVal[]=$_POST['genbank_id'] ;//SourceID => gen bank 
						$returnVal[]=$_POST['isolation_site'] ;
						$returnVal[]=$_POST['serotyping_method'];//Serotyping_method 
						$returnVal[]=$_POST['source_common_name'];//Source_common_name
						$returnVal[]=$_POST['specimen_collection_date'];
						$returnVal[]=$_POST['specimen_location_country'];
						$returnVal[]=$_POST['specimen_location'];// specimen location
						$returnVal[]=$_POST['specimen_location_lattitude'];
						$returnVal[]=$_POST['specimen_location_longitude'];
						$returnVal[]=$_POST['specimen_source_age'];
						$returnVal[]=$_POST['specimen_dev_stage'];//specimen_development stage 
						$returnVal[]=$_POST['specimen_source_disease'];
						$returnVal[]=$_POST['specimen_source_gender'];
						$returnVal[]=$_POST['specimen_health_status'];// Health status 
						$returnVal[]=$_POST['specimen_treatment'];// Treatment 	
						$returnVal[]=$_POST['speciment_type'];//Speciment_type 
						$returnVal[]=$_POST['speciment_symptom'];// symptom
						$returnVal[]=$_POST['speciment_Host'];//Host 
						$returnVal[]="0";//created_By
						$returnVal[]="0";//ModifiedBy
						$returnVal[]=$_POST['meta_id'];//id 
						return $returnVal;
					}

					function taxonomy_table_array(){
						$returnVal[]=$_POST['tax_id'];//Taxon_ID  
						$returnVal[]=$_POST['taxon_kingdom'];//Taxon_Kingdom
						$returnVal[]=$_POST['phylum'];//Taxon_Phylum
						$returnVal[]=$_POST['taxon_bacterial_bioVar'];//Taxon_Bacterial_BioVar 	
						$returnVal[]=$_POST['class_anti'];//Taxon_Class	
						$returnVal[]=$_POST['order_anti'];//Taxon_Order
						$returnVal[]=$_POST['family_anti'];//Taxon_Family
						$returnVal[]=$_POST['genus_anti'];//Taxon_Genus
						$returnVal[]=$_POST['species_anti'];//Taxon_Species
						$returnVal[]=$_POST['sub_species_anti'];//Taxon_Sub_Species
						$returnVal[]=$_POST['taxon_pathovar'];//Taxon_Pathovar					
						$returnVal[]=$_POST['taxon_serotype'];//Taxon_Serotype
						$returnVal[]=$_POST['strain'];//Taxon_Strain
						$returnVal[]=$_POST['sub_strain'];
						$returnVal[]="0";//Created_By
						$returnVal[]="0";//Modified_By
						$returnVal[]=$_POST['tax_id_1'];//ID
						return $returnVal;
					} 
					function assemly_table_array(){
						// $a_ID = geneate_id($GLOBALS['con']);
						// $returnVal[]=$a_ID;//ID
						$returnVal[]="1";//Is_Reference
						$returnVal[]=$_POST['meta_id'];//Sample_Metadata_ID
						$returnVal[]=$_POST['source'] ;//source =>gen bank 							
						$returnVal[]=$_POST['genbank_id'];//Source_ID 
						$returnVal[]=$_POST['pubmed_id'];//Pubmed_IDs
						$returnVal[]=$_POST['bioproject_id'];//BioProject_ID 						
						$returnVal[]=$_POST['tax_id_1'];//Taxonomy_ID
						$returnVal[]=$_POST['plasmid'];//Plasmid_Name 
						$returnVal[]="0";//Created_By
						$returnVal[]="0";//Modified_By
						$returnVal[]=$_POST['asmbly_id'];//ID
						return $returnVal;
					}
					function antibiogram_table_arry(){
						// $cID = geneate_id($GLOBALS['con']);
						$returnVal[]=$_POST['antibiotic'];// Antibiotic
						$returnVal[]=$_POST['drug_symbol'];// Drug_Symbol
						$returnVal[]=$_POST['laboratory_typing_method'];// Laboratory_Typing_Method
						$returnVal[]=$_POST['laboratory_typing_method_version_reagent'];// Laboratory_Typing_Method_Version_or_Reagent
						$returnVal[]=$_POST['laboratory_typing_platform'];// Laboratory_Typing_Platform
						$returnVal[]=$_POST['measurement'];// Measurement
						$returnVal[]=$_POST['measurement_sign'];// Measurement_Sign
						$returnVal[]=$_POST['measurement_unit'];// Measurement_Units
						$returnVal[]=$_POST['resistance_phenotype'];// Resistance_Phenotype
						$returnVal[]=$_POST['testing_standard'];// Testing_Standard
						$returnVal[]=$_POST['vendor'];// Vendor
						$returnVal[]=$_POST['anti_id'];// ID 
						return $returnVal;
					}	
					function threat_table_arry(){
						// $cID = geneate_id($GLOBALS['con']);
						$returnVal[]=$_POST['treatlevel'];//level 
						$returnVal[]=$_POST['tl_id'];// ID 
						// var_dump($_POST['tl_id']);
						return $returnVal;
					}

					/* return cloumn fields for a table*/
					function getting_colums_from_a_table($con, $table_name){
						$temp='';
						$statement =$con->prepare("SHOW COLUMNS FROM ".$table_name);
				       	$success = $statement->execute();
						foreach(array_values($statement->fetchAll()) as $i){		
							$temp = $temp.",".$i["Field"];
						}
						// Create Date and Modified date are generated by the SQL..
						$temp= str_replace(",Created_Date,Modified_Date","",$temp) ;
						$temp[0]='';
						$rt = explode(',',$temp);
						$temp = array_shift($rt);
						
						echo"<br>";
						return $rt;
					}

			        ?>
			</div>
		</div>
	</div>
</section>	
<?php include 'includes/footer.php';?>

