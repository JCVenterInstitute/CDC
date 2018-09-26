<?php
$idd = $_GET["id"];
include 'includes/header.php';
include 'includes/config.inc.php';
?>
<?php require_once('dbconfig.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php 
// var_dump($_SESSION['userID']);
if(!isset($_SESSION['userID'])){
  echo '<br><br><br><br><h2 align="center">Please Log in as an admin</h5><br><br><br><br><br><br><br>';
include 'includes/footerx.php';
  die;
}
// var_dump($_POST);



?>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="css/gsdk-bootstrap-wizard.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"> -->
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

</style>

<script type="text/javascript" class="init">



// Load this page with the table data.
// scripts/data.json
$(document).ready(function() {
  function escapeRegExp_helper(str){
    let regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
      if(str.trim()==''){
        // console.log('s');
        return;
      }
      if(!regex.test(str)){
         // console.log('here');
        return '*'+str+'*';
      }
      return '"*'+str+'*"';
  }
  var btn_count = 0;
var primer = <?php  if (isset($_GET['id'])){
                                    echo json_encode($_GET['id'], JSON_HEX_TAG);
                                }else{
                                    echo json_encode('', JSON_HEX_TAG);
                                };?>;
  function escapeRegExp(str) {
   
    if (str.trim()==''){
        console.log("s");
        return '';
    }
    
      rt_string='(Sample_Metadata_ID: '+escapeRegExp_helper(str)+' OR Taxonomy_ID: '+escapeRegExp_helper(str)
    + ' OR Taxon_Kingdom: '+escapeRegExp_helper(str)+ ' OR Taxon_Phylum: '+escapeRegExp_helper(str)+' OR Taxon_Class:'+escapeRegExp_helper(str)+
    ' OR Taxon_Order:'+escapeRegExp_helper(str)+' OR Taxon_Family:'+escapeRegExp_helper(str)+
    ' OR Taxon_Genus:'+escapeRegExp_helper(str)+
    ' OR Taxon_Species:'+escapeRegExp_helper(str)+
    ' OR Taxon_Sub_Species:'+escapeRegExp_helper(str)+
    ' OR Taxon_Strain:'+escapeRegExp_helper(str)+
    ' OR Source:'+escapeRegExp_helper(str)+
    ' OR Isolation_site:'+escapeRegExp_helper(str)+
    ' OR Serotyping_Method:'+escapeRegExp_helper(str)+
    ' OR Source_Common_Name:'+escapeRegExp_helper(str)+
    ' OR Specimen_Collection_Date:'+escapeRegExp_helper(str)+
    ' OR Specimen_Collection_Location_Country:'+escapeRegExp_helper(str)+
    ' OR Specimen_Collection_Location_Latitude:'+escapeRegExp_helper(str)+
    ' OR Specimen_Collection_Location_Longitude:'+escapeRegExp_helper(str)+
    ' OR Specimen_Source_Age:'+escapeRegExp_helper(str)+
    ' OR Specimen_Source_Developmental_Stage:'+escapeRegExp_helper(str)+
    ' OR Specimen_Source_Disease:'+escapeRegExp_helper(str)+
    ' OR Specimen_Source_Gender:'+escapeRegExp_helper(str)+
    ' OR Health_Status:'+escapeRegExp_helper(str)+
    ' OR Treatment:'+escapeRegExp_helper(str)+
    ' OR Host:'+escapeRegExp_helper(str)+
    ' OR Symptom:'+escapeRegExp_helper(str)+')';

  return rt_string;

  // return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|\&|\!|\"|\~|\:|\']/g, "\\$&");
}
var btn_search_me = '0';


    var table = $('#example').DataTable({
        

        // "processing":    true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
                               // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        "serverSide":    true, 
         "scrollY": 600,
        "scrollX": true,
     // "searching":false,
        lengthMenu: [
                    [ 10, 15, 25 ],
                    [ '10', '15', '25' ]
                    ],
                    // make a ajax call to solr server to get all the data that matches search field

        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "http://cdc-1.jcvi.org:8983/solr/tax_sm_anti_relation/select",
                 "type":"POST",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'Identity_ID:'+primer,
                                                            'filter':escapeRegExp(data.search.value)
                                                          ,'sort':(data.columns[data.order[0].column].data==null? 'id asc' : data.columns[data.order[0].column].data+' '+data.order[0].dir) //if order is null use id asc order
                                                          ,'rows':data.length}),
                 "dataType": "jsonp",
                 "jsonp":"json.wrf",
                 "success": function(json) {
                   var o = {
                     recordsTotal: json.response.numFound,
                     recordsFiltered: json.response.numFound,
                     data: json.response.docs
                   };        
                   callback(o);

                   // need to pass the varible to next table

                    // create export button. 
               
                 }
               } );
        },
        "columns": [
            { "data": "Sample_Metadata_ID",
              "render": function(data, type, row, meta){
                 // class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal
                if(type === 'display'){
                        
                    data = '<a href=singlex_antibiogram.php?sm_id='+data+' target=_blank >'+data+'</a>';
                }
                return data;
                }
            },            
            { "data": "Taxon_ID" ,
              "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                } 
            },
            { "data": "Taxon_Kingdom" ,
              "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                } 
            },
            { "data": "Taxon_Phylum",
              "render": function(data, type, row, meta){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                    } 
            },            
            { "data": "Taxon_Class",
              "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    }
                    return data;
                    } 
            },            
            { "data": "Taxon_Order",
              "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        data= "";    
                      }
                    if(type === 'display'){
                        let new_d=data.split(',');
                        data=new_d.map(x=>x).join();;
                    } 
                        return data;
                    }  
            },
            { "data": "Taxon_Family",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return full.Taxon_Genus+ "  "+ full.Taxon_Species;
                     }
            },
                { "data": "Taxon_Genus",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Taxon_Species",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Taxon_Sub_Species",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Taxon_Strain",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Taxon_Sub_Strain",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Source_ID",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Isolation_site",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Serotyping_Method",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Source_Common_Name",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Specimen_Collection_Date",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Specimen_Collection_Location_Country",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Specimen_Collection_Location",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Specimen_Collection_Location_Longitude",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Specimen_Collection_Location_Latitude",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Specimen_Source_Age",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Specimen_Source_Developmental_Stage",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Specimen_Source_Disease",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Specimen_Source_Gender",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Health_Status",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                  { "data": "Treatment",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                       { "data": "Specimen_Type",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                       { "data": "Symptom",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                       { "data": "Host",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            }
             
        ],
        // "order": [[ 0, 'desc' ], [ 1, 'desc' ]] // will not work on serverside fetching
    });
// second table 



 var table2 = $('#antiTable').DataTable({
        // "processing":    true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
                               // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        "serverSide":    true, 
         "scrollY": 600,
        "scrollX": true,

     // "searching":false,
        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "http://cdc-1.jcvi.org:8983/solr/many_antibiogram_singlex/select",
                 "type":"POST",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'Sample_Metadata_ID:'+ btn_search_me   //escapeRegExp(data.search.value) 
                                                          ,'sort':(data.columns[data.order[0].column].data==null? 'id asc' : data.columns[data.order[0].column].data+' '+data.order[0].dir)
                                                          ,'rows':data.length}),
                 "dataType": "jsonp",
                 "jsonp":"json.wrf",
                 "success": function(json) {
                   var o = {
                     recordsTotal: json.response.numFound,
                     recordsFiltered: json.response.numFound,
                     data: json.response.docs
                   };        
                   callback(o);
                   // console.log(escapeRegExp(data.search.value).trim()=="(+ )");
                    // create export button. 

                    // alert(o.data.search.value);
               
                 }
               } );
        },
        "columns": [
            { "data": "id",
              "render": function(data, type, row, meta){
                 // class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal
                if(type === 'display'){
                        
                    data = '<button class="btn btn-warning">'+data+'</button>';
                }
                return data;
                }
            },            
            { "data": "Antibiotic" ,
              "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                } 
            },
            { "data": "Drug_Symbol" ,
              "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                } 
            },
            { "data": "Laboratory_Typing_Method",
              "render": function(data, type, row, meta){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                    } 
            },            
            { "data": "Laboratory_Typing_Method_Version_or_Reagent",
              "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    }
                    return data;
                    } 
            },            
            { "data": "Laboratory_Typing_Platform",
              "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        data= "";    
                      }
                    if(type === 'display'){
                        let new_d=data.split(',');
                        data=new_d.map(x=>x).join();;
                    } 
                        return data;
                    }  
            },
            { "data": "Measurement",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return full.Taxon_Genus+ "  "+ full.Taxon_Species;
                     }
            },
                { "data": "Measurement_Sign",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Measurement_Units",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Resistance_Phenotype",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Testing_Standard",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            },
                { "data": "Vendor",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                     }
            }
             
        ],
       
    });
   

} );

</script>
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
                        <li><a href="#h2tab7" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i>Metadata</a></li>
                        <li><a href="#h2tab8" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i>Hit</a></li>
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
                    $sql = "select a.ID,Is_Reference,a.Sample_Metadata_ID,a.Source,a.Source_ID,a.PubMed_IDs,a.BioProject_ID,a.Taxonomy_ID,a.Plasmid_Name,a.Created_Date,a.Modified_Date,a.Created_By,a.Modified_By from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID";
                    $query=mysql_query($sql);
                    $as=mysql_fetch_array($query); 
                    #print_r ($as); echo "<br>"; #      echo $as[7];

                    #anitibiogram
                    $ssql = "Select tempt.ID,tempt.Is_Reference,tempt.Sample_Metadata_ID,tempt.Source,tempt.Source_ID,tempt.PubMed_IDs,tempt.BioProject_ID,tempt.Taxonomy_ID,tempt.Plasmid_Name,tempt.Created_Date,tempt.Modified_Date,tempt.Created_By,tempt.Modified_By,Sample_Metadata.ID,Sample_Metadata.Source,Sample_Metadata.Source_ID,Sample_Metadata.Isolation_site,Sample_Metadata.Serotyping_Method,Sample_Metadata.Source_Common_Name,Sample_Metadata.Specimen_Collection_Date,Sample_Metadata.Specimen_Collection_Location_Country,Sample_Metadata.Specimen_Collection_Location,Sample_Metadata.Specimen_Collection_Location_Latitude,Sample_Metadata.Specimen_Collection_Location_Longitude,Sample_Metadata.Specimen_Source_Age,Sample_Metadata.Specimen_Source_Developmental_Stage,Sample_Metadata.Specimen_Source_Disease,Sample_Metadata.Specimen_Source_Gender,Sample_Metadata.Health_Status,Sample_Metadata.Treatment,Sample_Metadata.Specimen_Type,Sample_Metadata.Symptom,Sample_Metadata.Host,Sample_Metadata.Created_Date,Sample_Metadata.Modified_Date,Sample_Metadata.Created_By,Sample_Metadata.Modified_By
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
//                     $sql=$query="";
//                     $sql = "select sm.* from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a LEFT JOIN CDC.Sample_Metadata sm ON sm.ID=a.Sample_Metadata_ID where 1=1 and i.ID = '$idd' and i.ID=ise.Identity_ID and ise.ID=iss.Identity_Sequence_ID and iss.Assemly_ID=a.ID";
// #                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
//                     $query=mysql_query($sql);
//                     $meta=mysql_fetch_array($query); 
                    #print_r ($meta); echo "<br>"; #        echo $meta[10];

                    # Search taxonomy Table
                    $sql=$query="";
                    $sql = "select t.ID,t.Taxon_ID,t.Taxon_Kingdom,t.Taxon_Phylum,t.Taxon_Bacterial_BioVar,t.Taxon_Class,t.Taxon_Order,t.Taxon_Family,t.Taxon_Genus,t.Taxon_Species,t.Taxon_Sub_Species,t.Taxon_Pathovar,t.Taxon_Serotype,t.Taxon_Strain,t.Taxon_Sub_Strain,t.Created_Date,t.Modified_Date,t.Created_By,t.Modified_By
                     from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a,CDC.Taxonomy t where i.ID=ise.Identity_ID and i.ID = '$idd' and iss.Identity_Sequence_ID=ise.ID and a.ID=iss.Assemly_ID and t.ID=a.Taxonomy_ID";
#                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $tax=mysql_fetch_array($query); 
                    #print_r ($tax); echo "<br>"; #     echo $tax[1];
                                                
                    # Search sequence Table
                    $sql=$query="";
                    $sql = "select ise.ID,ise.End3,ise.End5,ise.NA_Sequence,ise.AA_Sequence,ise.Feat_Type,ise.Identity_ID,ise.Created_Date,ise.Modified_Date,ise.Created_By,ise.Modified_By
                             from CDC.Identity_Sequence ise,CDC.Identity i where i.ID=ise.Identity_ID and i.ID = '$idd'";
#                    $sql = "SELECT * FROM Variants WHERE ID = '$idd'"; 
                    $query=mysql_query($sql);
                    $seq=mysql_fetch_array($query); 
                    #print_r ($seq); #      echo $seq[3];

                    #die;  $sql=$query="";
                    $sql = "select tl.ID,tl.Level,tl.Taxonomy_ID,tl.Identity_ID,tl.Created_Date from CDC.Identity ise, CDC.Threat_Level tl where ise.ID = '$idd' and ise.ID=tl.Identity_ID";
                    $query=mysql_query($sql);
                    $tl=mysql_fetch_array($query); 
                    ?>
                        <div class="tab-pane fade in active" id="h2tab1">
                            <div class="row">
                                <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Gene Symbol: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="control-label col-sm-9  input_field"><?php echo $ids[1]; ?></div>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Gene Alternative Names: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="control-label col-sm-9  input_field"><?php echo $ids[16]; ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-labelx">Gene Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[2];?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-labelx">Gene Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[3]; ?></div>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="allele" class="col-sm-2 control-labelx">Allele:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[4]; ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="parent_allele_family" class="col-sm-2 control-labelx">Parent Allele Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[6];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="parent_allele" class="col-sm-2 control-labelx">Parent Allele:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[7];  ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Product/Protein Name:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[11];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="gene_symbol" class="col-sm-2 control-labelx">Product/Protein Alternative  Names: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="control-label col-sm-9  input_field"><?php echo $ids[17]; ?></div>
                                    </div>
                                </div>                               
                                <div class="form-group">
                                    <label for="protein_id" class="col-sm-2 control-labelx">Protein ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><a class="input_link" href="https://www.ncbi.nlm.nih.gov/protein/<?php echo $ids[10];?>"  target="_blank"><?php echo $ids[10];  ?></a></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gene_aliases" class="col-sm-2 control-labelx">EC Number:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[5];  ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">PubMed ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">
                                            <!-- use php to split the pubmedids ?-->
                                      <?php
                                        if(trim($ids[12])!=''){
                                            $pub_ids_tmp =explode(',', $ids[12]);
                                            foreach ($pub_ids_tmp as $value) {
                                            $pub_ids_tmp_array[]='<a class="input_link" href="https://www.ncbi.nlm.nih.gov/pubmed/'.$value.'"  target="_blank">'.$value.'</a>';
                                            }
                                            $pub_ids_tmp_array=implode(',', $pub_ids_tmp_array);
                                            echo $pub_ids_tmp_array;
                                        }
                                      ?>    
                                        </div>
                                    </div>
                                </div>                                
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[8];  ?></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php if($ids[8] =="GenBank"){?> <a class="input_link" href="https://www.ncbi.nlm.nih.gov/nuccore/<?php echo $ids[9];?>" target="_blank"><?php echo $ids[9]; echo "</a>"; } else {echo $ids[9];}  ?></div>
                                    </div>
                                </div>
                                <!-- hide HMM  -->
                          <!--       <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">HMM: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[13]; ?></div>
                                    </div>
                                </div> -->                    

                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Status: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-2 control-label">
                                       <?php echo $ids[15]; ?>
                                    </div>
                                </div>

                             <!--     <div class="form-group">
                                    <label for="Is_Active" class="col-sm-2 control-labelx">Active: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-2 control-label">
                                       <input type="radio" name="Is_Active" id='active_' value='1'> Yes 
                                       <input type="radio" name="Is_Active" id='non_active_' value="0"> No
                                    </div>
                                </div> -->

                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="h2tab2">
                           
                            <?php
                                $sql = "Select t.Drug, t.Drug_Class, t.Drug_Family, t.Mechanism_of_Action, t.ID
                                        FROM  CDC.Classification t,CDC.Identity i where i.ID=t.Identity_ID and i.ID = '$idd' and t.Is_Active=1 ";

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
                                         <div class="col-sm-9 control-label input_field">$loop_varian_class[0]</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="drug_class" class="col-sm-2 control-labelx">Drug Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field">$loop_varian_class[1]</div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="drug_family" class="col-sm-2 control-labelx">Drug Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[2]</div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="sub_drug_class" class="col-sm-2 control-labelx">Mechanism of Action:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field">$loop_varian_class[3]</div>
                                    </div>
                                </div>
                                 <h4 class="col-sm-2 control-label">Variants:</h4>
                                
                               
TT;
                 $sql_child="SELECT va.SNP,va.PubMed_IDs FROM CDC.Variants va WHERE va.Classification_ID=$loop_varian_class[4] and va.Is_Active=1";
                                      $query_child=mysql_query($sql_child);
                                      while ($loop_va_child=mysql_fetch_array($query_child)) {
                                        // split pubmed id
                                        if(trim($loop_va_child[1])!=''){
                                            $v_pub_ids_tmp =explode(',', $loop_va_child[1]);
                                            foreach ($v_pub_ids_tmp as $value) {
                                            $v_pub_ids_tmp_array[]='<a class="input_link" href="https://www.ncbi.nlm.nih.gov/pubmed/'.$value.'"  target="_blank">'.$value.'</a>';
                                            }
                                            $v_pub_ids_tmp_array=implode(",", $v_pub_ids_tmp_array);
                                        }

                                               $tempContent2=<<<HH
                                <div class="form-group">
                                    <label for="snp" class="col-sm-2 control-labelx">SNP:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$loop_va_child[0]</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Variant PubMed ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field">$pub_ids_tmp_array</div>
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
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="drug_class" class="col-sm-2 control-labelx">Drug Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="drug_family" class="col-sm-2 control-labelx">Drug Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="sub_drug_class" class="col-sm-2 control-labelx">Mechanism of Action:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>
                                </div>
                                 <h4 >Variants:</h4>
                                <fieldset class="col-sm-10 col-sm-offset-1"" >
                                     <div class="form-group">
                                    <label for="snp" class="col-sm-2 control-labelx">SNP:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Variant PubMed ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
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
                                     $sql = "select anti.ID,anti.Antibiotic,anti.Drug_Symbol,anti.Laboratory_Typing_Method,anti.Laboratory_Typing_Method_Version_or_Reagent,anti.Laboratory_Typing_Platform,anti.Measurement,anti.Measurement_Sign,anti.Measurement_Units,anti.Resistance_Phenotype,anti.Testing_Standard,anti.Vendor,anti.Sample_Metadata_ID,anti.Created_Date,anti.Modified_Date,anti.Created_By,anti.Modified_By
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
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[1]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Drug Symbol</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"> $loop_varian_class[2]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[3]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Platform</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"> $loop_varian_class[4]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method Version or Reagent</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"> $loop_varian_class[5]</div>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[6]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Sign</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[7]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Units</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[8]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Resistance Phenotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"> $loop_varian_class[9]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Testing Standard</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"> $loop_varian_class[10]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Vendor</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"> $loop_varian_class[11]</div>
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
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Drug Symbol</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Platform</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Laboratory Typing Method Version or Reagent</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Sign</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Measurement Units</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Resistance Phenotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Testing Standard</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Vendor</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"></div>
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
                                                <p><input type="radio" name="treatlevel" id='urgent' value="urgent"> Urgent<br></p>
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
                                         <div class="col-sm-9 control-label input_field"><a class="input_link" href="https://www.ncbi.nlm.nih.gov/taxonomy/<?php echo $tax[1];?>"  target="_blank"><?php echo $tax[1];  ?></a></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Kingdom:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"><?php echo $tax[2]; ?></div>
                                       
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Phylum:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-9 control-label input_field"><?php echo $tax[3]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Bacterial BioVar:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-9 control-label input_field"><?php echo $tax[4]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Taxonomy Class:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-9 control-label input_field"><?php echo $tax[5]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Order:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-9 control-label input_field"><?php echo $tax[6]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Family:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                       <div class="col-sm-9 control-label input_field"><?php echo $tax[7]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Genus</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"><?php echo $tax[8]; ?></div>
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Species</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"><?php echo $tax[9]; ?></div>
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Sub Species</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-9 control-label input_field"><?php echo $tax[10]; ?></div>
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Pathovar</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-9 control-label input_field"><?php echo $tax[11]; ?></div>
                                    </div>                                  
                                </div>
                                 <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotype</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-9 control-label input_field"><?php echo $tax[12]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Strain:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"><?php echo $tax[13] ; ?></div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Sub strain:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"><?php echo $tax[14] ; ?></div>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="h2tab6">
                            <div class="row">

                                <?php
                                // print_r($seq);
                                if(!isset($ids[8])||($ids[8])==""){

                                    $ck_protein_id=<<<HAHA
                                      <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$ids[6]</div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="source" class="col-sm-2 control-labelx">Source ID:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$ids[7]</div>
                                    </div>
                                </div>
HAHA;
                                echo $ck_protein_id;
                                }else {

                                ?>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Source ID: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[9]; ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Protein ID: </label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php echo $ids[10]; ?></div>
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
                                            <div class="col-sm-9 control-label input_field"><?php echo  $seq[5]; ?></div>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <label for="pubmed" class="col-sm-2 control-labelx">Plasmid:</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><?php if ($as[8] != "") {echo $as[8];} else {echo "-";} ?> </div>
                                    </div>
                                </div>

                          <!--       <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">End5</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                         <div class="col-sm-9 control-label input_field"><?php echo  $seq[1]; ?></div>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">End3</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                          <div class="col-sm-9 control-label input_field"><?php echo  $seq[2]; ?></div>
                                    </div>                                  
                                </div> -->
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Protein Sequence</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        

                                        <label class="col-sm-12 control-label input_field" style=" word-wrap: break-word"><?php echo $seq[4]; ?></label>
                                    </div>                                  
                                </div>

                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Nucleotide Sequence</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <label class="col-sm-12 control-label input_field" style=" word-wrap: break-word"><?php echo $seq[3]; ?></label>
                                    </div>                                 
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="h2tab7">
                            <?php $loop_varian_class=mysql_fetch_array($squery);
                            // if source id is not present then source should be empty too
                            // var_dump($loop_varian_class);
                            $sm_source=trim($loop_varian_class[15])==''?'':$loop_varian_class[14];


                            // if($loop_varian_class){
                            function is_sm_fields_empty($loop_varian_class){
                                return trim($loop_varian_class['BioProject_ID'])==""&&
                                trim($loop_varian_class['Isolation_site'])==""&&
                                trim($loop_varian_class['Serotyping_Method'])==""&&
                                trim($loop_varian_class['Source_Common_Name'])==""&&
                                trim($loop_varian_class['Specimen_Collection_Date'])==""&&
                                trim($loop_varian_class['Specimen_Collection_Location_Country'])==""&&
                                trim($loop_varian_class['Specimen_Collection_Location_Latitude'])==""&&
                                trim($loop_varian_class['Specimen_Collection_Location'])==""&&
                                trim($loop_varian_class['Specimen_Source_Age'])==""&&
                                trim($loop_varian_class['Specimen_Collection_Location_Longitude'])==""&&
                                trim($loop_varian_class['Specimen_Source_Disease'])==""&&
                                trim($loop_varian_class['Specimen_Source_Developmental_Stage'])==""&&
                                trim($loop_varian_class['Specimen_Source_Gender'])==""&&
                                trim($loop_varian_class['Health_Status'])==""&&
                                trim($loop_varian_class['Treatment'])==""&&
                                trim($loop_varian_class['Specimen_Type'])==""&&
                                trim($loop_varian_class['Host'])==""&&
                                trim($loop_varian_class['Symptom'])=="";
                            }
                            // }
                            // var_dump( $loop_varian_class['BioProject_ID']);
                            if(is_sm_fields_empty($loop_varian_class)){
                                $source_part='<div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Source</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                    </div>                                  
                                </div>
                                    <label for="anti" class="col-sm-2 control-labelx">Source ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"></div>
                                        </div>
                                    </div>                                  
                                </div>';
                            }
                            // in the if    if some data are present in the meta tab 
                            if(!is_sm_fields_empty($loop_varian_class)){
                                $source_part=<<<HA
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Source</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$ids[8]</div>
                                    </div>                                  
                                </div>
                                    <label for="anti" class="col-sm-2 control-labelx">Source ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field"><a class="input_link" href=https://www.ncbi.nlm.nih.gov/nuccore/$ids[9] target="_blank">$ids[9]</a></div>
                                        </div>
                                    </div>                                  
                                </div>
HA;
                            }
                            // Source Id => from Identity  and Source Id From meta and has fields data from meta table then comes in here
                            if(trim($loop_varian_class['4'])!=""&&trim($loop_varian_class['15'])!=""&&!is_sm_fields_empty($loop_varian_class)){
                            $source_part=<<<HA
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Source</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$ids[8], $loop_varian_class[14]</div>
                                    </div>                                  
                                </div>
                                    <label for="anti" class="col-sm-2 control-labelx">Source ID</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">
                                        <a class="input_link" href=https://www.ncbi.nlm.nih.gov/nuccore/$ids[9] target="_blank">$ids[9]</a>, 
                                         <a class="input_link" href=https://www.ncbi.nlm.nih.gov/nuccore/$loop_varian_class[15] target="_blank"> $loop_varian_class[15]</a> 
                                       </div>
                                        </div>
                                    </div>                                  
                                </div>
HA;
                            }

// var_dump($loop_varian_class[14]);




                             $tempContent2=<<<TT
                         <div class="row">
                         <div class="form-group">
                                $source_part
                                <div class="form-group">
                                <label for="pubmed" class="col-sm-2 control-labelx">BioProject ID</label><div class="col-sm-1"></div>
                                <div class="col-sm-9">
                                    <div class="col-sm-2 control-label input_field"<a class="input_link" href="https://www.ncbi.nlm.nih.gov/bioproject/$as[6]"  target="_blank">$as[6]</a></div>
                                </div>
                            </div>                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Isolation Site</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$loop_varian_class[16]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotyping Method</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$loop_varian_class[17]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Serotyping Common Name</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[18]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Collection Date</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[19]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location Country</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[20]</div> 
                                    </div>                                  
                                </div>
                                
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Location</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[21]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Age</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[24]</div></div>                                  
                                </div>
                            <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Developmental Stage</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                        <div class="col-sm-9 control-label input_field">$loop_varian_class[25]</div>
                                    </div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Disease</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[26]</div>
                                    </div>                                  
                                </div>
                            
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Source Gender</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[27]</div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Health Status</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[28]</div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Treatment</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[29]</div></div>                                  
                                </div>
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Specimen Type</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[30]</div></div>                                  
                                </div>     
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Symptom</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[31]</div></div>                                  
                                </div>  
                                <div class="form-group">
                                    <label for="anti" class="col-sm-2 control-labelx">Host</label><div class="col-sm-1"></div>
                                    <div class="col-sm-9">
                                    <div class="col-sm-9 control-label input_field">$loop_varian_class[32]</div></div>                                  
                                </div>                            
                            </div>
TT;
                            echo "$tempContent2";
                             ?>


                             <!-- hit tab  -->
                        <div class="tab-pane fade" id="h2tab8">
                            <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <!-- <th></th> -->
                <th>Sample Metadata ID</th>
                <th>Taxon ID</th>
                <th>Kingdom</th>
                <th>Phylum</th>
                <th>Class</th>
                <th>Order</th>
                <th>Family</th>
                <th>Genus</th>
                <th>Species</th>
                <th>Sub Species</th>
                <th>Strain</th>
                <th>Sub Strain</th>

                <th>Source</th>
                <th>Isolation site</th>
                <th>Serotyping method</th>
                <th>Source Common Name</th>
                <th>Date</th>
                <th>Location Country</th>
                <th>Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Source Age</th>
                <th>Developmental Stage</th>
                <th>Source Disease</th>
                <th>Source Gender</th>
                <th>Health Status</th>
                <th>Treatment</th>
                <th>Specimen Type</th>
                <th>Symptom</th>
                <th>Host</th>

            </tr>
        </thead>
    </table>
                     
                        </div>


                        </div>

                    </div>
                    </form>

                                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <!-- <iframe > -->
                            <table id="antiTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <!-- <th></th> -->
                                        <th>Antibiogram ID</th>
                                        <th>Antibiotic</th>
                                        <th>Drug_Symbol</th>
                                        <th>Laboratory Typing Method</th>
                                        <th>Laboratory Typing Method Version or Reagent</th>
                                        <th>Laboratory Typing Platform</th>
                                        <th>Measurement</th>
                                        <th>Measurement Sign</th>
                                        <th>Measurement Units</th>
                                        <th>Resistance Phenotype</th>
                                        <th>Testing Standard</th>
                                        <th>Vendor</th>

                                    </tr>
                                </thead>
                            </table>
<!-- </iframe> -->
                            </div>
                </div>
            </div>
        </div>  
    </div>   
</section>
<?php

   

    if(isset($t1)){
        switch ($tl[1]) {
            case 'urgent':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#urgent").attr('checked', 'checked');
        document.getElementById("serious").disabled = true;
document.getElementById("concern").disabled = true;
document.getElementById("none").disabled = true;
document.getElementById("unknown").disabled = true;
document.getElementById("urgent").disabled = true;
        </script>
HAHA;
        echo $scp;
        break;
            case'serious':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#serious").attr('checked', 'checked');
        document.getElementById("serious").disabled = true;
document.getElementById("concern").disabled = true;
document.getElementById("none").disabled = true;
document.getElementById("unknown").disabled = true;
document.getElementById("urgent").disabled = true;
        </script>
HAHA;
        echo $scp;
        break;
            case'concern':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#concern").attr('checked', 'checked');
        document.getElementById("serious").disabled = true;
document.getElementById("concern").disabled = true;
document.getElementById("none").disabled = true;
document.getElementById("unknown").disabled = true;
document.getElementById("urgent").disabled = true;
        </script>
HAHA;
        echo $scp;
        break;

            case 'none':
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#none").attr('checked', 'checked');
document.getElementById("serious").disabled = true;
document.getElementById("concern").disabled = true;
document.getElementById("none").disabled = true;
document.getElementById("unknown").disabled = true;
document.getElementById("urgent").disabled = true;



        </script>
HAHA;
        echo $scp;
        break;
        }
    }else{
        $scp=<<<HAHA
        <script type="text/javascript">
        $("#unknown").attr('checked', 'checked');
document.getElementById("serious").disabled = true;
document.getElementById("concern").disabled = true;
document.getElementById("none").disabled = true;
document.getElementById("unknown").disabled = true;
document.getElementById("urgent").disabled = true;


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

<style>
.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}
</style>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}

function btn_click(hs){
    console.log(hs);
    btn_search_me = hs; 
}

</script>
<?php include 'includes/footerx.php';?>


