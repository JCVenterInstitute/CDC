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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"> -->
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


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
  // 
  function escapeRegExp(str) {
    // // check if primer is present if not primer should be empty. 
    if (str.trim()==''){
        console.log("s");
        // primer= ' AND '+ primer;
        return '';
    }
    
    rt_string=' AND (id: '+escapeRegExp_helper(str)+' OR Antibiotic: '+escapeRegExp_helper(str)
    + ' OR Drug_Symbol: '+escapeRegExp_helper(str)+ ' OR Laboratory_Typing_Method: '+escapeRegExp_helper(str)+' OR Laboratory_Typing_Platform:'+escapeRegExp_helper(str)+
    ' OR Laboratory_Typing_Method_Version_or_Reagent:'+escapeRegExp_helper(str)+' OR Measurement:'+escapeRegExp_helper(str)+
    ' OR Measurement_Sign:'+escapeRegExp_helper(str)+
    ' OR Measurement_Units:'+escapeRegExp_helper(str)+
    ' OR Resistance_Phenotype:'+escapeRegExp_helper(str)+
    ' OR Testing_Standard:'+escapeRegExp_helper(str)+
    ' OR Vendor:'+escapeRegExp_helper(str)+')';

    // console.log(rt_string);
  return rt_string;

  // return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|\&|\!|\"|\~|\:|\']/g, "\\$&");
}
var primer = <?php  if (isset($_GET['sm_id'])){
                                    echo json_encode($_GET['sm_id'], JSON_HEX_TAG);
                                }else{
                                    echo json_encode('', JSON_HEX_TAG);
                                };?>;
   
 var table = $('#example').DataTable({
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
                 "data": $.extend( {}, data, {'wt':'json', 'q':'Sample_Metadata_ID:'+primer+escapeRegExp(data.search.value)
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
                        
                    data = data;
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
<!-- Main container describstion for incoming data -->
<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
<table id="example" class="display" style="width:100%">
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
                        </div>
                    </div>
                </div>
            </section>

<?php include 'includes/footerx.php';?>


