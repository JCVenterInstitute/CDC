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
                    $sql = "SELECT ID, Gene_Symbol, Gene_Family, Gene_Class, Allele, EC_Number,Parent_Allele_Family,Parent_Allele, Source, Source_ID, Protein_ID, Protein_Name, Pubmed_IDs, HMM, Is_Active, Status,Gene_Alternative_Names,Protein_Alternative_Names FROM Identity WHERE ID = '$idd'"; 
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
                    $sql = "Select iss.ID, iss.SNP, iss.PubMed_IDs, iss.Identity_Sequence_ID, iss.Classification_ID, iss.Is_Active FROM CDC.Variants iss,CDC.Classification ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Classification_ID = ise.ID";
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
                    ?>
                        <div class="tab-pane fade in active" id="h2tab1">
                            <div class="row">
                                <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Gene Symbol: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="control-label col-sm-2  input_field"><?php echo $ids[1]; ?></div>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Gene Alternative Names: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="control-label col-sm-2  input_field"><?php echo $ids[16]; ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-labelx">Gene Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[2];?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-labelx">Gene Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[3]; ?></div>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-labelx">Allele:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[4]; ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gene_aliases" class="col-sm-2 control-labelx">EC Number:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[5];  ?></div>
                                    </div>
                                </div>
                               
                                  <div class="form-group">
                                    <label for="parent_allele_family" class="col-sm-2 control-labelx">Parent Allele Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[6];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="parent_allele" class="col-sm-2 control-labelx">Parent Allele:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[7];  ?></div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[8];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[9];  ?></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="protein_id" class="col-sm-2 control-labelx">Protein ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><a class="input_link" href="https://www.ncbi.nlm.nih.gov/protein/<?php echo $ids[10];?>"  target="_blank"><?php echo $ids[10];  ?></a></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Protein Name:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[11];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Protein Alternative  Names: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="control-label col-sm-2  input_field"><?php echo $ids[17]; ?></div>
                                    </div>
                                </div>
   
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">PubMed ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><a class="input_link" href="https://www.ncbi.nlm.nih.gov/pubmed/<?php echo $ids[12];?>"  target="_blank"><?php echo $ids[12];  ?></a></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">HMM: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[13]; ?></div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">BioProject ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"<a class="input_link" href="https://www.ncbi.nlm.nih.gov/bioproject/<?php echo $as[6];?>"  target="_blank"><?php echo $as[6];  ?></a></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Plasmid Name:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $as[8]; ?></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Status: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-2 control-label">
                                       <input type="radio" name="Stat" id='non' value="Non curated"> Non curated 
                                       <input type="radio" name="Stat" id='curated' value="Curated"> Curated
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="h2tab2">
                           
                            <?php
                                $sql = "Select t.Drug, t.Drug_Class, t.Drug_Family, t.Mechanism_of_Action, t.ID
                                        FROM  CDC.Classification t,CDC.Identity i where i.ID=t.Identity_ID and i.ID = '$idd'";
                    $query=mysql_query($sql);
                    $second_tab_content[]="";
                    while($loop_varian_class=mysql_fetch_array($query)){
                        // first define all fields then echo
                        $tempContent=<<<TT
                         <div class="row">
                                <div class="form-group">
                                 <h3>Classificiation:</h3>
                                    <label for="drug_class" class="col-sm-2 control-labelx">Drug:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field">$loop_varian_class[0]</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="drug_class" class="col-sm-2 control-labelx">Drug Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field">$loop_varian_class[1]</div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="drug_family" class="col-sm-2 control-labelx">Drug Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[2]</div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="sub_drug_class" class="col-sm-2 control-labelx">Mechanism of Action:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field">$loop_varian_class[3]</div>
                                    </div>
                                </div>
                                 <h4 class="col-sm-2 control-label">Variants:</h4>
                                
                               
TT;
                             $sql_child="SELECT va.SNP,va.PubMed_IDs FROM CDC.Variants va WHERE va.Classification_ID=$loop_varian_class[4]";
                                      $query_child=mysql_query($sql_child);
                                      while ($loop_va_child=mysql_fetch_array($query_child)) {
                                               $tempContent2=<<<HH
                                <div class="form-group">
                                    <label for="snp" class="col-sm-2 control-labelx">SNP:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$loop_va_child[0]</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Variant PubMed ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field">$loop_va_child[1]</div>
                                    </div>
                                </div>
HH;
                            }
                            if(isset($tempContent2)){
                         $second_tab_content[]=$tempContent.'<fieldset class="col-sm-10 col-sm-offset-1"" >'.$tempContent2."</div>
                                </fieldset>";
                            }else{
                         $second_tab_content[]=$tempContent."</div></fieldset>";
                            }
                    }   
                    if(count($second_tab_content)==1){

                         $tempContent=<<<TT
                         <div class="row">
                                <div class="form-group">
                                 <h3>Classificiation:</h3>
                                    <label for="drug_class" class="col-sm-2 control-labelx">Drug:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="drug_class" class="col-sm-2 control-labelx">Drug Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="drug_family" class="col-sm-2 control-labelx">Drug Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="sub_drug_class" class="col-sm-2 control-labelx">Mechanism of Action:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>
                                </div>
                                 <h4 >Variants:</h4>
                                <fieldset class="col-sm-10 col-sm-offset-1"" >
                                     <div class="form-group">
                                    <label for="snp" class="col-sm-2 control-labelx">SNP:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Variant PubMed ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>
                                </div>
                                </div></fieldset>
                               
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
                                    <label for="antibiotic" class="col-sm-2 control-labelx">Antibiotic</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[1]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Drug Symbol</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"> $loop_varian_class[2]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[3]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Platform</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"> $loop_varian_class[4]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method Version or Reagent</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"> $loop_varian_class[5]</div>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[6]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Sign</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[7]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Units</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[8]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Resistance Phenotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"> $loop_varian_class[9]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Testing Standard</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"> $loop_varian_class[10]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Vendor</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"> $loop_varian_class[11]</div>
                                    </div>                                  
                                </div>  
                            </div>

TT;
                        
                        $third_tab_content[]=$tempContent;
                    }
                    if(count($third_tab_content)==1){
                         $tempContent=<<<TT
                           <div class="row">
                         <div class="form-group">
                                    <label for="antibiotic" class="col-sm-2 control-labelx">Antibiotic</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Drug Symbol</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Platform</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method Version or Reagent</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Sign</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Units</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Resistance Phenotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Testing Standard</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Vendor</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>  
                            </div>

TT;
                        $third_tab_content[]=$tempContent;
                    }


                     array_shift($third_tab_content);
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
                                    <label for="anti" class="col-sm-2 control-labelx">Taxon ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><a class="input_link" href="https://www.ncbi.nlm.nih.gov/taxonomy/<?php echo $tax[1];?>"  target="_blank"><?php echo $tax[1];  ?></a></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Kingdom:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><?php echo $tax[2]; ?></div>
                                       
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Phylum:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-2 control-label input_field"><?php echo $tax[3]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Bacterial BioVar:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-2 control-label input_field"><?php echo $tax[4]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Taxonomy Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-2 control-label input_field"><?php echo $tax[5]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Order:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-2 control-label input_field"><?php echo $tax[6]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-2 control-label input_field"><?php echo $tax[7]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Genus</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><?php echo $tax[8]; ?></div>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Species</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><?php echo $tax[9]; ?></div>
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Sub Species</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-2 control-label input_field"><?php echo $tax[10]; ?></div>
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Pathovar</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-2 control-label input_field"><?php echo $tax[11]; ?></div>
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-2 control-label input_field"><?php echo $tax[12]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Strain:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><?php echo $tax[13] ; ?></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Sub strain:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><?php echo $tax[14] ; ?></div>
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
                                    <label for="source" class="col-sm-2 control-labelx">Source:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$ids[6]</div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$ids[7]</div>
                                    </div>
                                </div>
HAHA;
                                echo $ck_protein_id;
                                }else {

                                ?>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Protein ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"><?php echo $ids[8]; ?></div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>

                                <div id='additional_info'>
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Feat Type</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                            <div class="col-sm-2 control-label input_field"><?php echo  $seq[5]; ?></div>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">End5</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-2 control-label input_field"><?php echo  $seq[1]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">End3</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-2 control-label input_field"><?php echo  $seq[2]; ?></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Protein Sequence</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        

                                        <textarea class="form-control input-control-display" rows="3"><?php echo $seq[4]; ?></textarea>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Nucleotide Sequence</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <textarea class="form-control input-control-display" rows="3"><?php echo $seq[3]; ?></textarea>  
                                    </div>                                 
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
                          <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Source</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$loop_varian_class[14]</div>
                                    </div>                                  
                                </div>
                                    <label for="anti" class="col-sm-2 control-labelx">Source ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">
                         <a class="input_link" href="https://www.ncbi.nlm.nih.gov/biosample/$loop_varian_class[15]"  target="_blank">$loop_varian_class[15]</a>
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Isolation Site</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$loop_varian_class[16]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotyping Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$loop_varian_class[17]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotyping Common Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[18]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Collection Date</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[19]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Country</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[20]</div> 
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[21]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Latitude</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[22]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Longitude</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[23]</div></div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Age</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[24]</div></div>                                  
                                </div>
                            <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Developmental Stage</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field">$loop_varian_class[25]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Disease</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[26]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Gender</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[27]</div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Health Status</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[28]</div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Treatment</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[29]</div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Type</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[30]</div></div>                                  
                                </div>     
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Symptom</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[31]</div></div>                                  
                                </div>  
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Host</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[32]</div></div>                                  
                                </div>                            
                            </div>
TT;
                         $seven_tab_content[]=$tempContent2;
                    }   
                    if(!isset($tempContent2)){
                          $tempContent2=<<<TT
                         <div class="row">
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Isolation Site</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotyping Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotyping Common Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Collection Date</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Country</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div> 
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Latitude</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Longitude</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Age</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
                                </div>
                            <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Developmental Stage</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-2 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Disease</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field">$loop_varian_class[26]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Gender</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Health Status</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_fieldx"></div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Treatment</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Type</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
                                </div>     
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Symptom</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
                                </div>  
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Host</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"></div></div>                                  
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
<!-- if the field is empty replace it with NA -->
 <script type="text/javascript">
$(document).ready(function() {
    $('.input_field').each(function(){
       if(!this.innerHTML||this.innerHTML==' '){
        this.innerHTML="<span style='color: #bbb;'>-</span>";}});
    $('.input_link').each(function(){
       if(!this.innerHTML||this.innerHTML==' '){
        this.innerHTML="<span style='color: #bbb;'>-</span>";
    }});


});

</script> 
<?php include 'includes/footer.php';?>


