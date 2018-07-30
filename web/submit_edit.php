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
					if(isset($_POST['method'])&&isset($_SESSION['userID'])){
						  echo '<h2 align="center">Data has been submitted into the ARMdb for admin review. <br><br>Once admin approve the data, it will be visible in AMRdb</h2><br><h5 align="center">Thank you for update new information</h5>';
				       // var_dump($_POST);
				        $mydb=  new Database();
				       	$con = $mydb->dbConnection();
						$GLOBALS['con']=$con;
				        update_all_tables($con);

					}else{
						echo' <h2 align="center">Please Go back to submit form</h2><br>
							 <h2 align="center">Please log in as user</h2>';
					}
					/* return array which stores values for each tables 
					Tables: Identity, classification, Identity_Squence, Variants, Assemly, Sample_Metadata, Identity_assembly, Taxonomy
					*/
					function update_all_tables($con){
						// reverser the order  ORDER MATTERS!!
						update_identity_assembly_table($con);
						update_assemly_table($con);
						update_antibiogram_table($con);
						update_metadata_table($con);
						update_threat_table($con);
						update_tax_table($con);
						uodate_identity_sequence_table($con);
						update_variant_table($con);
						update_class_table($con);
						update_Identity_table($con);
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
					function update_antibiogram_table($con){
						$id_t=init_antibiogram_table_arry();
						$t_col=get_table_colums($con,'Antibiogram');
						$update_string= prepare_update_statement('Antibiogram',$t_col);
						if(!isset($id_t)){
							return;
						}
						$newstart=execute_update($con,$t_col,$update_string,$id_t,'Antibiogram',0);
						while ($newstart<sizeof($id_t)-1) {
							// echo "<b>doing it</b>";
							$newstart=execute_update($con,$t_col,$update_string,$id_t,'Antibiogram',$newstart+1);
						}
					}
					function update_metadata_table($con){
						$id_t=init_metadata_table_array();
						$t_col = get_table_colums($con,'Sample_Metadata');
						$update_string= prepare_update_statement('Sample_Metadata',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Sample_Metadata',0);
					}

					function update_threat_table($con){
						$id_t=init_threat_table_array();
						$t_col = get_table_colums($con,'Threat_Level');
						$update_string= prepare_update_statement('Threat_Level',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Threat_Level',0);
					}
					function update_tax_table($con){
						$id_t=init_taxonomy_table_array();
						$t_col = get_table_colums($con,'Taxonomy');
						$update_string= prepare_update_statement('Taxonomy',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Taxonomy',0);
					}

					function update_variant_table($con){
						$id_t=init_variant_table_array();
						$t_col=get_table_colums($con,'Variants');
						$update_string= prepare_update_statement('Variants',$t_col);
						if(!isset($id_t)){
							return;
						}
						$newstart=execute_update($con,$t_col,$update_string,$id_t,'Variants',0);
						while ($newstart<sizeof($id_t)-1) {
							$newstart=execute_update($con,$t_col,$update_string,$id_t,'Variants',$newstart+1);
						}
					}
					function uodate_identity_sequence_table($con){
						$id_t=identity_Sequence_table_array();
						$t_col = get_table_colums($con,'Identity_Sequence');
						$update_string= prepare_update_statement('Identity_Sequence',$t_col);
						execute_update($con,$t_col,$update_string,$id_t,'Identity_Sequence',0);
					}
					function update_class_table($con){
						$id_t=init_classification_table_array();
						$t_col=get_table_colums($con,'Classification');
						$update_string= prepare_update_statement('Classification',$t_col);
						if(!isset($id_t)){
							return;
						}
						$newstart=execute_update($con,$t_col,$update_string,$id_t,'Classification',0);
						while ($newstart<sizeof($id_t)-1) {
							// echo "<b>doing it</b>";
							$newstart=execute_update($con,$t_col,$update_string,$id_t,'Classification',$newstart+1);
						}
						// echo '<br> posotion: '.$newstart.' end pos'.sizeof($id_t);
					}

					function update_Identity_table($con){
						$id_t=init_identity_table_array();
						$t_col = get_table_colums($con,'Identity');
						$update_string= prepare_update_statement('Identity',$t_col);
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
							echo "<br>  <b>Error ".$msg.":</b><br>".$e;
							die();
						}
						return $i;
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
					function init_antibiogram_table_arry(){

						for ($i=0; $i <sizeof($_POST['anti_id']) ; $i++) { 
							if(trim($_POST['anti_id'][$i])!=''){
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
								$returnVal[]=$_POST['meta_id'];
								$returnVal[]=date("Y-m-d H:i:s");//Modified Date
								$returnVal[]=$_SESSION['userID'];//ModifiedBy
								$returnVal[]=$_POST['anti_id'][$i];// ID 
							}
						}
						if(!isset($returnVal)){
							return;
						}
						return $returnVal;
					}	
					function init_metadata_table_array(){
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
					function init_variant_table_array(){
						for($i=0;$i<sizeof($_POST['va_id']);$i++){
							if(trim($_POST['va_id'][$i])!=''){
								$returnVal[]=$_POST['snp'][$i];//SNP
								$returnVal[]=$_POST['vpubID'][$i];//PubMed_IDs  Plasmid
								$returnVal[]=$_POST['va_ids_id'][$i];//id from id_seq table 
								$returnVal[]=$_POST['ca_id'][$i];//id from classification table
								$returnVal[]="1";// is_active 0 or1   default is 1  
								$returnVal[]=date("Y-m-d H:i:s");//Modified Date
								$returnVal[]=$_SESSION['userID'];//ModifiedBy
								$returnVal[]=$_POST['va_id'][$i];//id
							}
						}
						if(!isset($returnVal)){
							return;
						}
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
						for ($i=0; $i <sizeof($_POST['ca_id']) ; $i++) { 
							$returnVal[]=$_POST['drug'][$i];//drug_name
							$returnVal[]=$_POST['drug_class'][$i];//drug_class
							$returnVal[]=$_POST['drug_family'][$i];//drug_family
							$returnVal[]="";//drug_sub_class
							$returnVal[]=$_POST['mechanism_of_action'][$i];//mechanism_of_action
							$returnVal[]=$_POST['id_id'];//id from identity table
							$returnVal[]="1";// is_active 0 or1   default is 1  
							$returnVal[]=date("Y-m-d H:i:s");//Modified Date
							$returnVal[]=$_SESSION['userID'];//ModifiedBy
							$returnVal[]=$_POST['ca_id'][$i];//id
						}
						if(!isset($returnVal)){
							return;
						}
						return $returnVal;
					}
					function init_identity_table_array(){
						$returnVal[]=$_POST['gene_symbol'];
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
						$returnVal[]=$_POST['pubmed_id'];
						$returnVal[]=$_POST['hmm'];
						$returnVal[]="1";// is_active 0 or1   default is 1  
						$returnVal[]=$_POST['Stat'];//Status
						$returnVal[]=date("Y-m-d H:i:s");//Modified Date
						$returnVal[]=$_SESSION['userID'];//ModifiedBy
						$returnVal[]=$_POST['id_id'];
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
			        ?>
			</div>
		</div>
	</div>
</section>	
<?php include 'includes/footer.php';?>

