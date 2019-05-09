<?php include 'includes/header.php';?>
<?php require_once('dbconfig.php');
session_start();
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
			        // ADD Check here &&isset($_SESSION['userID'])
					if(isset($_POST['id_id'])&&isset($_SESSION['userID'])){
						  echo '<h2 align="center">Data has been submitted into the ARMdb for admin review. <br><br>Once admin approve the data, it will be visible in AMRdb</h2><br><h5 align="center">Thank you for update new information</h5>';
				       // var_dump($_POST);
				        $mydb=  new Database();
				       	$con = $mydb->dbConnection();
						$GLOBALS['con']=$con;
				        update_all_tables($con);

					}else{
						echo' <h2 align="center">Error: Please Go back to submit form</h2><br>
							 <h2 align="center">Please login as user and submit it again</h2>';
					}
					/* return array which stores values for each tables 
					Tables: Identity, classification, Identity_Squence, Variants, Assemly, Sample_Metadata, Identity_assembly, Taxonomy
					*/
					function update_all_tables($con){
						//**********
						// reverser the order
						//**********
						update_identity_assembly_table($con);
						update_assemly_table($con);
						//**********
						//delete Antibiogram then add the updated antibiogram 
						//**********
						delete_antibiogram_table($con);
						delete_variant_table($con);
						delete_class_table($con);
						// die;
						//**********
						// insert and update 
						//**********
						insert_antibiogram_table($con);
						update_metadata_table($con);
						update_threat_table($con);
						update_tax_table($con);
						uodate_identity_sequence_table($con);



						//**********
						// update_variant_table($con);
						// delete variant then classification then reinsert classification and variant with the new record 
						//*********
						$classification_table_val=insert_class_table($con);
						if(isset($_POST['drug'])){
							for($i=0;$i<count($_POST['drug']);$i++){
								$temp_class_ID[]=$classification_table_val[$i*9];
							}
							insert_varant_table($con,$temp_class_ID);
						}
						update_Identity_table($con);
						// update_class_table($con);
						// update_variant_table($con);
						// update_antibiogram_table($con);

					}

					function insert_varant_table($con,$class_ids){
						$varian_t=variant_table_array($class_ids);

						 $t_col = getting_colums_from_a_table($con,'Variants');
						 if($varian_t!=''){
								$loopMe=insert_variants_helper(0,$con,$varian_t,$t_col);
								 // echo 'hey '.sizeof($varian_t).' V : '.$loopMe;
								while ($loopMe<sizeof($varian_t)) {
									$loopMe=insert_variants_helper($loopMe,$con,$varian_t,$t_col);
								}	
						 }
					}
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
								 die();
								}
							return $i;
					}
					function variant_table_array($cID){

						// first parse all is active values 
						
						// var_dump($_POST['v_active_count']);
						// first split the varibles then take the first as key and second as value eg 2_5  to 2=>5 2 is classification 2. 5 is the fifth varible 
						$v_split_ar =explode('_', $_POST['v_active_count']);
						// var_dump($_POST['v_active_count']);
						if(isset($v_split_ar[1])){
							//echo "string";
							$v_active_total=$v_split_ar[1];
						
							 for($i=1;$i<=$_POST['c_active_count'];$i++){
							// 	if(isset($_POST["cIsActive_".$i])){
							// 		$c_act_arrty[]=$_POST["cIsActive_".$i];
							// 	}
								 for ($q=1; $q<=$v_active_total ; $q++) { 
								 	$a_str="vIsActive_".$i."_$q";
								 	if(isset($_POST[$a_str])){
								 		// echo "<br>$a_str<br>";
								 		$v_active_ar[]=$_POST[$a_str];
								 	}
								 }
							 }
						}

						 // var_dump($v_active_ar);
						
						$temp=0;
						foreach ($_POST['drug'] as $i => $xx) {
							// echo "here is : ".$i ;
							$q=0;
							foreach ($_POST['snp'][$i+1] as $key => $value) {
								if($_POST['snp'][$i+1][$key]!=''&&$_POST['v_plasmid'][$i+1][$key]!=''){
									// echo $_POST['snp'][$i+1][$key]."<br>".$_POST['v_plasmid'][$i+1][$key]."<br>";

									$vID = geneate_id($GLOBALS['con']);
									$returnVal[]=$vID;//id
									$returnVal[]=$_POST['snp'][$i+1][$key];//SNP	
									$returnVal[]=$_POST['v_plasmid'][$i+1][$key];//PubMed_IDs  Plasmid
									$returnVal[]=$_POST['id_seq_id'];//id from id_seq table 
									$returnVal[]=$cID[$temp];//id from classification table
									$returnVal[]=isset($v_active_ar[$q])?$v_active_ar[$q]:"1";//is_activate
									$returnVal[]=$_SESSION['userID'];//created_By
									$returnVal[]=$_SESSION['userID'];//ModifiedBy
									$txt[]=isset($v_active_ar[$q])?$v_active_ar[$q]:"1";
									$q++;

								}
							}
							$temp++;
						}


 						// var_dump($txt);

						// die;
						// var_dump($active_ar);

						// die;
						if(!isset($returnVal)){
							return "";
						}
						// 9 entries
						return $returnVal; 
					}



					function insert_antibiogram_table($con){
						// echo "Inserting Anti <br>";
						$t_col = getting_colums_from_a_table($con,'Antibiogram');
						$antibiog_t=init_antibiogram_table_arry($con);
						
						$loopMe=insert_anti_helper(0,$con,$antibiog_t,$t_col);

						 // echo 'hey '.sizeof($varian_t).' V : '.$loopMe;
						while ($loopMe<count($antibiog_t)) {
							$loopMe=insert_anti_helper($loopMe,$con,$antibiog_t,$t_col);
						}
						// echo "stoped at :".$loopMe;
					}
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
								 die();
								}
							return $i;
						}


					function insert_class_table($con){
						if(isset($_POST['drug'])){
							$class_t=init_classification_table_array();
							 $t_col = getting_colums_from_a_table($con,'Classification');
							 // var_dump($class_t);
							 // die();
							 $loopMe=insert_classifictaion_helper(0,$con,$class_t,$t_col);
							while ($loopMe<sizeof($class_t)) {
								$loopMe=insert_classifictaion_helper($loopMe,$con,$class_t,$t_col);
							}
						return $class_t;
						}
						return '';
					}
					function insert_classifictaion_helper($start_p,$con,$class_t,$t_col){
							$sth = $con->prepare('INSERT INTO Classification(ID,Drug,Drug_Class,Drug_Family,Mechanism_of_Action,Identity_ID,Is_Active,Created_By,Modified_By) VALUES( ?, ?, ? , ? , ? , ? , ? , ? , ? );');
							$i=$start_p;
							for ($q=0;$q<9 ; $i++, $q++) {
								$in_val = "\"".$class_t[$i]."\"";
								$sth->bindValue($q+1, $class_t[$i], PDO::PARAM_STR);
								// echo $t_col[$q]." : ".$class_t[$i]."<br>";
							}
							try {

								$sth->execute();



							} catch (Exception $e) {
								echo "<br>  <b>Error ->Classification </b> <br> : ".$e;
								die();
							}
							return $i;
					}

					function delete_class_table($con){
						$query='DELETE FROM Classification WHERE ID=?';
						if(isset($_POST['ca_id'])){
							for($i=0;$i<count($_POST['ca_id']);$i++){
								execute_delete($con,$query,$_POST['ca_id'][$i]);
								// echo "Deleting; ".$_POST['ca_id'][$i];
							}
							
						}
					}
					function delete_variant_table($con){
						$query='DELETE FROM Variants WHERE ID=?';

						if(isset($_POST['va_id'])){
							for($i=0;$i<count($_POST['va_id']);$i++){
								execute_delete($con,$query,$_POST['va_id'][$i]);
							}
						}
					}
					function delete_antibiogram_table($con){
						$query='DELETE FROM Antibiogram WHERE ID=?';
						if(isset($_POST['anti_id'])){
							for($i=0;$i<count($_POST['anti_id']);$i++){
								execute_delete($con,$query,$_POST['anti_id'][$i]);
								// echo "Deleting: ".$_POST['anti_id'][$i];
							}
						}
						// die();
					}
					function update_identity_assembly_table($con){
						$id_t=init_identity_assembly_table_array();
						$t_col = get_table_colums($con,'Identity_Assembly');
						$update_string= prepare_update_statement('Identity_Assembly',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Identity_Assembly',0);

					}
					function update_assemly_table($con){
						$id_t=init_assemly_table_array();
						$t_col = get_table_colums($con,'Assemly');
						$update_string= prepare_update_statement('Assemly',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Assemly',0);

					}
				
					function update_metadata_table($con){
						$id_t=init_metadata_table_array();
						$t_col = get_table_colums($con,'Sample_Metadata');
						$update_string= prepare_update_statement('Sample_Metadata',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Sample_Metadata',0);
					}
					function update_threat_table($con){
						if(isset($_POST['treatlevel'])){
							$id_t=init_threat_table_array();
							$t_col = get_table_colums($con,'Threat_Level');
							$update_string= prepare_update_statement('Threat_Level',$t_col);
							execute_update($con,$t_col,$update_string,$id_t,'Threat_Level',0);
						}
					}
					function update_tax_table($con){
						$id_t=init_taxonomy_table_array();
						$t_col = get_table_colums($con,'Taxonomy');
						$update_string= prepare_update_statement('Taxonomy',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Taxonomy',0);
					}

					function uodate_identity_sequence_table($con){
						$id_t=identity_Sequence_table_array();
						$t_col = get_table_colums($con,'Identity_Sequence');
						$update_string= prepare_update_statement('Identity_Sequence',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Identity_Sequence',0);
					}

					function update_Identity_table($con){
						$id_t=init_identity_table_array();
						$t_col = get_table_colums($con,'Identity');
						$update_string= prepare_update_statement('Identity',$t_col);
						// echo $update_string;
						execute_update($con,$t_col,$update_string,$id_t,'Identity',0);
					}
					function prepare_update_statement($ptablename,$t_col){
						$set_string='UPDATE '.$ptablename.' SET ';
						foreach ($t_col as $fd) {
							$set_string.=$fd.'=?, ';
						}
						$set_string=substr(trim($set_string), 0, -1);
						$set_string.=' WHERE ID=?';
						return $set_string;
					}
					function execute_update($con,$t_col,$update_string,$id_t,$msg,$position){
						$sth = $con->prepare($update_string);
						$i=$position;
						for ($q=0; $q<=sizeof($id_t) ; $i++,$q++) {
							// start at 1 for bind varible
							$sth->bindValue($q+1, $id_t[$i], PDO::PARAM_STR);
								// echo '<br>'.$t_col[$q]." =>: ". $id_t[$i];
							if($q==sizeof($t_col)){
								break;
							}
						}
						try {
							$sth->execute();
							// echo"<hr>update<hr>";
						} catch (Exception $e) {
							echo "<br>  <b>Error :</b><br>".$e;
							die();
						}
						return $i;
					}
					function execute_delete($con,$delete_string,$delete_id){
						$sth=$con->prepare($delete_string);
						$sth->bindValue(1,$delete_id, PDO::PARAM_STR);
						// echo $delete_string." ?='$delete_id' <br>";	

						try {
							$sth->execute();
							// echo"<hr>update<hr>";
						} catch (Exception $e) {
							echo "<br>  <b>Error Deleting:</b><br>".$e;
							die();
						}
					}

					function init_identity_assembly_table_array(){
						$returnVal[]=$_POST['mol_type'];
						$returnVal[]=$_POST['id_seq_id'];
						$returnVal[]=$_POST['asmbly_id'];
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['idaa_id'];
						return $returnVal;
					}
					function init_assemly_table_array(){
						$returnVal[]="1";//Is_Reference
						$returnVal[]=$_POST['meta_id'];//Sample_Metadata_ID
						$returnVal[]=$_POST['source'] ;//source =>gen bank 							
						$returnVal[]=$_POST['genbank_id'];//Source_ID 
						$returnVal[]=$_POST['pubmed_id'];//Pubmed_IDs
						$returnVal[]=$_POST['bioproject_id'];//BioProject_ID 						
						$returnVal[]=$_POST['tax_id_1'];//Taxonomy_ID
						$returnVal[]=$_POST['plasm_name'];//Plasmid_Name 
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['asmbly_id'];//ID
						return $returnVal;
					}
					function init_antibiogram_table_arry($con){

					for ($i=0; $i <count($_POST['antibiotic']) ; $i++) { 
							# code...
							$cID = geneate_id($con);
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
							$returnVal[]=$_POST['meta_id'];// Sample_Metadata_ID
							$returnVal[]=$_SESSION['userID'];//created_By
							$returnVal[]=$_SESSION['userID'];//ModifiedBy
						}
						if(!isset($returnVal)){
							return '';
						}
						return $returnVal;
					}	
					function init_metadata_table_array(){
						$returnVal[]=$_POST['source'] ;//source =>gen bank 	
						$returnVal[]=$_POST['genbank_id'] ;//SourceID => gen bank 
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
						
						$returnVal[]=$date_string;// date 
						$returnVal[]=$_POST["specimen_collection_date_YY"];// year
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
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['meta_id'];//id 
						return $returnVal;
					}
					function init_threat_table_array(){
						$returnVal[]=$_POST['treatlevel'];//level 
						$returnVal[]=$_POST['tax_id_1'];// TaxID 
						$returnVal[]=$_POST['id_id'];//ID
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['tl_id'];//threat level ID
						return $returnVal;
					}
					function init_taxonomy_table_array(){
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
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['tax_id_1'];//ID
						return $returnVal;
					} 
		
					function identity_Sequence_table_array(){
						$returnVal[]=$_POST['end5'];
						$returnVal[]=$_POST['end3'];
						$returnVal[]=$_POST['nucleotide_sequence'];//NA Sequence
						$returnVal[]=$_POST['protein_squence'];//AA sequence
						$returnVal[]=$_POST['feat_type'];
						$returnVal[]=$_POST['id_id'];// id from Identity Table 
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['id_seq_id'];
						return $returnVal;
					}
					function init_classification_table_array(){
					//use a for loop to get all the information 
						// var_dump($_POST['drug']);
					//first get the correspond active;


						// var_dump($_POST['c_active_count']);

						for($i=1;$i<=$_POST['c_active_count'];$i++){
							if(isset($_POST["cIsActive_".$i])){
								$c_act_arrty[]=$_POST["cIsActive_".$i];
							}
						}
						// var_dump($c_act_arrty);

						for ($i=0; $i <count($_POST['drug']); $i++) {

							$cID = geneate_id($GLOBALS['con']);
							$returnVal[]=$cID;//id
							$returnVal[]=$_POST['drug'][$i];//drug_name 
							$returnVal[]=$_POST['drug_class'][$i];//drug_class
							$returnVal[]=$_POST['drug_family'][$i];//drug_family
							$returnVal[]=$_POST['mechanism_of_action'][$i];//Mechanism of Action
							$returnVal[]=$_POST['identity'];//id from identity table
							$returnVal[]=isset($c_act_arrty[$i])? $c_act_arrty[$i]:"1";// is_active 0 or1   default is 1  
							$returnVal[]=$_SESSION['userID'];//created_By
							$returnVal[]=$_SESSION['userID'];//ModifiedBy
							//vIsActive_6_11
						
							// echo "<br>rt***";
							// var_dump($returnVal);
							// echo "<br>";
							// $active_ar[]=$_POST["cIsActive_".$q];
						}
						// var_dump($_POST);
						// var_dump($active_ar);
						// 10 entries
						
						return $returnVal;
					}
					function init_identity_table_array(){
						$returnVal[]=$_POST['gene_symbol'];
						$returnVal[]=$_POST['gene_alter_names'];
						$returnVal[]=$_POST['gene_family'];
						$returnVal[]=$_POST['gene_class'];
						$returnVal[]=$_POST['allele'];
						$returnVal[]=$_POST['ec_number'];//EC_Number
						$returnVal[]=$_POST['parent_allele_family'];
						$returnVal[]=$_POST['parent_allele'];
						$returnVal[]=$_POST['source'];//Source => GenBank 
						$returnVal[]=$_POST['genbank_id'];//SourceID =>gen Bank ID 
						$returnVal[]=$_POST['protein_id'];
						$returnVal[]=$_POST['protein_name'];//Protein_name
						$returnVal[]=$_POST['protein_alter_names'];
						$returnVal[]=$_POST['pubmed_id'];
						$returnVal[]=$_POST['hmm'];
						$returnVal[]=$_POST['is_active_'];// is_active 0 or1   default is 1  
						$returnVal[]=$_POST['Stat'];//Status
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['id_id'];
						// var_dump($returnVal);
						
						return $returnVal;
					}
					/* return cloumn fields for a table*/
					function get_table_colums($con, $table_name){
						$temp='';
						$statement =$con->prepare("SHOW COLUMNS FROM ".$table_name);
				       	$success = $statement->execute();
						foreach(array_values($statement->fetchAll()) as $i){		
							$temp = $temp.",".$i["Field"];
						}
						// Create Date and Modified date are generated by the SQL..
						$temp= str_replace(",Created_Date","",$temp) ;
						$temp= str_replace(",Created_By","",$temp) ;
						$temp[0]='';
						$rt = explode(',',$temp);
						$temp = array_shift($rt);
						return $rt;
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
					/* this function generate a unique id for a table.*/
					function geneate_id($con){
						$statement = $con->prepare("select nextval('CDC_Seq')");
						$success = $statement->execute();
						$ids = $statement->fetch();
						return $ids[0];
					}
					// function update_class_table($con){
					// 	$id_t=init_classification_table_array();
					// 	$t_col=get_table_colums($con,'Classification');
					// 	$update_string= prepare_update_statement('Classification',$t_col);
					// 	if(!isset($id_t)){
					// 		return;
					// 	}
					// 	$newstart=execute_update($con,$t_col,$update_string,$id_t,'Classification',0);
					// 	while ($newstart<sizeof($id_t)-1) {
					// 		// echo "<b>doing it</b>";
					// 		$newstart=execute_update($con,$t_col,$update_string,$id_t,'Classification',$newstart+1);
					// 	}
					// 	// echo '<br> posotion: '.$newstart.' end pos'.sizeof($id_t);
					// }
					// function update_variant_table($con){
					// 	$id_t=init_variant_table_array();
					// 	$t_col=get_table_colums($con,'Variants');
					// 	$update_string= prepare_update_statement('Variants',$t_col);
					// 	if(!isset($id_t)){
					// 		return;
					// 	}
					// 	$newstart=execute_update($con,$t_col,$update_string,$id_t,'Variants',0);
					// 	while ($newstart<sizeof($id_t)-1) {
					// 		$newstart=execute_update($con,$t_col,$update_string,$id_t,'Variants',$newstart+1);
					// 	}
					// }
						// function update_antibiogram_table($con){
					// 	$id_t=init_antibiogram_table_arry();
					// 	$t_col=get_table_colums($con,'Antibiogram');
					// 	$update_string= prepare_update_statement('Antibiogram',$t_col);
					// 	if(!isset($id_t)){
					// 		return;
					// 	}
					// 	$newstart=execute_update($con,$t_col,$update_string,$id_t,'Antibiogram',0);
					// 	while ($newstart<sizeof($id_t)-1) {
					// 		// echo "<b>doing it</b>";
					// 		$newstart=execute_update($con,$t_col,$update_string,$id_t,'Antibiogram',$newstart+1);
					// 	}
					// }
					echo "<meta  http-equiv='refresh' content='5;url=browse_admin.php' />";
			        ?>
			</div>
		</div>
	</div>
</section>	
<?php include 'includes/footer.php';?>

