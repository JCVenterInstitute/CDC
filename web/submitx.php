<?php
include 'includes/header.php';
include 'includes/config.inc.php';
?>
<script type="text/javascript">
function validateForm(){
  if((document.getElementById("drug_class").value).length == 0){
 			alert(" Please Enter a Drug Class!");  
    		return false;
  }	 	
}	
// count for variants, claassifcation, and antibiogram
  var count =1;
  var v_count=0;
  var a_count=1;
</script>
	<!--     Fonts and icons     -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <!-- <script src="js/bootstrap.min.js" type="text/javascript"></script> -->
    <script src="js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

    <!--  Plugin for the Wizard -->
    <script src="js/gsdk-bootstrap-wizard.js"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/  -->
    <script src="js/jquery.validate.min.js"></script>

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-12">
                    <?php 
                    # Search Identity Table
#                    $sql = "SELECT ID FROM `Identity` ORDER BY ID DESC LIMIT 1"; 
                    $snp_var_tracker=0;
                    $sql = "select nextval('CDC_seq')";
                    $query=mysql_query($sql);
					$ids=mysql_fetch_array($query); 

					// print_r($ids);
					$id = $ids[0]; 
					?>
                <div class="card wizard-card" data-color="red" id="wizardProfile">
				<form name="example" action="dataseq.php" method="POST" enctype="multipart/form-data" onSubmit="return validateForm()">   
				             <!--        You can switch ' data-color="orange" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->
                    	<div class="wizard-header">
                        	<h3>Submit new data to the AMRdb</h3>
                       	    <p>This page allows the user to submit new AMR data into the AMRdb. The page is split into six different sections or tabs: 1) Identification details of the AMR entry, 2) drug classification and SNP variant information, 3) antibiogram data if any, 4) threat level of the organism, 5) taxonomy of the organism, 6) protein and nucleotide sequence, and 7) any metadata associated with the AMR sequence. For more information see the <a href="help.php#location">help page</a>.</p>
                    	</div>

						<div class="wizard-navigation">
							<ul>
	                            <li><a href="#about" data-toggle="tab">Identity</a></li>
	                            <li><a href="#classi" data-toggle="tab">Classification</a></li>    <li><a href="#anti" data-toggle="tab">Antibiogram</a></li>
	                            <li><a href="#threat" data-toggle="tab">Threat Level</a></li>
	                            <li><a href="#taxonomy" data-toggle="tab">Taxonomy</a></li>
	                            <li><a href="#sequence" data-toggle="tab">Sequence</a></li>	 
	                             <li><a href="#meta" data-toggle="tab">Metadata</a></li>                          
	                        </ul>
						</div>

                        <div class="tab-content"  style="background:white;">
                            <div class="tab-pane" id="about">
                            	<!-- first tab  -->
                              <div class="row">
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="identity" class="control-label">Identity ID</label>
	                                        <input type="text" class="form-control" name="identity" id="identity"  value="<?php echo "$id"; ?>" placeholder="<?php echo "$id"; ?>" readonly >
	                                    </div>
	                                </div>
                                  
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="gene_symbol" class="control-label">Gene Symbol</label>
	                                        <input type="text" class="form-control" name="gene_symbol" id="gene_symbol" placeholder="e.g. AAC(1)" value="">
	                                    </div>
	                                </div>
									<div class="col-sm-10 col-sm-offset-1">
		                               <div class="form-group">
		                                    <label for="gene_aliases" class="control-label">Product/Protein Name</label>
		                                        <input type="text" class="form-control" name="protein_name"  placeholder="e.g. CMY-7 blacmy-7" value="">
										</div>
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="genbank_id" class="control-label">Source</label>
		                                        <input type="text" class="form-control" name="source" id="source" placeholder="e.g. GenBank" value="">
										</div>
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="genbank_id" class="control-label">Source ID</label>
		                                        <input type="text" class="form-control" name="source_id" id="source_id" placeholder="e.g. AJ011291.1" value="">
										</div>
									</div>																		
	                                <div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="gene_sybmol" class="control-label">Gene Alternative Names</label>
	                                        <input type="text" class="form-control" name="gene_alter_names" id="gene_alter_names" placeholder="e.g. Names" value="">
	                                    </div>
	                                </div>
	                                  <div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="gene_family" class="control-label">Gene Family</label>
	                                        <input type="text" class="form-control" name="gene_family" id="gene_family" placeholder="e.g. AAC(1)" value="">
	                                    </div>
	                                </div>
	                                <div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="gene_class" class="control-label">Gene Class</label>
	                                        <input type="text" class="form-control" name="gene_class" id="gene_class" placeholder="e.g. " value="">
	                                    </div>
	                                </div>
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="gene_family" class="control-label">Protein Alternative Names</label>
	                                        <input type="text" class="form-control" name="protein_alter_names" id="protein_alter_names" placeholder="e.g. Alternative Names " value="">
	                                    </div>
	                                </div>																		
	                                <div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="allele" class="control-label">Allele</label>
		                                        <input type="text" class="form-control" name="allele" id="allele" placeholder="e.g. IIb" value="">
										</div>
									</div>
									<div class="col-sm-10 col-sm-offset-1">
		                               <div class="form-group">
		                                    <label for="gene_aliases" class="control-label">EC Number</label>
		                                        <input type="text" class="form-control" name="ec_number" id="gene_aliases" placeholder="e.g. 3.5.2.6" value="">
										</div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="parent_allele" class="control-label">Parent Allele</label>
		                                        <input type="text" class="form-control" name="parent_allele" id="parent_allele" placeholder="e.g. JF273470" value="">
										</div>
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="parent_allele_family" class="control-label">Parent Allele Family</label>
		                                        <input type="text" class="form-control" name = "parent_allele_family" id="parent_allele_family" placeholder="e.g. ZEG" value="">
										</div>
									</div>									
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="protein_id" class="control-label">Protein ID</label>
		                                        <input type="text" class="form-control" name = "protein_id" id="protein_id" placeholder="e.g. CAB36900.1" value="">
										</div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="pubmed_id" class="control-label">PubMed IDs</label>
		                                        <input type="text" class="form-control" name="plasmid" id="pubmed_id" placeholder="e.g. 1917844" value="">
										</div>
									</div>
								
									 <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="plasmid" class="control-label">Plasmid Name</label>
                                                <input type="text" class="form-control" name="plasm_name" id="plasm_name" placeholder="e.g.23954421 " value="<?php echo $as[8]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="hmm" class="control-label">HMM</label>
		                                        <input type="text" class="form-control" name="hmm" id="hmm" placeholder="e.g." value="">
										</div>
									</div>
		                                 
                                    <div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="bioproject_id" class="control-label">Bioproject ID</label>
		                                        <input type="text" class="form-control" name = "bioproject_id" id="bioproject_id" placeholder="e.g. PRJNA225" value="">
										</div>
									</div>
									  <div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="bioproject_id" class="control-label">BioSample ID </label>
		                                        <input type="text" class="form-control" name = "biosample_ID"  placeholder="e.g. 12345" value="">
										</div>
									</div>
									 <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                              <label class="control-label">Status: </label><br>
                                        <input type="radio" name="Stat" id='non' value="Non curated" checked="checked">
                                         Non curated <br><input type="radio" name="Stat" id='curated' value="Curated"> Curated</label>
                                        </div>
                                    </div>
                              </div>
                            </div>


                            <!-- Second Tab -->
 							<div class="tab-pane" id="classi">
                                <div class="row"> 

                                  <div class="col-sm-10 col-sm-offset-1">  
                                  <h2>Classification 1</h2>                                   
                                        <div class="form-group">
                                            <label for="drug_name" class="control-label">Drug</label>
                                                <input type="text" class="form-control" name="drug[]" id="drug_name" placeholder="e.g. Beta" value="">
                                        </div>
                                    </div>  
                                  <div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="drug_family" class="control-label">Drug Family</label>
		                                        <input type="text" class="form-control" name="drug_family[]" id="drug_family" placeholder="e.g. Beta-lactam" value="">
										</div>
									</div>
		                                
									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="drug_class" class="control-label">Drug Class</label>
		                                        <input type="text" class="form-control" name = "drug_class[]" id="drug_class" placeholder="e.g. Class_C_Carbapenemase" value="">
										</div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="sub_drug_class" class="control-label">Mechanism of Action</label>
		                                        <input type="text" class="form-control" name ="mechanism_of_action[]"  id="sub_drug_class" placeholder="e.g. Small Multidrug Reistance" value="">
										</div>
									</div>
									<fieldset class="col-sm-10 col-sm-offset-1">
									<div class="col-sm-10 col-sm-offset-1">	
									<h4>Variants</h4>   	                            
		                                <div class="form-group">
		                                    <label for="snp" class="control-label">SNP</label>
		                                        <input type="text" class="form-control" name="snp[1][]" id="snp" placeholder="e.g. Y114F,M109I,V165I" value="">
										</div>
									</div>
		                                
									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="plasmid" class="control-label">Variant PubMed ID</label>
		                                        <input type="text" class="form-control" name='v_plasmid[1][]' id="plasmid" placeholder="e.g.7989561 " value="">
										</div>
									</div>

									<div ID="vant_div">
									</div>

									 <div class="col-sm-10 col-sm-offset-1">	
	                                	<br>
                                 		<INPUT TYPE="Button" class="control-label"onClick="addRow('vant_div','varant')" VALUE="+ Add Variant">
                                 	</div>
									</fieldset>
	                                <div  ID="tblPets"></div>
	                                <div class="col-sm-10 col-sm-offset-1">	
	                                	<br><br>
                                 		<INPUT TYPE="Button" class="control-label"onClick="addRow('tblPets','classi')" VALUE="+ Add Classification">
                                 	</div>
                               	</div>


                            </div>

                           <!-- third tab -->                            
                            <div class="tab-pane" id="meta">
                                <div class="row">
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Isoloation Site</label>
		                                        <input type="text" class="form-control" name ="isolation_site"  placeholder="e.g. Raw milk " value="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Serotyping Method</label>
		                                        <input type="text" class="form-control" name ="serotyping_method"  placeholder="e.g. Brun" value="">
		                                </div>
									</div>
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Source Common Name</label>
		                                        <input type="text" class="form-control" name ="source_common_name"  placeholder="e.g.ERS000055" value="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Collection Date (DD-MON-YYYY)</label>
		                                        <br><input type="text" class="" name = "specimen_collection_date_DD" style="width: 2em;" id="specimen_collection_date_DD"  placeholder="DD" onkeyup="validate_date('specimen_collection_date_DD')" value="">
		                                        -&nbsp;<input type="text" class="" name = "specimen_collection_date_MON" style="width: 3em;" id="specimen_collection_date_MON"  placeholder="MON"onkeyup="validate_date('specimen_collection_date_MON')" value="">
		                                        -&nbsp;<input type="text" class="" name = "specimen_collection_date_YY" style="width: 4em;" id="specimen_collection_date_YY"  placeholder="YYYY" onkeyup="validate_date('specimen_collection_date_YY')" value="" required>
		                                      <!--   &nbsp; <input type="text" class="" name = "specimen_collection_date_HH" style="width: 2em;" id="specimen_collection_date_HH"  placeholder="HH" onkeyup="validate_date('specimen_collection_date_HH')" value="">&nbsp;:
		                                        <input type="text" class="" name = "specimen_collection_date_MM" style="width: 2em;" id="specimen_collection_date_MM"  placeholder="MM" onkeyup="validate_date('specimen_collection_date_MM')" value="">&nbsp;:
		                                        <input type="text" class="" name = "specimen_collection_date_SS" style="width: 2em;" id="specimen_collection_date_SS"  placeholder="SS" onkeyup="validate_date('specimen_collection_date_SS')" value=""> -->
		                                        <br>
		                                         <label for="meta" class="control-label" id="date_valid_label"style="color:#ccc"></label>
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Location Country</label>
		                                        <input type="text" class="form-control" name ="specimen_location_country"  placeholder="e.g. India" value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Location</label>
		                                        <input type="text" class="form-control" name ="specimen_location"  placeholder="e.g. Wernigerode" value ="">
		                                </div>
									</div>
		                                
									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Location Lattitude</label>
		                                        <input type="text" class="form-control" name ="specimen_location_lattitude"   placeholder="e.g. 39.09802374503313" value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Location Longitude</label>
		                                        <input type="text" class="form-control" name = "specimen_location_longitude" placeholder="e.g. -77.19469801321412" value ="">
		                                </div>
									</div>
									
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Source Age</label>
		                                        <input type="text" class="form-control" name ="specimen_source_age"  placeholder="e.g. 32" value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Development Stage</label>
		                                        <input type="text" class="form-control" name ="specimen_dev_stage"  placeholder="e.g. Missing" value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Source Disease</label>
		                                        <input type="text" class="form-control" name ="specimen_source_disease"  placeholder="e.g. Swine Dysentery" value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Source Gender</label>
		                                        <input type="text" class="form-control" name ="specimen_source_gender" placeholder="e.g. Male" value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Health Status</label>
		                                        <input type="text" class="form-control" name ="specimen_health_status" placeholder="e.g.missing " value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Treatment</label>
		                                        <input type="text" class="form-control" name ="specimen_treatment" placeholder="e.g. missing " value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Type</label>
		                                        <input type="text" class="form-control" name ="speciment_type" placeholder="e.g.missing " value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Specimen Symptom</label>
		                                        <input type="text" class="form-control" name ="speciment_symptom" placeholder="e.g. missing " value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="meta" class="control-label">Host</label>
		                                        <input type="text" class="form-control" name ="speciment_Host" placeholder="e.g. Sus scrofa " value ="">
		                                </div>
									</div>
                                </div>
                            </div>
                           

                           
                            <!-- Third tab -->                            
                            <div class="tab-pane" id="anti">
                                <div class="row">
									<div class="col-sm-10 col-sm-offset-1">  
									<h3>Antibiogram 1</h3>       
		                                <div class="form-group">
		                                    <label for="antibiotic" class="control-label">Antibiotic</label>
		                                        <input type="text" class="form-control" name ="antibiotic[]" id="antibiotic" placeholder="e.g.meropenem " value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Drug Symbol</label>
		                                        <input type="text" class="form-control" name ="drug_symbol[]"  placeholder="e.g.d symbol " value ="">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Laboratory Typing Method</label>
		                                        <input type="text" class="form-control" name="laboratory_typing_method[]"  placeholder="e.g. met " value ="">
		                                </div>
									</div>


									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Laboratory Typing Platform</label>
		                                        <input type="text" class="form-control" name = "laboratory_typing_platform[]" placeholder="e.g. 96-well plate " value ="">
		                                </div>
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Laboratory Typing Method Version or Reagent</label>
		                                        <input type="text" class="form-control" name = "laboratory_typing_method_version_reagent[]" placeholder="e.g.  Reagent" value ="">
		                                </div>
									</div>
		                                
									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Measurement</label>
		                                        <input type="text" class="form-control" name ="measurement[]" placeholder="e.g. 45" value = "">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Measurement Sign</label>
		                                        <input type="text" class="form-control" name="measurement_sign[]" placeholder="e.g. == " value = "">
		                                </div>
									</div>
									
									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Measurement Units</label>
		                                        <input type="text" class="form-control" name ="measurement_unit[]" placeholder="e.g. mg/L" value = "">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Resistance Phenotype</label>
		                                        <input type="text" class="form-control"name="resistance_phenotype[]" placeholder="e.g. resistant" value = "">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Testing Standard</label>
		                                        <input type="text" class="form-control" name="testing_standard[]" placeholder="e.g. CLSI" value = "">
		                                </div>
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Vendor</label>
		                                        <input type="text" class="form-control" name = "vendor[]" placeholder="e.g. Vendor" value = "">
		                                </div>
									</div>
		                            <div  ID="anti_tab">
	                                </div>
	                                <div class="col-sm-10 col-sm-offset-1">	
	                                	<br>
                                 	<INPUT TYPE="Button" class="control-label"onClick="addRow('anti_tab','anti')" VALUE="+ Add">
                                 </div>
                                </div>
                            </div>

                           <!-- Third tab -->
                            <div class="tab-pane" id="threat">
                                <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1" style="float:center;">
                                            <div style="float:center;">
                                              	<p><input type="radio" name="treatlevel" value="urgent"> Urgent<br></p>
                                            </div>
                                       
                                             <div style="float:center;">
                                             	<p><input type="radio" name="treatlevel" value="serious"> Serious<br></p>
                                            </div>
                                   
                                             <div style="float:center;">
                                             	<p><input type="radio" name="treatlevel" value="concern"> Concern<br></p>
                                            </div>
                                        
                                             <div style="float:center;">
                                             	<p><input type="radio" name="treatlevel" value="unknown" checked="checked"> Unknown<br></p>
                                            </div>
                                        
                                             <div style="float:center;">
                                             	<p><input type="radio" name="treatlevel" value="none"> None<br></p>
                                            </div>
                                    </div>

                                </div>
                            </div>

                           <!-- Fourth tab -->
                            <div class="tab-pane" id="taxonomy">
                                <div class="row">
                                	<div class="col-sm-10 col-sm-offset-1">		                                                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Taxonomy ID</label>
		                                        <input type="text" class="form-control" name ="tax_id"  placeholder="e.g.470.0 " value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                                                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Taxon Kingdom</label>
		                                        <input type="text" class="form-control" name ="taxon_kingdom"  placeholder="e.g.Bacteria " value ="">
										</div>									
									</div>


									<div class="col-sm-10 col-sm-offset-1">		                                                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Phylum</label>
		                                        <input type="text" class="form-control" name ="phylum"  placeholder="e.g. Proteobacteria" value ="">
										</div>									
									</div>


									<div class="col-sm-10 col-sm-offset-1">		                                                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Taxon Bacterial BioVar</label>
		                                        <input type="text" class="form-control" name ="taxon_bacterial_bioVar"  placeholder="e.g.missing" value ="">
										</div>									
									</div>
		
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Class</label>
		                                        <input type="text" class="form-control" name = "class_anti"  placeholder="e.g. Gammaproteobacteria" value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Order</label>
		                                        <input type="text" class="form-control" name = "order_anti"  placeholder="e.g.Enterobacterales" value ="">
										</div>									
									</div>
		
									<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Family</label>
		                                        <input type="text" class="form-control" name = "family_anti"  placeholder="e.g. Enterobacteriaceae" value ="">
										</div>									
									</div>
		
									<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Genus</label>
		                                        <input type="text" class="form-control" name = "genus_anti"  placeholder="e.g. Escherichia" value ="">
										</div>									
									</div>
		                                
									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Species</label>
		                                        <input type="text" class="form-control" name ="species_anti"  placeholder="e.g. pittii" value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Sub Species</label>
		                                        <input type="text" class="form-control" name ="sub_species_anti"  placeholder="e.g. enterica" value ="">
										</div>									
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Taxonomy Pathovar</label>
		                                        <input type="text" class="form-control" name ="taxon_pathovar"  placeholder="e.g. Pathovar" value ="">
										</div>									
									</div>

									<div class="col-sm-10 col-sm-offset-1">		                                
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Taxonomy Serotype</label>
		                                        <input type="text" class="form-control" name ="taxon_serotype"  placeholder="e.g. O8:H19" value ="">
										</div>									
									</div>

							
									<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Strain</label>
		                                        <input type="text" class="form-control" name ="strain" placeholder="e.g. VC627" value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Sub Strain</label>
		                                        <input type="text" class="form-control" name ="sub_strain" placeholder="e.g. W3110" value ="">
										</div>									
									</div>

                                </div>
                            </div>

                          <!-- fifth tab -->
                            <div class="tab-pane" id="sequence">
                                <div class="row">
                                	<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Mole Type</label>
		                                        <input type="text" class="form-control" name ="mol_type"  placeholder="5 chracter only N/A" value ="">
										</div>									
									</div>
                                	<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Feat Type</label>
		                                        <input type="text" class="form-control" name ="feat_type"  placeholder="5 chracter only N/A" value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">End 3</label>
		                                        <input type="text" class="form-control" name = "end3"  placeholder="e.g. 3136" value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">		
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">End 5</label>
		                                        <input type="text" class="form-control" name ="end5"  placeholder="e.g. 4762" value ="">
										</div>									
									</div>
									<div class="col-sm-10 col-sm-offset-1">		                                                            
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Protein Sequence</label>
                                        	<textarea class="form-control" rows="3" name ="protein_squence"  placeholder="EQARSEIYIYDLAVSGEHRRQGIATALINLLKHEANALGAYVIYVQADYGDDPAVALYTKLGIREEVMHFDIDPSTAT" value =""></textarea>
										</div>									
									</div>
		
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="anti" class="control-label">Nucleotide Sequence</label>
	                                        <textarea class="form-control" rows="3" name = "nucleotide_sequence" placeholder="TACGGGAAGAAGTGATGCACTTTGATATCGACCCAAGTACCGCCACCTAAC" value =""></textarea>
										</div>									
									</div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="wizard-footer height-wizard">
                            <div class="pull-right">

                            	<!-- Hidden fields -->
                            	<input type="Hidden" id='c_count' name="c_count" value="1">
                            	<input type="Hidden" id='a_count' name="a_count" value="1">
                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd btn-sm' name='next' value='Next' />
                                <input type='submit' class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' name='method' value='Submit' />

                            </div>

                            <div class="pull-left">
                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' value='Previous' />
                            </div>
                            <div class="clearfix"></div>
                        </div>
<SCRIPT TYPE="text/javascript">

  function addRow(in_tbl_name,add_options)
  {
  	
	if(add_options=='classi'){
		c_count++;
		count++;
		v_count++;
		let container_pas = document.getElementById(in_tbl_name);
	  	let outter_div=document.createElement('div');
	  	outter_div.setAttribute("id",'addon_fields'+count);
	  	container_pas.appendChild(outter_div);
		// create HR as a divider
		let hrcontain = document.getElementById('addon_fields'+count);
	    let hr=document.createElement('hr');
	    hr.style="border-color:#aaa";
	    let coldiv1=document.createElement('div');
  		coldiv1.className='col-sm-10 col-sm-offset-1';
  	 	let groudiv2=document.createElement('div');
	  	groudiv2.className='form-group';
	  	hrcontain.appendChild(coldiv1);
		coldiv1.appendChild(groudiv2);
	    coldiv1.appendChild(hr);
	    let h=document.createElement('h3');
	    let ht = document.createTextNode('Classification '+count);
	    h.appendChild(ht);
	    coldiv1.appendChild(h);
	    //call helper function to create fields
	    addRow_helper('','addon_fields'+count,'Drug Name','drug[]','e.g. Beta-lactam',count);
		addRow_helper('','addon_fields'+count,'Drug Family','drug_family[]','e.g. Beta-lactam',count);
		addRow_helper('','addon_fields'+count,'Drug Class','drug_class[]','e.g. Class_C_Carbapenemase',count);
		addRow_helper('','addon_fields'+count,'Sub Drug Class','sub_drug_class[]','e.g. Resistance-Nodulation_Cell_Division',count);
		let start_indent=addRow_helper('','addon_fields'+count,'Mechanism of Action','mechanism_of_action[]','e.g. action',count);

		// add field set as their parents => snp and variants are children of classification 
		// let field_set = document.createElement('fieldset');
		//create a fieldset then add a div  then satrt to insert snps

		let child_container = document.createElement('fieldset');
		child_container.id='field_set_id'+count;
		start_indent.appendChild(child_container);
		let h2=document.createElement('h4');
	    let ht2 = document.createTextNode('Variants');
	    h2.className='col-sm-10 col-sm-offset-1';
	    h2.appendChild(ht2);
	    child_container.appendChild(h2);
		addRow_helper('','field_set_id'+count,'SNP','snp['+count+'][]','e.g.xxxx',count);
		addRow_helper('','field_set_id'+count,'Variant PubMed ID','v_plasmid['+count+'][]','e.g.xxxx',count);
		// create add and remove button for varuants and snps 
		let holder_div = document.createElement('DIV');
		holder_div.id='vant_div'+count;
		let add_varin_btn=document.createElement('input');
		add_varin_btn.type='button';
		add_varin_btn.className='control-label';
		add_varin_btn.onclick=function(){addRow('vant_div'+count,'varant');};
		add_varin_btn.value='+ Add Variant';
		child_container.appendChild(holder_div);
		child_container.appendChild(add_varin_btn);
		// create remove button
		let removeMe=document.createElement("input");
		removeMe.type="Button";
		removeMe.className='red_buttond';
		removeMe.onclick=function(){hrcontain.remove();document.getElementById('c_count').value=count;};
		removeMe.value='Delete Classification';
		// add remove button		
		coldiv1.appendChild(removeMe);
		coldiv1.appendChild(document.createElement("BR"));
		coldiv1.appendChild(document.createElement("BR"));
		document.getElementById('c_count').value=count;
		// console.log(document.getElementById('c_count').value);
	}
	if(add_options=='anti'){
		a_count++;
		document.getElementById('a_count').value=a_count;
		let container_pas = document.getElementById(in_tbl_name);
	  	let outter_div=document.createElement('div');
	  	outter_div.setAttribute("id",'ant_addon_fields'+a_count);
	  	container_pas.appendChild(outter_div);
		// create HR as a divider
		let hrcontain = document.getElementById('ant_addon_fields'+a_count);
	    let hr=document.createElement('hr');
	    hr.style="border-color:#aaa";
	    let coldiv1=document.createElement('div');
  		coldiv1.className='col-sm-10 col-sm-offset-1';
  	 	let groudiv2=document.createElement('div');
	  	groudiv2.className='form-group';
	  	hrcontain.appendChild(coldiv1);
		coldiv1.appendChild(groudiv2);
	    coldiv1.appendChild(hr);


	    let h=document.createElement('h3');
	    let ht = document.createTextNode('Antibiogram '+a_count);
	    h.appendChild(ht);
	    coldiv1.appendChild(h);
	    //call helper function to create fields
		addRow_helper('','ant_addon_fields'+a_count,'Antibiotic','antibiotic[]','e.g. ',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Drug Symbol','drug_symbol[]','e.g.',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Laboratory Typing Method','laboratory_typing_method[]','e.g. -',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Laboratory Typing Platform','laboratory_typing_platform[]','e.g. ',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Laboratory Typing Method Version or Reagent','laboratory_typing_method_version_reagent[]','e.g.xxxx',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Measurement','measurement[]','e.g. -',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Measurement Sign','measurement_sign[]','e.g. -',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Measurement Units','measurement_unit[]','e.g. -',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Resistance Phenotype','resistance_phenotype[]','e.g. -',a_count);
		addRow_helper('','ant_addon_fields'+a_count,'Testing Standard','testing_standard[]','e.g. -',a_count);
		let groudiv=addRow_helper('','ant_addon_fields'+a_count,'vendor','vendor[]','e.g. ',a_count);
		// create remove button
		let removeMe=document.createElement("input");
		removeMe.type="Button";
		removeMe.className='red_buttond';
		removeMe.onclick=function(){a_count--;hrcontain.remove();document.getElementById('c_count').value=a_count;};
		removeMe.value='Delete';
		// add remove button
		groudiv.appendChild(document.createElement("BR"));
		groudiv.appendChild(removeMe);
	}

	if(add_options=='varant'){
		v_count++;
		let container_pas = document.getElementById(in_tbl_name);
	  	let outter_div=document.createElement('div');
	  	outter_div.setAttribute("id",'addon_va_fields'+v_count);
	  	container_pas.appendChild(outter_div);
		// create HR as a divider
		let hrcontain = document.getElementById('addon_va_fields'+v_count);
	    let hr=document.createElement('hr');
	    hr.style="border-color:#aaa";
	    let coldiv1=document.createElement('div');
  		coldiv1.className='col-sm-10 col-sm-offset-1';
  	 	let groudiv2=document.createElement('div');
	  	groudiv2.className='form-group';
	  	hrcontain.appendChild(coldiv1);
		coldiv1.appendChild(groudiv2);
	    coldiv1.appendChild(hr);
		addRow_helper('','addon_va_fields'+v_count,'SNP','snp['+count+'][]','e.g.xxxx',v_count);
		let groudiv=addRow_helper('','addon_va_fields'+v_count,'Variant PubMed ID','v_plasmid['+count+'][]','e.g.xxxx',v_count);
		// create remove button
		let removeMe=document.createElement("input");
		removeMe.type="Button";
		removeMe.className='red_buttond';
		removeMe.onclick=function(){hrcontain.remove();document.getElementById('c_count').value=v_count;};
		removeMe.value='Delete';
		// add remove button
		groudiv.appendChild(document.createElement("BR"));
		groudiv.appendChild(removeMe);
		document.getElementById('c_count').value=v_count;
		console.log(document.getElementById('c_count').value);
	}
	
  }
  function addRow_helper(parent_node, in_tbl_name,lable_name,field_name,holder,countMe){
  	if(parent_node==''){
		let container = document.getElementById(in_tbl_name);
	  	let coldiv=document.createElement('div');
	  	let groudiv=document.createElement('div');
	  	let lb = document.createElement("LABEL");
	  	let t = document.createTextNode(lable_name);
	    let input = document.createElement("input");
	  	coldiv.className='col-sm-10 col-sm-offset-1';
	  	// coldiv.setAttribute("id",'addon_fields'+countMe);
	  	groudiv.className='form-group';
	  	lb.className='control-label';
	    input.type = "text";
	    input.name = field_name;
	    input.placeholder =holder;
	    input.className='form-control';
	    container.appendChild(coldiv);
		coldiv.appendChild(groudiv);
		groudiv.appendChild(lb);
		groudiv.appendChild(t);
	    groudiv.appendChild(input);
	   return groudiv;
	}
	
  }
</SCRIPT>
<script type="text/javascript">

   function validate_date(f_name){
   	// console.log("called ME ");
	 let valid_date=document.getElementById("date_valid_label");
	if(f_name=="specimen_collection_date_YY"){
		let regex = /(\d){4}$/gm;
		let match =document.getElementById(f_name).value.match(regex);
		  if(!match){
		  	valid_date.style="color:red";
		   valid_date.innerHTML="Wrong format detected Correct format: DD-MON-YYYY HH:MM:SS    note: e.g. MON:Jan ";
		  }
		  if(match&&match[0].length==4){
		  	valid_date.style="color:#ccc";
		   valid_date.innerHTML="Valid";
		  }
	}else if(f_name=="specimen_collection_date_MON"){
		let regex = /^[A-Z][a-z]{2}$/gm;
		let match =document.getElementById(f_name).value.match(regex);
		  if(!match){
		  	valid_date.style="color:red";
		   valid_date.innerHTML="Wrong format detected Correct format: DD-MON-YYYY HH:MM:SS    note: e.g. MON:Jan ";
		  }
		  if(match&&match[0].length==3){
		  	valid_date.style="color:#ccc";
		   valid_date.innerHTML="Valid";
		  }
	}else{
		let regex = /^(\d){2}$/gm;
		let match =document.getElementById(f_name).value.match(regex);
		  if(!match){
		  	valid_date.style="color:red";
		   valid_date.innerHTML="Wrong format detected Correct format: DD-MON-YYYY HH:MM:SS    note: e.g. MON:Jan ";
		  }
		  if(match&&match[0].length==2){
		  	valid_date.style="color:#ccc";
		   valid_date.innerHTML="Valid";
		  }
	}

}


</script>
                    </form>
			</div>
		</div>
	</div><!-- end row -->
</div> <!--  big container -->
<?php include 'includes/footerx.php';?>
    
