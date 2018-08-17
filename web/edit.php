<?php
include 'includes/header.php';
include 'includes/config.inc.php';
?>

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
    <script type="text/javascript">
    var count =1;
    var class_count=1;
  var v_count=0;
  var a_count=1;</script>

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-12">
                    <?php 
                    $idd = $_GET["id"];
                    # Search Identity Table
                    $sql = "SELECT * FROM Identity WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $ids=mysql_fetch_array($query); 
                    #print_r ($ids);                    f

                    # Search Classification Table
                    $sql=$query="";
                    $sql = "SELECT ise.* FROM CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd'";
                    $query=mysql_query($sql);
                    $va=mysql_fetch_array($query); 
                    #print_r ($va); #                   foreach ($va as $vax){ echo "**$vax | "; }
                    // var_dump($va);
                    # Search Variant Table
                    $sql=$query="";
                    $sql = "SELECT iss.* FROM CDC.Variants iss,CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Classification_ID = ise.ID";
                    $query=mysql_query($sql);
                    $var=mysql_fetch_array($query); 
#                   print_r ($var);         echo $var[1];

                    # Search Bioproject Table
                    $sql=$query="";
                    $sql = "SELECT a.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID";
                    $query=mysql_query($sql);
                    $as=mysql_fetch_array($query); 
#                   print_r ($as);      echo $as[7];
                    
                    # Search antibiogram Table
                    $sql=$query="";
                    $sql = "SELECT anti.* from (select sm.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a LEFT JOIN CDC.Sample_Metadata sm ON sm.ID=a.Sample_Metadata_ID where 1=1 and i.ID ='$idd'  and i.ID=ise.Identity_ID and ise.ID=iss.Identity_Sequence_ID and iss.Assemly_ID=a.ID )as sm_t  LEFT JOIN CDC.Antibiogram anti ON sm_t.ID=anti.Sample_Metadata_ID where 1=1";
                    $query=mysql_query($sql);
                    $anti=mysql_fetch_array($query); 


                    # Search metadata Table
                    $sql=$query="";
                    $sql = "SELECT sm.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a LEFT JOIN CDC.Sample_Metadata sm ON sm.ID=a.Sample_Metadata_ID where 1=1 and i.ID = '$idd' and i.ID=ise.Identity_ID and ise.ID=iss.Identity_Sequence_ID and iss.Assemly_ID=a.ID";
                    $query=mysql_query($sql);
                    $meta=mysql_fetch_array($query); 
                  // print_r ($meta);        
                  // echo $meta[10];

                    # Search taxonomy Table
                    $sql=$query="";
                    $sql = "SELECT t.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a,CDC.Taxonomy t where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID and t.ID=a.Taxonomy_ID";
                    $query=mysql_query($sql);
                    $tax=mysql_fetch_array($query); 
#                   print_r ($tax);         echo $tax[1];
                    // var_dump($tax);
                                                
                    # Search sequence Table
                    $sql=$query="";
                    $sql = "SELECT ise.* from CDC.Identity_Sequence ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd'";
                    $query=mysql_query($sql);
                    $seq=mysql_fetch_array($query); 
#                   print_r ($seq);         echo $seq[3];
                    $sql=$query="";
                    $sql = "SELECT idaa.* from CDC.Identity_Sequence ise,CDC.Identity i, Identity_Assembly idaa where i.ID=ise.Identity_ID and idaa.Identity_Sequence_ID=ise.ID and i.ID = '$idd'";
                    $query=mysql_query($sql);
                    $idaa=mysql_fetch_array($query); 
                    // echo 'hey '.$idaa[0];
                   
                    //search threat_level 
                    $sql=$query="";
                    $sql = "SELECT tl.* from CDC.Identity ise, CDC.Threat_Level tl where ise.ID = '$idd' and ise.ID=tl.Identity_ID";
                    $query=mysql_query($sql);
                    $tl=mysql_fetch_array($query); 
                    // print_r ($tl); //        echo $tax
                      if($tl=='false'){
                       $tl[0]="false";
                      }
                    ?>
                <div class="card wizard-card" data-color="red" id="wizardProfile">
                <form name="example" role="form" action="submit_edit.php" method="POST" enctype="multipart/form-data" onSubmit="return validateForm()">                <!--        You can switch ' data-color="orange" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->
                        <div class="wizard-header">
                            <h3> Edit Identity ID <b><?php echo "$idd" ?></b> in AMRdb</h3>
                            <p>The tabs allow the use to edit existing AMR selected ID in the AMRdb. The data is split into six different tabs. The first tab displays the identification details which consist of gene symbol, allele information, genebank/protein id, SNP and drug class details. The second tab displays any metadata associated with the ID. The third tab has antibiogram data from associated bioproject and the fourth tab has threat level. The taxonomy information is in the fifth tab and the protein/nucleotide sequence is present in the last tab. <a href="help.php#location">help page</a>.</p>
                        </div>

                        <div class="wizard-navigation">
                            <ul>
                                <li><a href="#about" data-toggle="tab">Identity</a></li>
                                <li><a href="#classi" data-toggle="tab">Classification</a></li>
                                <li><a href="#anti" data-toggle="tab">Antibiogram</a></li>
                                <li><a href="#threat" data-toggle="tab">Threat Level</a></li>
                                <li><a href="#taxonomy" data-toggle="tab">Taxonomy</a></li>
                                <li><a href="#sequence" data-toggle="tab">Sequence</a></li>
                                <li><a href="#meta" data-toggle="tab">Metadata</a></li>
                            </ul>
                        </div>

                        <div class="tab-content" style="background:white;">
                            <div class="tab-pane" id="about">
                              <div class="row">

                                    <!-- first -->
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <input type="hidden" name="id_id" value="<?php echo $ids[0]; ?>"><!--//Identity -->
                                            <input type="hidden" name="asmbly_id" value="<?php echo $as[0]; ?>"><!-- //Assemly -->
                                            <input type="hidden" name="meta_id" value="<?php echo $meta[0]; ?>"><!-- //sample metadata -->
                                            <input type="hidden" name="tax_id_1" value="<?php echo $tax[0]; ?>"><!-- //Taxonomy -->
                                            <input type="hidden" name="id_seq_id" value="<?php echo $seq[0]; ?>"><!-- //Identity_Sequence -->
                                            <input type="hidden" name="idaa_id" value="<?php echo $idaa[0]; ?>">   <!-- Identity Assembly -->
                                            <input type="hidden" name="tl_id" value="<?php echo $tl[0]; ?>">   <!-- Identity Assembly -->
                                         	 <label for="identity" class="control-label">Identity ID</label>
                                            <input type="text" class="form-control" name="identity" id="identity"  value="<?php echo $ids[0]; ?>" placeholder="<?php echo $ids[0]; ?>" readonly >
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="gene_symbol" class="control-label">Gene Symbol</label>
                                            <input type="text" class="form-control" name="gene_symbol"  value="<?php echo $ids[1]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="gene_symbol" class="control-label">Gene Alternative Names</label>
                                            <input type="text" class="form-control" name="gene_alter_names"  value="<?php echo $ids[2]; ?>">
                                        </div>
                                    </div>
                                      <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="gene_symbol" class="control-label">Gene Family</label>
                                            <input type="text" class="form-control" name="gene_family"  value="<?php echo $ids[3]; ?>">
                                        </div>
                                    </div>
                                      <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="gene_symbol" class="control-label">Gene Class</label>
                                            <input type="text" class="form-control" name="gene_class"  value="<?php echo $ids[4]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="allele" class="control-label">Allele</label>
                                                <input type="text" class="form-control" name="allele" id="allele" placeholder="e.g. 7" value="<?php echo $ids[5]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">
                                       <div class="form-group">
                                            <label for="gene_aliases" class="control-label">EC Number</label>
                                                <input type="text" class="form-control" name="ec_number" id="gene_aliases" value="<?php echo $ids[6]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="parent_allele_family" class="control-label">Parent Allele Family</label>
                                                <input type="text" class="form-control" name = "parent_allele_family" id="parent_allele_family" placeholder="e.g. OXA-55-like" value="<?php echo $ids[7]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="parent_allele" class="control-label">Parent Allele</label>
                                                <input type="text" class="form-control" name="parent_allele" id="parent_allele" placeholder="e.g. CMY" value="<?php echo $ids[8]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="source" class="control-label">Source </label>
                                                <input type="text" class="form-control" name = "source" id="source" placeholder="e.g. OXA-55-like" value="<?php echo $ids[9]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="genbank_id" class="control-label">Source ID</label>
                                                <input type="text" class="form-control" name="genbank_id" id="genbank_id" placeholder="e.g. AJ011291.1" value="<?php echo $ids[10]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="protein_id" class="control-label">Protein ID</label>
                                                <input type="text" class="form-control" name = "protein_id" id="protein_id" placeholder="e.g. CAB36900.1" value="<?php echo $ids[11]; ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">
                                       <div class="form-group">
                                            <label for="gene_aliases" class="control-label">Protein Name</label>
                                                <input type="text" class="form-control" name="protein_name" id="protein_name" placeholder="e.g. CMY-7 blacmy-7" value="<?php echo $ids[12]; ?>">
                                        </div>
                                    </div>
                                      <div class="col-sm-10 col-sm-offset-1">
                                       <div class="form-group">
                                            <label for="gene_aliases" class="control-label">Protein Alternative Names</label>
                                                <input type="text" class="form-control" name="protein_alter_names" id="protein_alter_names" placeholder="e.g. CMY-7 blacmy-7" value="<?php echo $ids[13]; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="pubmed_id" class="control-label">PubMed IDs</label>
                                                <input type="text" class="form-control" name="pubmed_id" id="pubmed_id" placeholder="e.g. 1917844" value="<?php echo $ids[14]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="pubmed_id" class="control-label">HMM</label>
                                                <input type="text" class="form-control" name="hmm" id="hmm" placeholder="e.g. 1917844" value="<?php echo $ids[15]; ?>">
                                        </div>
                                    </div>

                                     <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="plasmid" class="control-label">Plasmid Name</label>
                                                <input type="text" class="form-control" name="plasm_name" id="plasm_name" placeholder="e.g. " value="<?php echo $as[8]; ?>">
                                        </div>
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="bioproject_id" class="control-label">Bioproject ID</label>
                                                <input type="text" class="form-control" name = "bioproject_id" id="bioproject_id" placeholder="e.g. PRJNA225" value="<?php echo $as[6];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="pubmed" class="control-label">
                                        <input type="radio" name="Stat" id='non' value="Non curated">
                                         Non curated <br><br><input type="radio" name="Stat" id='curated' value="Curated"> Curated</label>
                                        </div>
                                    </div>

                                    <!-- End of first -->
                              </div>
                            </div>

                           <!-- Second tab: Classification and Variant-->
                               <div class="tab-pane" id="classi">
                               <?php
                                $sql = "SELECT ise.* FROM  CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd'";
                                $query=mysql_query($sql);
                                // $second_tab_content[]="";
                                $class_parent_count=0;
                                while($loop_varian_class=mysql_fetch_array($query)){
                                     $class_parent_count++;
                                    $tempContent=<<<TT
                                    <div class="row">
                                                <script type="text/javascript"> count++; class_count++;</script>
                                            <input type="hidden" name="ca_id[]" value='$loop_varian_class[0]'><!-- classification -->
                                            <div class="col-sm-10 col-sm-offset-1"> 
                                            <h2>Classification $class_parent_count</h2> 
                                                 <div class="form-group">
                                                    <label for="drug_class" class="control-label">Drug</label>
                                                    <input type="text" class="form-control" name="drug[]" placeholder="e.g. Beta-lactam" value='$loop_varian_class[1]'>
                                                </div>
                                            </div>

                                            <div class="col-sm-10 col-sm-offset-1"> 
                                                    <div class="form-group">
                                                        <label for="drug_class" class="control-label">Drug Class</label>
                                                        <input type="text" class="form-control" name = "drug_class[]" placeholder="e.g. Class_C_Carbapenemase" value='$loop_varian_class[2]'>
                                                    </div>
                                            </div> 
                                            <div class="col-sm-10 col-sm-offset-1"> 
                                                     <div class="form-group">
                                                        <label for="drug_family" class="control-label">Drug Family</label>
                                                        <input type="text" class="form-control" name="drug_family[]"  placeholder="e.g. Beta-lactam" value='$loop_varian_class[3]'>
                                                    </div>
                                            </div>
                                              <div class="col-sm-10 col-sm-offset-1"> 
                                                     <div class="form-group">
                                                        <label for="drug_family" class="control-label">Mechanism of Action</label>
                                                        <input type="text" class="form-control" name="mechanism_of_action[]"  placeholder="e.g. Beta-lactam" value='$loop_varian_class[5]'>
                                                    </div>
                                            </div>
                                            <fieldset class="col-sm-10 col-sm-offset-1" >
                                            <h4 >Variants</h4>
                                     
TT;

                                      $sql_child="SELECT * FROM CDC.Variants va WHERE va.Classification_ID=$loop_varian_class[0]";
                                      $query_child=mysql_query($sql_child);
                                    
                                  $va_child_count=0;
                                         while ($loop_va_child=mysql_fetch_array($query_child)) {
                                            $va_child_count++;
                                        // echo 'Hi'.isset($loop_va_child);
                                        // if($loop_va_child)
                                        # code...
                                        $tempContent2=<<<HH
                                            <input type="hidden" name="va_id[]" value='$loop_va_child[0]'><!-- //Variants -->
                                                <div class="col-sm-10 col-sm-offset-1">
HH;
                                          
                                            $tempContent2.=<<<HH
                                              
                                                    <div class="form-group">
                                                        <label for="snp" class="control-label">SNP</label>
                                                        <input type="text" class="form-control" name="snp[$class_parent_count][]" placeholder="e.g. Y114F,M109I,V165I"  value='$loop_va_child[1]'>
                                                    </div>
                                                </div>
                                                <div class="col-sm-10 col-sm-offset-1"> 
                                                         <div class="form-group">
                                                            <label for="pubmed" class="control-label">Variant PubMed ID</label>
                                                            <input type="text" class="form-control" name="v_plasmid[$class_parent_count][]"  value='$loop_va_child[2]'>
                                                        </div><hr style="border-color:#aaa;">
                                                </div>
                                            
                                             
                                          

HH;
                                                $tempContent.=$tempContent2;
                                    }
                                    if($va_child_count==0){
                                     $tempContent2=<<<HH
                                          <input type="hidden" name="va_id[]" value='$loop_va_child[0]'>
                                         
                                                <div class="col-sm-10 col-sm-offset-1">
                                                <h4>Variants</h4>  
                                                     <div class="form-group">
                                                        <label for="snp" class="control-label">SNP</label>
                                                        <input type="text" class="form-control" name="snp[$class_parent_count][]" id="snp" placeholder="e.g. Y114F,M109I,V165I"  value=''>
                                                    </div>
                                                </div>
                                                <div class="col-sm-10 col-sm-offset-1"> 
                                                        <div class="form-group">
                                                            <label for="pubmed" class="control-label">Variant PubMed ID</label>
                                                            <input type="text" class="form-control" name="v_plasmid[$class_parent_count][]" placeholder="e.g. 25006521" value=''>
                                                        </div><hr style="border-color:#aaa;">
                                                </div>
                                            

HH;
                                                $tempContent.=$tempContent2;
                                }
                                   
                                $add_button_php=<<<HAHA
                                             <div ID="vant_div$class_parent_count">
                                             </div>    
                                            <div class="col-sm-10 col-sm-offset-1">
                                            
                                                <br>
                                                <INPUT TYPE="Button" class="control-label"onClick="addRow('vant_div$class_parent_count','varant','$class_parent_count')" VALUE="+ Add Variant">
                                            </div>
HAHA;
                         $second_tab_content[]=$tempContent.$add_button_php."  </fieldset></div>";
                    }   


                    $add_class_btn=<<<HAHA
                                <div class="row">
                                    <div  ID="tblPets">
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1"> 
                                        <br><br>
                                        <INPUT TYPE="Button" class="control-label"onClick="addRow('tblPets','classi','$class_parent_count')" VALUE="+ Add Classification">
                                    </div>
                                    </div>

HAHA;
                                    $second_tab_content[].=$add_class_btn;
                                // array_shift($second_tab_content);
                                echo implode('<hr style="border-color:#aaa;">',$second_tab_content);
                            ?>
                            </div>


                            <!-- third tab -->                            
                            <div class="tab-pane" id="anti">
                                <?php
                                     $sql = "select anti.*
                                            From (select a.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID and a.Is_Reference=1) as tempt
                                            LEFT Join Sample_Metadata on Sample_Metadata.ID=tempt.Sample_Metadata_ID
                                            LEFT JOIN Antibiogram anti On tempt.Sample_Metadata_ID= anti.Sample_Metadata_ID";
                                $query=mysql_query($sql);
                                $third_tab_content[]="";
                    while($loop_varian_class=mysql_fetch_array($query)){
                        $tempContent=<<<TT
                        <div class="row">
                                <input type="hidden" name="anti_id[]" value='$loop_varian_class[0]'><!-- //Antibiogram -->
                                <div class="col-sm-10 col-sm-offset-1"> 
                                <div class="form-group">
                                    <label for="antibiotic" class="control-label">Antibiotic</label>
                                        <input type="text" name="antibiotic[]" class="form-control"  value='$loop_varian_class[1]'>
                                    </div>                                  
                                </div>
                            
                                <div class="col-sm-10 col-sm-offset-1"> 
                                            <div class="form-group">
                                    <label for="anti" class="control-label">Drug Symbol</label>
                                        <input type="text" name="drug_symbol[]" class="form-control" value='$loop_varian_class[2]'>
                                    </div>                                  
                                </div>
                                <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti"   class="control-label">Laboratory Typing Method</label>
                                 
                                        <input type="text" name="laboratory_typing_method[]" class="form-control"  value='$loop_varian_class[3]'>
                                    </div>                                  
                                </div>
                                <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti" class="control-label">Laboratory Typing Platform</label>
                                 
                                        <input type="text" name="laboratory_typing_platform[]" class="form-control"  value='$loop_varian_class[4]'>
                                    </div>                                  
                                </div>
                                <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti" class="control-label">Laboratory Typing Method Version or Reagent</label>
                                 
                                        <input type="text" name="laboratory_typing_method_version_reagent[]" class="form-control"  value='$loop_varian_class[5]'>
                                    </div>                                  
                                </div>
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti" class="control-label">Measurement</label>
                                 
                                        <input type="text" name="measurement[]" class="form-control" value='$loop_varian_class[6]'>
                                    </div>                                  
                             </div>
                             <div class="col-sm-10 col-sm-offset-1">
                                     <div class="form-group">
                                        <label for="anti" class="control-label">Measurement Sign</label>
                                        <input type="text" name="measurement_sign[]" class="form-control" value='$loop_varian_class[7]'>
                                    </div>                                  
                            </div>
                            <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                    <label for="anti" class="control-label">Measurement Units</label>
                                 
                                        <input type="text" name="measurement_unit[]" class="form-control" value='$loop_varian_class[8]'>
                                    </div>                                  
                            </div>
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti" class="control-label">Resistance Phenotype</label>
                                 
                                        <input type="text" name="resistance_phenotype[]" class="form-control" value='$loop_varian_class[9]'>
                                    </div>                                  
                                </div>
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti" class="control-label">Testing Standard</label>
                                 
                                        <input type="text" name="testing_standard[]" class="form-control"  value='$loop_varian_class[10]'>
                                    </div>                                  
                                </div>
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="anti" class="control-label">Vendor</label>
                                        <input type="text" name="vendor[]" class="form-control" value='$loop_varian_class[11]'>
                                    </div>                                  
                                </div>  

                        </div>

TT;
                        $third_tab_content[]=$tempContent;
                    } 
                    array_shift($third_tab_content);

                    // add button here
                    $add_anti_btn=<<<HAHA
                    <div class="row">
                    <div  ID="anti_tab">
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1"> 
                                        <br>
                                    <INPUT TYPE="Button" class="control-label"onClick="addRow('anti_tab','anti')" VALUE="+ Add">
                                 </div></div>
HAHA;
                    $third_tab_content[].=$add_anti_btn;

                    echo implode('<hr style="border-color:#aaa;">',$third_tab_content);
                                ?>
                            </div>

                           <!-- Fourth tab -->
                               <div class="tab-pane" id="threat">
                                <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1" style="float:center;">
                                            <div style="float:center;">
                                                <p><input type="radio" id="urgent" name="treatlevel" value="urgent"> Urgent<br></p>
                                            </div>
                                     
                                             <div style="float:center;">
                                                <p><input type="radio" id="serious" name="treatlevel" value="serious"> Serious<br></p>
                                            </div>
                               
                                             <div style="float:center;">
                                                <p><input type="radio" id="concern" name="treatlevel" value="concern"> Concern<br></p>
                                            </div>
                                       
                                             <div style="float:center;">
                                                <p><input type="radio" id="none" name="treatlevel" value="none"> None<br></p>
                                            </div>
                                             <div style="float:center;">
                                                <p><input type="radio" id="unknown" name="treatlevel" value="unknown"> Unknown<br></p>
                                            </div>
                                        
                                    </div>

                                </div>
                            </div>

                            <!-- Fifth tab -->
                            <div class="tab-pane" id="taxonomy">
                                <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1">                                                                 
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Taxonomy ID</label>
                                                <input type="text" class="form-control" name ="tax_id"  placeholder="e.g. " value ="<?php echo $tax[1]; ?>">
                                        </div>                                  
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">                                                                 
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Taxon Kingdom</label>
                                                <input type="text" class="form-control" name ="taxon_kingdom"  placeholder="e.g. " value ="<?php echo $tax[2]; ?>">
                                        </div>                                  
                                    </div>


                                    <div class="col-sm-10 col-sm-offset-1">                                                                 
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Phylum</label>
                                                <input type="text" class="form-control" name ="phylum"  placeholder="e.g. Proteobacteria" value ="<?php echo $tax[3]; ?>">
                                        </div>                                  
                                    </div>


                                    <div class="col-sm-10 col-sm-offset-1">                                                                 
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Taxon Bacterial BioVar</label>
                                                <input type="text" class="form-control" name ="taxon_bacterial_bioVar" placeholder="e.g. " value ="<?php echo $tax[4]; ?>">
                                        </div>                                  
                                    </div>
        
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Class</label>
                                                <input type="text" class="form-control" name = "class_anti"  placeholder="e.g. Enterobacterales" value ="<?php echo $tax[5]; ?>">
                                        </div>                                  
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Order</label>
                                                <input type="text" class="form-control" name = "order_anti"  placeholder="e.g. " value ="<?php echo $tax[6]; ?>">
                                        </div>                                  
                                    </div>
        
                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Family</label>
                                                <input type="text" class="form-control" name = "family_anti"  placeholder="e.g. Enterobacteriaceae" value ="<?php echo $tax[7]; ?>">
                                        </div>                                  
                                    </div>
        
                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Genus</label>
                                                <input type="text" class="form-control" name = "genus_anti"  placeholder="e.g. Escherichia" value ="<?php echo $tax[8]; ?>">
                                        </div>                                  
                                    </div>
                                        
                                    <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Species</label>
                                                <input type="text" class="form-control" name ="species_anti"  placeholder="e.g. Escherichia coli" value ="<?php echo $tax[9]; ?>">
                                        </div>                                  
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Sub Species</label>
                                                <input type="text" class="form-control" name ="sub_species_anti"  placeholder="e.g. Escherichia coli" value ="<?php echo $tax[10]; ?>">
                                        </div>                                  
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Taxonomy Pathovar</label>
                                                <input type="text" class="form-control" name ="taxon_pathovar"  placeholder="e.g. Escherichia coli" value ="<?php echo $tax[11]; ?>">
                                        </div>                                  
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Taxonomy Serotype</label>
                                                <input type="text" class="form-control" name ="taxon_serotype"  placeholder="e.g. " value ="<?php echo $tax[12]; ?>">
                                        </div>                                  
                                    </div>

                            
                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Strain</label>
                                                <input type="text" class="form-control" name ="strain" placeholder="e.g. CMC" value ="<?php echo $tax[13]; ?>">
                                        </div>                                  
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Sub Strain</label>
                                                <input type="text" class="form-control" name ="sub_strain"  placeholder="e.g. CMC" value ="<?php echo $tax[14]; ?>">
                                        </div>                                  
                                    </div>

                                </div>
                            </div>
                          <!-- sixth tab -->
                            <div class="tab-pane" id="sequence">
                                <div class="row">

                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Mol Type</label>
                                                <input type="text" class="form-control" name ="mol_type"  placeholder="5 charactors only" value ="<?php echo $idaa[1]; ?>">
                                        </div>                                  
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Feat Type</label>
                                                <input type="text" class="form-control" name ="feat_type"  placeholder="e.g. 4762" value ="<?php echo $seq[5]; ?>">
                                        </div>                                  
                                    </div>
                                     <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">End 3</label>
                                                <input type="text" class="form-control" name = "end3"  placeholder="e.g. 3136" value ="<?php echo $seq[1]; ?>">
                                        </div>                                  
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">     
                                        <div class="form-group">
                                            <label for="anti" class="control-label">End 5</label>
                                                <input type="text" class="form-control" name ="end5"  placeholder="e.g. 4762" value ="<?php echo $seq[2]; ?>">
                                        </div>                                  
                                    </div>

                                   
                                
                                    <div class="col-sm-10 col-sm-offset-1">                                                                 
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Protein Sequence</label>
                                            <textarea class="form-control" rows="3" name ="protein_squence" value =""><?php echo $seq[4]; ?></textarea>
                                        </div>                                  
                                    </div>
        
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="anti" class="control-label">Nucleotide Sequence</label>
                                            <textarea class="form-control" rows="3" name = "nucleotide_sequence" value =""><?php echo $seq[3]; ?></textarea>
                                        </div>                                  
                                    </div>
                                </div>
                            </div>
                            
                             <!-- second tab -->                            
                            <div class="tab-pane" id="meta">
                                <div class="row">

                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Isoloation Site</label>
                                                <input type="text" class="form-control" name ="isolation_site"  placeholder="e.g. " value="<?echo $meta[3];?>"">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Serotyping Method</label>
                                                <input type="text" class="form-control" name ="serotyping_method"  placeholder="e.g. " value="<?echo $meta[4];?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Source Common Name</label>
                                                <input type="text" class="form-control" name ="source_common_name"  placeholder="e.g. Rectal swab" value="<?echo $meta[5];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">

                                            <?php
                                                // format output
                                              $day="";
                                                $mon="";
                                                $yea="";
                                            $data_string = explode('-', $meta[6]);
                                            if(count($data_string)==1){
                                                $day="";
                                                $mon="";
                                                $yea=$data_string[0];
                                            }
                                            if(count($data_string)==2){
                                                $day="";
                                                $mon="";
                                                $yea=$data_string[0];
                                            }
                                            if(count($data_string)==3){
                                                $day=$data_string[0];
                                                $mon=$data_string[1];
                                                $yea=$data_string[2];
                                            }

                                            ?>

                                            <label for="meta" class="control-label">Specimen Collection Date (DD-MON-YYYY)</label>
                                                <input type="text" class="form-control" name = "specimen_collection_date"  placeholder="e.g. 01/01/2015" value="<?echo $meta[6];?>">

                                                    <br><input type="text" class="" name = "specimen_collection_date_DD" style="width: 2em;" id="specimen_collection_date_DD"  placeholder="DD" onkeyup="validate_date('specimen_collection_date_DD')" value="<?echo $day?>">
                                                -&nbsp;<input type="text" class="" name = "specimen_collection_date_MON" style="width: 2em;" id="specimen_collection_date_MON"  placeholder="MON"onkeyup="validate_date('specimen_collection_date_MON')" value="<?echo $mon?>">
                                                -&nbsp;<input type="text" class="" name = "specimen_collection_date_YY" style="width: 4em;" id="specimen_collection_date_YY"  placeholder="YYYY" onkeyup="validate_date('specimen_collection_date_YY')" value="<?echo $yea?>" required>




                                                 <br>
                                                 <label for="meta" class="control-label" id="date_valid_label"style="color:#ccc"></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Location Country</label>
                                                <input type="text" class="form-control" name ="specimen_location_country"  placeholder="e.g. India" value ="<?echo $meta[7];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Location</label>
                                                <input type="text" class="form-control" name ="specimen_location"  placeholder="e.g. India" value ="<?echo $meta[8];?>">
                                        </div>
                                    </div>
                                        
                                    <div class="col-sm-10 col-sm-offset-1">                                     
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Location Lattitude</label>
                                                <input type="text" class="form-control" name ="specimen_location_lattitude"   placeholder="e.g. 39.09802374503313" value ="<?echo $meta[9];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Location Longitude</label>
                                                <input type="text" class="form-control" name = "specimen_location_longitude" placeholder="e.g. -77.19469801321412" value ="<?echo $meta[10];?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Source Age</label>
                                                <input type="text" class="form-control" name ="specimen_source_age"  placeholder="e.g. 32" value ="<?php echo $meta[11];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Development Stage</label>
                                                <input type="text" class="form-control" name ="specimen_dev_stage"  placeholder="e.g. 32" value ="<?echo $meta[12];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Source Disease</label>
                                                <input type="text" class="form-control" name ="specimen_source_disease"  placeholder="e.g. IBD" value ="<?php echo $meta[13];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Source Gender</label>
                                                <input type="text" class="form-control" name ="specimen_source_gender" placeholder="e.g. Male" value ="<?echo $meta[14];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Health Status</label>
                                                <input type="text" class="form-control" name ="specimen_health_status" placeholder="e.g. " value ="<?echo $meta[15];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Treatment</label>
                                                <input type="text" class="form-control" name ="specimen_treatment" placeholder="e.g. " value ="<?echo $meta[16];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Type</label>
                                                <input type="text" class="form-control" name ="speciment_type" placeholder="e.g. " value ="<?echo $meta[17];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Specimen Symptom</label>
                                                <input type="text" class="form-control" name ="speciment_symptom" placeholder="e.g. " value ="<?echo $meta[18];?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">                                 
                                        <div class="form-group">
                                            <label for="meta" class="control-label">Host</label>
                                                <input type="text" class="form-control" name ="speciment_Host" placeholder="e.g. " value ="<?echo $meta[19];?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="Hidden" id='a_count' name="a_count" value="1">
                        </div>
                        <div class="wizard-footer height-wizard">
                            <div class="pull-right">
                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd btn-sm' name='next' value='Next' />
                                <input type='submit' class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' name='method' value='Submit' />

                            </div>

                            <div class="pull-left">
                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' value='Previous' />
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
            </div>
        </div>
    </div><!-- end row -->
</div> <!--  big container -->

<!-- Select the correspond bottons -->
<?php
// $xxx= $t1!='false';
// pre populate the curated button

    if($ids[12]=='Curated'){
    $scp=<<<HAHA
        <script type="text/javascript">
        $("#curated").attr('checked', 'checked');
        </script>
HAHA;
        echo $scp;

    }else{
    $scp=<<<HAHA
        <script type="text/javascript">
        $("#non").attr('checked', 'checked');
        </script>
HAHA;
        echo $scp;
    }

    if(isset($tl)){    //var_dump('hey1');
        switch ($tl[1]) {
            case 'urgent':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#urgent").attr('checked', 'checked');
        </script>
HAHA;
        echo $scp;
        break;
            case'serious':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#serious").attr('checked', 'checked');
        </script>
HAHA;
        echo $scp;
        break;
            case'concern':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#concern").attr('checked', 'checked');
        </script>
HAHA;
        echo $scp;
        break;

            case 'none':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#none").attr('checked', 'checked');
        </script>
HAHA;
        echo $scp;
        break;
        }

    }else{
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#unknown").attr('checked', 'checked');
        </script>
HAHA;
    echo $scp;
    }
?>
<SCRIPT TYPE="text/javascript">
  function addRow(in_tbl_name,add_options,child_varnat_index)
  {
 
    if(add_options=='classi'){
        console.log("Adding Class to "+ in_tbl_name);
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
        let new_count = count;
        add_varin_btn.onclick=function(){addRow('vant_div'+new_count,'varant',new_count);};
        // console.log("creating with vant div "+count+" and add to add varant");
        add_varin_btn.value='+ Add Variant';
        let btn_syntax_div=document.createElement('div');
        btn_syntax_div.className='col-sm-10 col-sm-offset-1';
        btn_syntax_div.appendChild(add_varin_btn);
        child_container.appendChild(holder_div);
        child_container.appendChild(btn_syntax_div);
        // create remove button
        let removeMe=document.createElement("input");
        removeMe.type="Button";
        removeMe.className='red_buttond';
        removeMe.onclick=function(){hrcontain.remove();};
        removeMe.value='Delete Classification';
        // add remove button        
        coldiv1.appendChild(removeMe);
        coldiv1.appendChild(document.createElement("BR"));
        coldiv1.appendChild(document.createElement("BR"));
        coldiv1.appendChild(h);
        count++;

    }
    if(add_options=='varant'){
        console.log("adding to "+ in_tbl_name);
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
        // coldiv1.appendChild(hr);
        addRow_helper('','addon_va_fields'+v_count,'SNP','snp['+child_varnat_index+'][]','e.g.xxxx',v_count);
        let groudiv=addRow_helper('','addon_va_fields'+v_count,'Variant PubMed ID','v_plasmid['+child_varnat_index+'][]','e.g.xxxx',v_count);
        // create remove button
        let removeMe=document.createElement("input");
        removeMe.type="Button";
        removeMe.className='red_buttond';
        removeMe.onclick=function(){hrcontain.remove();};
        removeMe.value='Delete';
        // add remove button
        groudiv.appendChild(document.createElement("BR"));
        groudiv.appendChild(removeMe);
        groudiv.appendChild(hr);
    
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
        removeMe.onclick=function(){a_count--;hrcontain.remove();};
        removeMe.value='Delete';
        // add remove button
        groudiv.appendChild(document.createElement("BR"));
        groudiv.appendChild(removeMe);
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
<!-- data entry  -->
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

<?php  include 'includes/footerx.php';?>
    
