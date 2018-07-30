<?php include 'includes/header.php'; 

include_once 'export_txtfile.php';
date_default_timezone_set('America/chicago');
/* This script generate a datatable based on the 
   data returned by browse_fetch.php
*/
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
        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select",
                 "type":"GET",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'* OR Protein_ID:*'+data.search.value+'* OR Taxon_Genus:*'+data.search.value+'*' ,'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),'rows':data.length}),
                 "dataType": "jsonp",
                 "jsonp":"json.wrf",
                 "success": function(json) {
                   var o = {
                     recordsTotal: json.response.numFound,
                     recordsFiltered: json.response.numFound,
                     data: json.response.docs
                   };        
                   callback(o);
                   // add a button where we can retrive all the information
                    // let btn_container=document.getElementById('btn_container');
                    // console.log(btn_container);
                    // let btn=document.createElement('BUTTON');
                    // btn.style.float="right";
                    // btn.onclick=function() { 

                          // <?php
                          //  $search ="http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=*:*&rows=2000&wt=json";
                          //  // "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=*:*&wt=php"
                          //  $jsonString = file_get_contents($search);
                          //  // echo $jsonString;
                          //  ?> 
                        
         
                    // <button id ='export_btn'style="float: right;" value="H" onclick="">Export</button>

                    // </a>

                    if(btn_count==0){
                        let expt_btn = document.getElementById('btn_container');
                        // console.log(data.search.value);
                        let btn=document.createElement('BUTTON');
                        btn.style="float: right";
                        btn.id="btn_export";
                        let btn_t = document.createTextNode('Export');
                        expt_btn.appendChild(btn);
                        btn.appendChild(btn_t);
                        expt_btn.appendChild(btn);
                        let sent_query = 'id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'* OR Protein_ID:*'+data.search.value+'* OR Taxon_Genus:*'+data.search.value+'*';
                        expt_btn.onclick=function(){
                          // when user click download
                          // b_href.click();
                          $( "#btn_export" ).wrap( "<a id='linkMe' href=<?php echo export_files_main('');?> download></a>" );
                          document.getElementById('btn_export').click();
                          
                        }
                        btn_count++;
                  }else{
                    // only change the download url
                    // let btn=document.getElementById('b_href');

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


