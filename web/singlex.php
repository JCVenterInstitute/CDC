<?php
$idd = $_GET["id"];
include 'includes/header.php';
include 'includes/config.inc.php';
?>
<?php require_once('dbconfig.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="css/gsdk-bootstrap-wizard.css" rel="stylesheet" />

<section class="main-container">
    <div class="container">
        <div class="row">
            <div class="main col-md-12">
                <p align="justify"> The detailed gene page displays identification, metadata, antibiogram, taxonomy, and sequence information for a selected AMR gene. Toggle between the different sets of information to display the associated data.</p>
                <h3 class="title" align="center"> Gene details of AMR gene ID # <b><?php echo "$idd" ?></b></h3>
            </div>
            <div class="main col-md-12">
                <div class="tabs-style-2">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#h2tab1" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Identity</a></li>
                        <li><a href="#h2tab2" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i>Classification</a></li>                        
                        <li><a href="#h2tab3" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i>Antibiogram</a></li>
                        <li><a href="#h2tab4" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Threat Level</a></li>
                        <li><a href="#h2tab5" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Taxonomy</a></li>             
                        <li><a href="#h2tab6" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Sequence</a></li>  
                         <li><a href="#h2tab7" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i>Sample Metadata</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <form class="form-horizontal" role="form">
                    <div class="tab-content">   
                    <?php 
                    # Search Identity Table
                    $sql = "SELECT ID, Gene_Symbol, Gene_Family, Gene_Class, Allele, EC_Number,Parent_Allele_Family,Parent_Allele, Source, Source_ID, Protein_ID, Protein_Name, Pubmed_IDs, HMM, Is_Active, Status, Created_Date, Modified_Date, Created_By, Modified_By FROM Identity WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $ids=mysql_fetch_array($query); 

                    # Search Classification Table
//                     $sql=$query="";
//                     $sql = "Select ise.ID, ise.Drug, ise.Drug_Class, ise.Drug_Family, ise.Mechanism_of_Action, ise.Identity_ID  FROM CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd'";
// #                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
//                     $query=mysql_query($sql);
//                     $va=mysql_fetch_array($query); 
                    #print_r ($va); echo "<br>"; #                  foreach ($va as $vax){ echo "**$vax | "; }

                    # Search Variant Table
                    $sql=$query="";
                    $sql = "Select iss.* FROM CDC.Variants iss,CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Classification_ID = ise.ID";
#                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $var=mysql_fetch_array($query); 
                    // print_r ($var); echo "<br>";  #      echo $var[1];

                    # Search Bioproject Table
                    $sql=$query="";
                    $sql = "select a.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID";
                    $query=mysql_query($sql);
                    $as=mysql_fetch_array($query); 
                    #print_r ($as); echo "<br>"; #      echo $as[7];

                    #anitibiogram
                    $ssql = "Select * 
                            From (select a.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID and a.Is_Reference=1) as tempt
                            LEFT Join Sample_Metadata on Sample_Metadata.ID=tempt.Sample_Metadata_ID";
                    $squery=mysql_query($ssql);
                    # Search antibiogram Table
//                     $sql=$query="";
//                     $sql = "select anti.* from CDC.Sample_Metadata sm LEFT JOIN CDC.Antibiogram anti ON sm.ID=anti.Sample_Metadata_ID where 1=1";
// #                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
//                     $query=mysql_query($sql);
//                     $anti=mysql_fetch_array($query); 
                    #print_r ($anti); echo "<br>"; #        echo $as[7];                    

                    # Search metadata Table
                    $sql=$query="";
                    $sql = "select sm.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a LEFT JOIN CDC.Sample_Metadata sm ON sm.ID=a.Sample_Metadata_ID where 1=1 and i.ID = '$idd' and i.ID=ise.Identity_ID and ise.ID=iss.Identity_Sequence_ID and iss.Assemly_ID=a.ID";
#                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $meta=mysql_fetch_array($query); 
                    #print_r ($meta); echo "<br>"; #        echo $meta[10];

                    # Search taxonomy Table
                    $sql=$query="";
                    $sql = "select t.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a,CDC.Taxonomy t where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID and t.ID=a.Taxonomy_ID";
#                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $tax=mysql_fetch_array($query); 
                    #print_r ($tax); echo "<br>"; #     echo $tax[1];
                                                
                    # Search sequence Table
                    $sql=$query="";
                    $sql = "select ise.* from CDC.Identity_Sequence ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd'";
#                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $seq=mysql_fetch_array($query); 
                    #print_r ($seq); #      echo $seq[3];

                    #die;  $sql=$query="";
                    $sql = "select tl.* from CDC.Identity ise, CDC.Threat_Level tl where ise.ID = '$idd' and ise.ID=tl.Identity_ID";
                    $query=mysql_query($sql);
                    $tl=mysql_fetch_array($query); 

/* Links 
<a href=https://www.ncbi.nlm.nih.gov/nuccore/' + data + ' target=_blank>'
https://www.ncbi.nlm.nih.gov/nuccore/NC_020088.1
https://www.ncbi.nlm.nih.gov/bioproject/?term=PRJNA298946
https://www.ncbi.nlm.nih.gov/biosample/?term=SAMN03703141
https://www.ncbi.nlm.nih.gov/pmc/articles/pmid/21933985/
https://www.ncbi.nlm.nih.gov/pmc/articles/PMC3478237/
*/                    
                    
                    ?>
                        <div class="tab-pane fade in active" id="h2tab1">
                            <div class="row">
                                <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-label">Gene Symbol: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[1]=="" ? "Not Available":  $ids[1]; ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-label">Gene Family</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[2]=="" ? "Not Available": $ids[2]; ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-label">Gene Class</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[3]=="" ? "Not Available": $ids[3];  ?></div>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-label">Allele</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[4]=="" ? "Not Available": $ids[4]; ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gene_aliases" class="col-sm-2 control-label">EC Number</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[5]=="" ? "Not Available": $ids[5];  ?></div>
                                    </div>
                                </div>
                               
                                  <div class="form-group">
                                    <label for="parent_allele_family" class="col-sm-2 control-label">Parent Allele Family</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[6]=="" ? "Not Available": $ids[6];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="parent_allele" class="col-sm-2 control-label">Parent Allele</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[7]=="" ? "Not Available": $ids[7];  ?></div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-label">Source:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[8]=="" ? "Not Available": $ids[8];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-label">Source ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[9]=="" ? "Not Available": $ids[9];  ?></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="protein_id" class="col-sm-2 control-label">Protein ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[10]=="" ? "Not Available": $ids[10];  ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-label">Protein Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[11]=="" ? "Not Available": $ids[11];  ?></div>
                                    </div>
                                </div>
   
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-label">PubMed ID </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[12]=="" ? "Not Available": $ids[12];  ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-label">HMM </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $ids[13]=="" ? "Not Available": $ids[13]; ?></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-label">Plasmid Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="input_field"><?php echo $as[8]=="" ? "Not Available": $as[8]; ?></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-label">Status: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <input type="radio" name="Stat" id='non' value="Non curated"> Non curated <input type="radio" name="Stat" id='curated' value="Curated"> Curated</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="h2tab2">
                           
                            <?php
                                $sql = "Select t.Drug, t.Drug_Class, t.Drug_Family, t.Mechanism_of_Action,iss.SNP,iss.PubMed_IDs  
FROM (Select ise.* FROM  CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd')as t
LEFT Join CDC.Variants iss on iss.Classification_ID=t.ID ";
                    $query=mysql_query($sql);
                    $second_tab_content[]="";
                    while($loop_varian_class=mysql_fetch_array($query)){
                        // $second_tab_content[]=
                        $tempContent=<<<TT
                         <div class="row">
                                <div class="form-group">
                                    <label for="drug_class" class="col-sm-2 control-label">Drug Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[0]'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="drug_class" class="col-sm-2 control-label">Drug Class</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[1]'>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="drug_family" class="col-sm-2 control-label">Drug Family</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" id="drug_family" value='$loop_varian_class[2]'>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="sub_drug_class" class="col-sm-2 control-label">Mechanism of Action</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" id="sub_drug_class" value='$loop_varian_class[3]'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="snp" class="col-sm-2 control-label">SNP</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" id="snp" value='$loop_varian_class[4]'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-label">Variant PubMed ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" id="protein_name" value='$loop_varian_class[5]'>
                                    </div>
                                </div>

                         </div>
TT;
                         $second_tab_content[]=$tempContent;
                    }   
                    array_shift($second_tab_content);
                    echo implode('<hr style="border-color:#aaa;">',$second_tab_content);

                            ?>
                        </div>
                        <div class="tab-pane fade" id="h2tab3">
                         
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
                         <div class="form-group">
                                    <label for="antibiotic" class="col-sm-2 control-label">Antibiotic</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" id="antibiotic" value='$loop_varian_class[1]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Drug Symbol</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[2]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Laboratory Typing Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[3]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Laboratory Typing Platform</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[4]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Laboratory Typing Method Version or Reagent</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[5]'>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Measurement</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[6]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Measurement Sign</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[7]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Measurement Units</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[8]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Resistance Phenotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[9]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Testing Standard</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[10]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Vendor</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[11]'>
                                    </div>                                  
                                </div>  


                            </div>

TT;
                        $third_tab_content[]=$tempContent;
                    } array_shift($third_tab_content);
                    echo implode('<hr style="border-color:#aaa;">',$third_tab_content);
                                ?>
                                <!-- loop thought A -->

                        </div>
                     
                        <div class="tab-pane fade" id="h2tab4">
                            <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1">
                                      	  	<div style="float:center;">
                                                <p><input type="radio" name="treatlevel" id='' value="urgent"> Urgent<br></p>
                                            </div>
                                    
                                             <div style="float:center;">
                                                <p><input type="radio" name="treatlevel" id='serious' value="serious"> Serious<br></p>
                                            </div>
                                             <div style="float:center;">
                                                <p><input type="radio" name="treatlevel" id='concern' value="concern"> Concern<br></p>
                                            </div>
                                             <div style="float:center;">
                                                <p><input type="radio" name="treatlevel" id='unknown' value="unknown" checked="checked"> Unknown<br></p>
                                            </div>
                                             <div style="float:center;">
                                                <p><input type="radio" name="treatlevel" id='none' value="none"> None<br></p>
                                            </div>
                                   </div>     
                            </div>
                        </div>

                        <div class="tab-pane fade" id="h2tab5">
                            <div class="row">
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Taxon ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[1]; ?>">
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Kingdom</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[2]; ?>">
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Phylum</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[3]; ?>">
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Class</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[5]; ?>">
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Order</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value="<?php echo $tax[6]; ?>">
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Family</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[7]; ?>">
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Genus</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value="<?php echo $tax[8]; ?>">
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Species</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value="<?php echo $tax[9]; ?>">
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Sub Species</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[10]; ?>">
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Pathovar</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[11]; ?>">
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Serotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[12]; ?>">
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Strain</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[13]; ?>">
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Sub strain</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $tax[14]; ?>">
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="h2tab6">
                            <div class="row">

                                <?php
                                if(!isset($ids[8])||($ids[8])==""){

                                    $ck_protein_id=<<<HAHA
                                      <div class="form-group">
                                    <label for="source" class="col-sm-2 control-label">Source:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value=$ids[6]>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-label">Source ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value=$ids[7]>
                                    </div>
                                </div>
HAHA;
                                echo $ck_protein_id;
                                }else {

                                ?>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Protein ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" id="ck_protein_id" value="<?php echo $ids[8]; ?>">
                                    </div>
                                </div>
                                <?php
                                }
                                ?>

                                <div id='additional_info'>
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Feat Type</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $seq[5]; ?>">
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">End5</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value="<?php echo $seq[1]; ?>">
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">End3</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value="<?php echo $seq[2]; ?>">
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Protein Sequence</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <textarea class="form-control input-control-display" rows="3"><?php echo $seq[4]; ?></textarea>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Nucleotide Sequence</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <textarea class="form-control input-control-display" rows="3"><?php echo $seq[3]; ?></textarea>                                    </div>                                 
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="h2tab7">
                            <?php
                                
                    $seven_tab_content[]="";
                    while($loop_varian_class=mysql_fetch_array($squery)){
                        
                        $tempContent2=<<<TT
                         <div class="row">
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Isolation Site</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[16]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Serotyping Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[17]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Serotyping Common Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[18]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Collection Date</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[19]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Location Country</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[20]'>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Location</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[21]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Location Latitude</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[22]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Location Longitude</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display"  value='$loop_varian_class[23]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Source Age</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[24]'>
                                    </div>                                  
                                </div>
                            <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Source Developmental Stage</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[25]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Source Disease</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[26]'>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Source Gender</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[27]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Health Status</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[28]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Treatment</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[29]'>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Specimen Type</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[30]'>
                                    </div>                                  
                                </div>     
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Symptom</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[31]'>
                                    </div>                                  
                                </div>  
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-label">Host</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-control-display" value='$loop_varian_class[32]'>
                                    </div>                                  
                                </div>                            
                            </div>
TT;
                         $seven_tab_content[]=$tempContent2;
                    }   
                    array_shift($seven_tab_content);
                    // echo implode('<hr style="border-color:#aaa;">',$seven_tab_content);
                     echo implode('<hr style="border-color:#aaa;">',$seven_tab_content);


                            ?>
                            <!-- </div> -->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>   
</section>
<?php

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

    if(isset($t1)){
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
 <script type="text/javascript">
$(document).ready(function() {
    $('.input-control-display').each(function(){
        if(!this.value.trim()){
             $(this).parent().parent().hide();
        }});
});
// create div or field if the protein id is empty
// if((document.getElementById('ck_protein_id').value).trim()==""){


// // <div class="form-group">
// //<label for="anti" class="col-sm-2 control-label">Protein ID</label><div class="col-sm-1"></div>
// //<div class="col-sm-9">
// //<input type="text" class="form-control input-control-display" id="ck_protein_id" value="<?php echo $ids[8]; ?>">
// //</div>
// //</div>

//     let dc = document.createElement('ck_protein_id');
//     dc.className = "form-group";

//     let label = document.createElement('label');
//     label.className = "col-sm-2 control-label";
</script> 
<?php include 'includes/footer.php';?>


