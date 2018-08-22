<?php include 'includes/header.php'; 

// $haha = curl_init();
//phpinfo();
include_once 'export_txtfile.php';
date_default_timezone_set('America/chicago');
/* This script generate a datatable based on the 
   data returned by browse_fetch.php
*/
?>
<!-- self reference to get the data   -->
<?php

// var_dump($_POST['search_query']);

  if($_POST['mySubmit']){
      // $link_path=export_files_main($_POST['search_query']);
    // var_dump($_POST['search_query']);
    $path_ex= export_files_main($_POST['search_query']);
// echo $path_ex;
    $sub_btn=<<<HAHA

     <script type="text/javascript">
    window.open("$path_ex");
      </script>
HAHA;
 echo $sub_btn;
}

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"> -->
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


<script type="text/javascript" class="init">
// Load this page with the table data.
// scripts/data.json
$(document).ready(function() {
  var btn_count = 0;
  // 
  function escapeRegExp(str) {
  let regex = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
    if(str.trim()==''){
     return  '*';
    }
    if(!regex.test(str)){
        return '*'+str+'*';
    }
    return '"*'+str+'*"';

  // return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|\&|\!|\"|\~|\:|\']/g, "\\$&");
}
    var table = $('#example').DataTable({
        

        // "processing":    true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
                               // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        "serverSide":    true, 
        // "bLengthChange": false, // disable page entities
        // "searching":     false,       // disable search
        "stateSave":     true,// when refresh stay on the same page 

        // "ajax": 'ztest.txt',// work with local file 
        // "displayLength" : 0,
        lengthMenu: [
                    [ 10, 15, 25 ],
                    [ '10', '15', '25' ]
                    ],
                    // make a ajax call to solr server to get all the data that matches search field

        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select",
                 "type":"POST",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'id :'+escapeRegExp(data.search.value)+
                                                            ' Or Allele:'+escapeRegExp(data.search.value)+
                                                            ' Or Antibiotic:'+escapeRegExp(data.search.value)+
                                                            ' Or BioProject_ID:'+escapeRegExp(data.search.value)+
                                                            ' Or Drug_Class:'+escapeRegExp(data.search.value)+
                                                            ' Or Drug_Family:'+escapeRegExp(data.search.value)+
                                                            ' Or Drug_Name:'+escapeRegExp(data.search.value)+
                                                            ' Or Drug_Sub_Class:'+escapeRegExp(data.search.value)+
                                                            ' Or Drug_Symbol:'+escapeRegExp(data.search.value)+
                                                            ' Or EC_Number:'+escapeRegExp(data.search.value)+
                                                            ' Or Gene_Symbol:'+escapeRegExp(data.search.value)+
                                                            ' Or Health_Status:'+escapeRegExp(data.search.value)+
                                                            ' Or Host:'+escapeRegExp(data.search.value)+
                                                            ' Or Identity_Sequence_ID:'+escapeRegExp(data.search.value)+
                                                            ' Or Isolation_site:'+escapeRegExp(data.search.value)+
                                                            ' Or Laboratory_Typing_Method:'+escapeRegExp(data.search.value)+
                                                            ' Or Laboratory_Typing_Platform:'+escapeRegExp(data.search.value)+
                                                            ' Or Measurement:'+escapeRegExp(data.search.value)+
                                                            ' Or Measurement_Sign:'+escapeRegExp(data.search.value)+
                                                            ' Or Measurement_Units:'+escapeRegExp(data.search.value)+
                                                            ' Or Mol_Type:'+escapeRegExp(data.search.value)+
                                                            ' Or Parent_Allele:'+escapeRegExp(data.search.value)+
                                                            ' Or Parent_Allele_Family:'+escapeRegExp(data.search.value)+
                                                            ' Or Plasmid_Name:'+escapeRegExp(data.search.value)+
                                                            ' Or Protein_ID:'+escapeRegExp(data.search.value)+
                                                            ' Or Protein_Name:'+escapeRegExp(data.search.value)+
                                                            ' Or PubMed_IDs:'+escapeRegExp(data.search.value)+
                                                            ' Or Pubmed_IDs:'+escapeRegExp(data.search.value)+
                                                            ' Or Resistance_Phenotype:'+escapeRegExp(data.search.value)+
                                                            ' Or SNP:'+escapeRegExp(data.search.value)+
                                                            ' Or Serotyping_Method:'+escapeRegExp(data.search.value)+
                                                            ' Or Source:'+escapeRegExp(data.search.value)+
                                                            ' Or Source_Common_Name:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Collection_Date:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Collection_Location:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Collection_Location_Country:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Collection_Location_Latitude:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Collection_Location_Longitude:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Source_Age:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Source_Developmental_Stage:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Source_Disease:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Source_Gender:'+escapeRegExp(data.search.value)+
                                                            ' Or Specimen_Type:'+escapeRegExp(data.search.value)+
                                                            ' Or Status:'+escapeRegExp(data.search.value)+
                                                            ' Or Symptom:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Bacterial_BioVar:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Class:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Family:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Genus:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_ID:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Kingdom:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Order:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Pathovar:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Phylum:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Serotype:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Species:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Strain:'+escapeRegExp(data.search.value)+
                                                            ' Or Taxon_Sub_Species:'+escapeRegExp(data.search.value)+
                                                            ' Or Testing_Standard:'+escapeRegExp(data.search.value)+
                                                            ' Or Treatment:'+escapeRegExp(data.search.value)+
                                                            ' Or Vendor:'+escapeRegExp(data.search.value) 
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

                    // create export button. 
                      if(btn_count==0){
                       let expt_btn = document.getElementById('btn_container');
                                let f = document.createElement("form");
                                f.setAttribute('method',"post");
                                f.setAttribute('action',"browse.php");
                                // f.formAction="/export_txtfile.php";
                                let i = document.createElement("input"); //input element, text
                                i.id="search_input";
                                i.setAttribute('type',"hidden");
                                i.setAttribute('name',"search_query");
                                i.setAttribute('value',escapeRegExp(data.search.value));
                                let s = document.createElement("input"); //input element, Submit button

                                s.setAttribute('type',"submit");
                                s.setAttribute('name',"mySubmit");
                                s.setAttribute('value',"Export");
                                s.id='mySubmit';
                                s.style="float: right";
                                f.appendChild(i);
                                f.appendChild(s);
                                expt_btn.appendChild(f);
                                btn_count++;
                        }else{
                              let i= document.getElementById('search_input');
                              i.setAttribute('value',escapeRegExp(data.search.value));
                        }
                 }
               } );
        },

        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "id",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = '<a href=singlex.php?id=' + data + ' target=_blank>' + data + '</a>';
                }
                return data;
                }
            },            
            { "data": "Gene_Symbol" ,
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
            { "data": "Drug_Family",
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
            { "data": "Taxon_Species",
              "mRender": function(data, type,full){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return full.Taxon_Genus+ "  "+ full.Taxon_Species;
                     }},
            { "data": "Protein_Name",
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
            { "data": "Protein_ID",
              "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = '<a href=https://www.ncbi.nlm.nih.gov/protein/' + data + ' target=_blank>' + data + '</a>';
                    }
                    return data;
                    } 
            }       
        ],
        // "order": [[ 0, 'desc' ], [ 1, 'desc' ]] // will not work on serverside fetching
    });
} );

</script>
<!-- Main container describstion for incoming data -->
<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
            <h2 class="title">AMRdb Browse/Search Module</h2>
            <div class="separator-2"></div>
                <div style="line-height: 150%;"> <p style="text-align:justify"; >Browse and search the data contained within the AMRdb. Commonly searched data types are preferentially displayed. Users can search the entire AMRdb using the main search bar, or each main data type can be searched individually using its respective search. Additionally, each data type is sortable. <br><b>Example search function</b>: To display all <i>Escherichia coli </i>genes of the beta-lactam drug class, enter "Escherichia coli beta-lactam" in the main search bar. AMRdb data will be filtered and only those AMR genes found in Escherichia coli AND that belong to the beta-lactam drug class are displayed. For more information see <a href="help.php#location" target="_blank">help page</a>.</p><hr></hr>
                  
                  <div id='btn_container'>
                  </div>

                <br><br><a style="float: right;" href="search_qb.php" target="_blank" >Advance Search</a><br>
                </div>
<!--  <img src="images/details_open.png" alt="+ sign" style="display:inline;"> -->
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>Identity ID</th>
                <th>Gene Symbol</th>
                <th>Drug Family</th>
                <th>Organism</th>
                <th>Protein_Name</th>
                <th>Protein ID</th>
            </tr>
        </thead>
   
    </table>
            </div>
        </div>
   </div>  
</section>

<?php include 'includes/footerx.php';?>


