<?php include 'includes/header.php';?>
<?php require_once('dbconfig.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
// var_dump($_POST['treatlevel']);
// die();
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

// 						var_dump(count($_POST['antibiotic']));
// die();

						  echo '<h2 align="center">Data has been submitted into the AMRdb for admin review. <br><br>Once admin approve the data, it will be visible in AMRdb</h2>';
				        $mydb=  new Database();
				       	$con = $mydb->dbConnection();
						$GLOBALS['con']=$con;
				        getting_form_values($con);


					}else{
						echo' <h2 align="center">Please Go back to submit form</h2>';
					}

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
					function getting_form_values($con){
						//Provide values for Identity table
						$id_table_val = identity_table_array();
						//Provide value for classification table
						$classification_table_val = classification_table_array();
						//Provide values for Identity_Sequence table
						$id_seq_table_val = identity_Sequence_table_array();
						//Provide values forVariants

						//loop thought classification_table_val to get ids
						for($i=0;$i<count($_POST['drug']);$i++){
							$temp_class_ID[]=$classification_table_val[$i*9];
						}

						$variant_table_val= variant_table_array($id_seq_table_val[0],$temp_class_ID);

						//Provide values for Sample_metadata table 
						$sample_meta_val= sample_metadata_table_array();
						//Provide valuesfor taxonomy table 
						$tax_table_val= taxonomy_table_array();
						//Provide valuesfor assemly table
						$assemnly_val = assemly_table_array($sample_meta_val[0],$tax_table_val[0]);
						//Provide valuesforidentityassembly table
						$ida_val= identity_assembly_table_array($id_seq_table_val[0],$assemnly_val[0]);
						$antibigram_val =antibiogram_table_arry($sample_meta_val[0]);
						$threat_val=threat_table_arry($tax_table_val[0],$id_table_val[0]);
						//inserting functions 
						
						insert_info_into_db($con, $id_table_val, $classification_table_val , $id_seq_table_val, $variant_table_val,$sample_meta_val,$tax_table_val,$assemnly_val,$ida_val,$antibigram_val,$threat_val);
						return "<br>Called values function" ;
					}

					function insert_info_into_db($con,$id_t, $class_t, $id_seq_t,$varian_t, $samp_t, $tax_t, $assem_t, $ida_assen_t,$antibiog_t,$threat_t){

						
						 $t_col = getting_colums_from_a_table($con,'Identity');
	
						$sth = $con->prepare('INSERT INTO Identity(ID,Gene_Symbol,Gene_Alternative_Names,Gene_Family,Gene_Class,Allele,EC_Number,Parent_Allele_Family,Parent_Allele,Source,Source_ID,Protein_ID,Protein_Name,Protein_Alternative_Names,Pubmed_IDs,HMM,Is_Active,Status,Created_By,Modified_By) VALUES( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? ,?,?,?, ? , ? , ? , ? , ?,?,? );');
						for ($i=0; $i<sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$id_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $id_t[$i], PDO::PARAM_STR);
							// echo $t_col[$i]." : ".$id_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							echo "<br>  <b>Error ->Identity :</b> <br> ";
						}
						//insert classification
						 $t_col = getting_colums_from_a_table($con,'Classification');
						 function insert_classifictaion_helper($start_p,$con,$class_t,$t_col){
							$sth = $con->prepare('INSERT INTO Classification(ID,Drug,Drug_Class,Drug_Family,Mechanism_of_Action,Identity_ID,Is_Active,Created_By,Modified_By) VALUES( ?, ? , ? , ? , ? , ? , ? , ? , ? );');
							$i=$start_p;
							for ($q=0;$q<9 ; $i++, $q++) {
								$in_val = "\"".$class_t[$i]."\"";
								$sth->bindValue($q+1, $class_t[$i], PDO::PARAM_STR);
								// echo $t_col[$q]." : ".$class_t[$i]."<br>";
							}
							try {
								$sth->execute();
							} catch (Exception $e) {
								echo "<br>  <b>Error ->Classification </b> <br> : ";
							}
							return $i;
						}
						 $loopMe=insert_classifictaion_helper(0,$con,$class_t,$t_col);

						 // echo 'hey '.sizeof($class_t).' V : '.$loopMe;
						while ($loopMe<sizeof($class_t)) {
							$loopMe=insert_classifictaion_helper($loopMe,$con,$class_t,$t_col);
						}			 

						 $t_col = getting_colums_from_a_table($con,'Identity_Sequence');

						$sth = $con->prepare('INSERT INTO Identity_Sequence(ID,End5,End3,NA_Sequence,AA_Sequence,Feat_Type,Identity_ID,Created_By,Modified_By) VALUES( ?, ? , ? , ? , ? , ? , ? , ? , ? );');
						for ($i=0; $i<sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$id_seq_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $id_seq_t[$i], PDO::PARAM_STR);
							// echo $t_col[$i]." : ".$id_seq_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
								echo "<br>  <b>Error ->Identity Sequence </b> <br> : ".$e;
						}


						// var_dump($varian_t);
						// die();

						 $t_col = getting_colums_from_a_table($con,'Variants');
						 function insert_variants_helper($start_p,$con,$varian_t,$t_col){
								$sth = $con->prepare('INSERT INTO Variants(ID,SNP,PubMed_IDs,Identity_Sequence_ID,Classification_ID,Is_Active,Created_By,Modified_By) VALUES( ? , ? , ? , ? , ? , ? , ? , ? );');
								$i=$start_p;
								for ($q=0; $q<8 ; $q++,$i++) {
									// echo $new_i;
									$sth->bindValue($q+1, $varian_t[$i], PDO::PARAM_STR);
									 // echo $t_col[$q]." : ".$varian_t[$i]."<br>";
								}
								try {

									$sth->execute();
								} catch (Exception $e) {
								 echo "<br>  <b>Error ->Variant </b> <br> : ".$e;
								}
							return $i;
						}
						// insert Variants
						
						 $t_col = getting_colums_from_a_table($con,'Variants');
						 if($varian_t!=''){
								$loopMe=insert_variants_helper(0,$con,$varian_t,$t_col);

								 // echo 'hey '.sizeof($varian_t).' V : '.$loopMe;
								while ($loopMe<sizeof($varian_t)) {
									$loopMe=insert_variants_helper($loopMe,$con,$varian_t,$t_col);
								}	
						 }
						 $t_col = getting_colums_from_a_table($con,'Sample_Metadata');
	
						$sth = $con->prepare('INSERT INTO Sample_Metadata(ID,Source,Source_ID,Isolation_site,Serotyping_Method,Source_Common_Name,Specimen_Collection_Date,Specimen_Collection_Year,Specimen_Collection_Location_Country,Specimen_Collection_Location,Specimen_Collection_Location_Latitude,Specimen_Collection_Location_Longitude,Specimen_Source_Age,Specimen_Source_Developmental_Stage,Specimen_Source_Disease,Specimen_Source_Gender,Health_Status,Treatment,Specimen_Type,Symptom,Host,Created_By,Modified_By) VALUES( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? );');
						for ($i=0; $i<sizeof($t_col) ; $i++) {
							// $new_i=$i+1; 
							$in_val = "\"".$samp_t[$i]."\"";
							// echo $new_i;
							$sth->bindValue($i+1, $samp_t[$i], PDO::PARAM_STR);
							// echo $t_col[$i]." : ".$samp_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {

							echo "<br>  <b>Error ->Sample Metadata </b> <br> : ".$e;
						}

						 $t_col = getting_colums_from_a_table($con,'Taxonomy');

						
						$sth = $con->prepare('INSERT INTO Taxonomy(ID,Taxon_ID,Taxon_Kingdom,Taxon_Phylum,Taxon_Bacterial_BioVar,Taxon_Class,Taxon_Order,Taxon_Family,Taxon_Genus,Taxon_Species,Taxon_Sub_Species,Taxon_Pathovar,Taxon_Serotype,Taxon_Strain,Taxon_Sub_Strain,Created_By,Modified_By) VALUES(?, ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? );');
						for ($i=0; $i<sizeof($t_col) ; $i++) {
							$in_val = "\"".$tax_t[$i]."\"";
							$sth->bindValue($i+1, $tax_t[$i], PDO::PARAM_STR);
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							echo "<br>  <b>Error ->Taxonomy </b> <br> : ".$e;
						}
						 $t_col = getting_colums_from_a_table($con,'Assemly');

						
						$sth = $con->prepare('INSERT INTO Assemly(ID,Is_Reference,Sample_Metadata_ID,Source,Source_ID,PubMed_IDs,BioProject_ID,Taxonomy_ID,Plasmid_Name,Created_By,Modified_By) VALUES( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? );');
						for ($i=0; $i<sizeof($t_col) ; $i++) {
							$in_val = "\"".$assem_t[$i]."\"";
							$sth->bindValue($i+1, $assem_t[$i], PDO::PARAM_STR);
							// echo $t_col[$i]." : ".$assem_t[$i]."<br>";
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							echo "<br>  <b>Error ->Assemly </b> <br> : ".$e;
						}


						 $t_col = getting_colums_from_a_table($con,'Identity_Assembly');

						$sth = $con->prepare('INSERT INTO Identity_Assembly(ID,Mol_Type,Identity_Sequence_ID,Assemly_ID,Created_By,Modified_By) VALUES(?, ? , ? , ? , ? , ? );');
						for ($i=0; $i<sizeof($t_col) ; $i++) {
							$in_val = "\"".$ida_assen_t[$i]."\"";
							$sth->bindValue($i+1, $ida_assen_t[$i], PDO::PARAM_STR);
						}
						try {
							$sth->execute();
						} catch (Exception $e) {
							echo "<br>  <b>Error ->Identity Assembly </b> <br> : ".$e;
						}



						// var_dump($antibiog_t);
					

						// inserting Antibiogram table 
						$t_col = getting_colums_from_a_table($con,'Antibiogram');

						function insert_anti_helper($start_p,$con,$antibiog_t,$t_col){
							$sth = $con->prepare('INSERT INTO Antibiogram(ID,Antibiotic,Drug_Symbol,Laboratory_Typing_Method,Laboratory_Typing_Method_Version_or_Reagent,Laboratory_Typing_Platform, Measurement, Measurement_Sign, Measurement_Units,Resistance_Phenotype,Testing_Standard,Vendor,Sample_Metadata_ID, Created_By,Modified_By) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
								$i=$start_p;
								for ($q=0; $q<15 ; $q++,$i++) {
									// echo $new_i;
									$sth->bindValue($q+1, $antibiog_t[$i], PDO::PARAM_STR);
									 // echo $t_col[$q]." : ".$antibiog_t[$i]."<br>";
								}
								try {

									$sth->execute();
								} catch (Exception $e) {
								 echo "<br>  <b>Error ->Antibiogram </b> <br> : ".$e;
								}
							return $i;
						}
						$loopMe=insert_anti_helper(0,$con,$antibiog_t,$t_col);

						 // echo 'hey '.sizeof($varian_t).' V : '.$loopMe;
						while ($loopMe<sizeof($antibiog_t)) {
							$loopMe=insert_anti_helper($loopMe,$con,$antibiog_t,$t_col);
						}	

						//inserting into Threat Level table

						if(isset($_POST['treatlevel'])){ 
							$t_col = getting_colums_from_a_table($con,'Threat_Level');
							$sth = $con->prepare('INSERT INTO Threat_Level(ID,Level,Taxonomy_ID,Identity_ID, Created_By,Modified_By) VALUES(?,?,?,?,?,?);');
							// echo sizeof($t_col);
							for ($i=0; $i<sizeof($t_col) ; $i++) {
								// $new_i=$i+1; 
								$in_val = "\"".$threat_t[$i]."\"";
								// echo $new_i;
								$sth->bindValue($i+1, $threat_t[$i], PDO::PARAM_STR);
								// echo $t_col[$i]." : ".$threat_t[$i]."<br>";
							}
							try {
								$sth->execute();
							} catch (Exception $e) {
								echo "<br>  <b>Error ->Threat </b> <br> : ".$e;
								
							}
						}
					}
					function threat_table_arry($taxid,$idid){
						$tID = geneate_id($GLOBALS['con']);
						$returnVal[]=$tID;// ID 
						$returnVal[]=$_POST['treatlevel'];;
						$returnVal[]=$taxid;//taxonomy_id
						$returnVal[]=$idid;// Identity_ID
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy

						return $returnVal;
					}

					function classification_table_array() {

						//use a for loop to get all the information 
						// var_dump($_POST['drug']);
						for ($i=0; $i <count($_POST['drug']); $i++) { 
							$cID = geneate_id($GLOBALS['con']);
							$returnVal[]=$cID;//id
							$returnVal[]=$_POST['drug'][$i];//drug_name 
							$returnVal[]=$_POST['drug_class'][$i];//drug_class
							$returnVal[]=$_POST['drug_family'][$i];//drug_family
							// $returnVal[]=$_POST['sub_drug_class'][$i];//drug_sub_class
							$returnVal[]=$_POST['mechanism_of_action'][$i];
							$returnVal[]=$_POST['identity'];//id from identity table
							$returnVal[]="1";// is_active 0 or1   default is 1  
							$returnVal[]=$_SESSION['userID'];//created_By
							$returnVal[]=$_SESSION['userID'];//ModifiedBy
						}
						// 9 entries
						
						return $returnVal;
					}
					function variant_table_array($idsID,$cID){
						//use a for loop to get all the information 
						// var_dump(count($_POST['drug']));
						// echo "<br>";
						$temp=0;
						foreach ($_POST['drug'] as $i => $xx) {
							// echo "here is : ".$i ;
							foreach ($_POST['snp'][$i+1] as $key => $value) {
								if($_POST['snp'][$i+1][$key]!=''&&$_POST['v_plasmid'][$i+1][$key]!=''){
									  // echo $_POST['snp'][$i+1][$key]."<br>".$_POST['v_plasmid'][$i+1][$key]."<br>";
									$vID = geneate_id($GLOBALS['con']);
									$returnVal[]=$vID;//id
									$returnVal[]=$_POST['snp'][$i+1][$key];//SNP	
									$returnVal[]=$_POST['v_plasmid'][$i+1][$key];//PubMed_IDs  Plasmid
									$returnVal[]=$idsID;//id from id_seq table 
									$returnVal[]=$cID[$temp];//id from classification table
									$returnVal[]=1;//is_activate
									$returnVal[]=$_SESSION['userID'];//created_By
									$returnVal[]=$_SESSION['userID'];//ModifiedBy
								}
							}
							$temp++;
						}

						if(!isset($returnVal)){
							return "";
						}
						// 9 entries
						// die();
						return $returnVal; 
					}
					function antibiogram_table_arry($sid){

						for ($i=0; $i <count($_POST['antibiotic']) ; $i++) { 
							# code...
							$cID = geneate_id($GLOBALS['con']);
							$returnVal[]=$cID;// ID 
							$returnVal[]=$_POST['antibiotic'][$i];// Antibiotic
							$returnVal[]=$_POST['drug_symbol'][$i];// Drug_Symbol
							$returnVal[]=$_POST['laboratory_typing_method'][$i];// Laboratory_Typing_Method
							$returnVal[]=$_POST['laboratory_typing_method_version_reagent'][$i];// Laboratory_Typing_Method_Version_or_Reagent
							$returnVal[]=$_POST['laboratory_typing_platform'][$i];// Laboratory_Typing_Platform
							$returnVal[]=$_POST['measurement'][$i];// Measurement
							$returnVal[]=$_POST['measurement_sign'][$i];// Measurement_Sign
							$returnVal[]=$_POST['measurement_unit'][$i];// Measurement_Units
							$returnVal[]=$_POST['resistance_phenotype'][$i];// Resistance_Phenotype
							$returnVal[]=$_POST['testing_standard'][$i];// Testing_Standard
							$returnVal[]=$_POST['vendor'][$i];// Vendor
							$returnVal[]=$sid;// Sample_Metadata_ID
							$returnVal[]=$_SESSION['userID'];//created_By
							$returnVal[]=$_SESSION['userID'];//ModifiedBy
						}
						return $returnVal;
					}

					function identity_Sequence_table_array(){
						$idsID= geneate_id($GLOBALS['con']);
						$returnVal[]=$idsID;//id
						$returnVal[]=$_POST['end5'];
						$returnVal[]=$_POST['end3'];
						$returnVal[]=$_POST['nucleotide_sequence'];//NA Sequence
						$returnVal[]=$_POST['protein_squence'];//AA sequence
						$returnVal[]= $_POST['feat_type'];//Feat_Type
						$returnVal[]=$_POST['identity'];// id from Identity Table 
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						return $returnVal;
					}
					function identity_table_array(){
						$returnVal[]=$_POST['identity'];
						$returnVal[]=$_POST['gene_symbol'];
						$returnVal[]=$_POST['gene_alter_names'];
						$returnVal[]=$_POST['gene_family'];
						$returnVal[]=$_POST['gene_class'];
						$returnVal[]=$_POST['allele'];
						$returnVal[]=$_POST['ec_number'];//EC_Number
						$returnVal[]=$_POST['parent_allele_family'];
						$returnVal[]=$_POST['parent_allele'];
						$returnVal[]=$_POST['source'];//Source => GenBank 
						$returnVal[]=$_POST['source_id'];//SourceID =>gen Bank ID 
						$returnVal[]=$_POST['protein_id'];
						$returnVal[]=$_POST['protein_name'];//Protein_name
						$returnVal[]=$_POST['protein_alter_names'];
						$returnVal[]=$_POST['plasmid'];
						$returnVal[]=$_POST['hmm'];
						$returnVal[]="1";// is_active 0 or1   default is 1  
						$returnVal[]=$_POST['Stat'];//Status
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						
						return $returnVal;
					}



					function sample_metadata_table_array(){
						$s_m_ID = geneate_id($GLOBALS['con']);
						$returnVal[]=$s_m_ID;
						$returnVal[]=$_POST['biosample'] ;//source =>gen bank 	
						$returnVal[]=$_POST['biosample_ID'] ;//SourceID => gen bank 
						$returnVal[]=$_POST['isolation_site'] ;
						$returnVal[]=$_POST['serotyping_method'];//Serotyping_method 
						$returnVal[]=$_POST['source_common_name'];//Source_common_name
						$date_string=""; 

						if(isset($_POST['specimen_collection_date_DD']) &&(!trim($_POST['specimen_collection_date_DD'])=="")){
							$date_string.=$_POST['specimen_collection_date_DD']."-";
						}
						if(isset($_POST['specimen_collection_date_MON'])&&(!trim($_POST['specimen_collection_date_MON'])=="")){
							$date_string.=$_POST['specimen_collection_date_MON']."-";
						}
						$date_string.=$_POST["specimen_collection_date_YY"];
						$returnVal[]=$date_string;// data 
						$returnVal[]=isset($_POST["specimen_collection_date_YY"])?$_POST["specimen_collection_date_YY"]:"";// year
						$returnVal[]=$_POST['specimen_location_country'];
						$returnVal[]=$_POST['specimen_location'];		// specimen_location specimen location 	
						$returnVal[]=$_POST['specimen_location_lattitude'];
						$returnVal[]=$_POST['specimen_location_longitude'];
						$returnVal[]=$_POST['specimen_source_age'];
						$returnVal[]=$_POST['specimen_dev_stage'];//specimen_development stage 
						$returnVal[]=$_POST['specimen_source_disease'];
						$returnVal[]=$_POST['specimen_source_gender'];
						$returnVal[]=$_POST['specimen_health_status'];//specimen_health_status Health status 
						$returnVal[]=$_POST['specimen_treatment'];// Treatment 	
						$returnVal[]=$_POST['speciment_type'];//Speciment_type 
						$returnVal[]=$_POST['speciment_symptom'];// symptom
						$returnVal[]=$_POST['speciment_Host'];//Host 
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
					// var_dump($date_string);
					// 	die();
						return $returnVal;
					}

					function taxonomy_table_array(){
						$t_ID = geneate_id($GLOBALS['con']);
						$returnVal[]=$t_ID;//ID,
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
						$returnVal[]=$_POST['sub_strain'];// 											
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						return $returnVal;
					} 
					function assemly_table_array($sid, $tid){
						$a_ID = geneate_id($GLOBALS['con']);
						$returnVal[]=$a_ID;//ID
						$returnVal[]="1";//Is_Reference
						$returnVal[]=$sid;//Sample_Metadata_ID
						$returnVal[]="";//Source => GenBank 
						$returnVal[]="";//SourceID =>gen Bank ID 
						$returnVal[]="";//Pubmed_IDs
						$returnVal[]=$_POST['bioproject_id'];//BioProject_ID 						
						$returnVal[]=$tid;//Taxonomy_ID
						$returnVal[]=$_POST['plasm_name'];//Plasmid_Name  //plasmid
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						return $returnVal;
					}
					function identity_assembly_table_array($isid,$aid){
						$ia_ID = geneate_id($GLOBALS['con']);
						$returnVal[]=$ia_ID;//ID
						$returnVal[]=$_POST['mol_type'];//Mol_Type   
						$returnVal[]=$isid;//Identity_Sequence_ID
						$returnVal[]=$aid;//Assemly_ID
						$returnVal[]=$_SESSION['userID'];//created_By
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						return $returnVal;
					}
					/* this function generate a unique id for a table.*/
					function geneate_id($con){
						$statement = $con->prepare("select nextval('CDC_Seq')");
						$success = $statement->execute();
						$ids = $statement->fetch();
						return $ids[0];
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
						return explode(',',$temp);
					}
			        ?>
			</div>
		</div>
	</div>
</section>	
<?php include 'includes/footer.php';?>

