<?php include 'includes/header.php'; 

// include_once 'export_txtfile.php';
date_default_timezone_set('America/chicago');
/* This script generate a datatable based on the 
   data returned by browse_fetch.php
*/

//    if(!isset($_SESSION['userID'])){
//   echo '<br><br><br><br><h2 align="center">Please Log in as an admin</h5><br><br><br><br><br><br><br>';
// include 'includes/footerx.php';
//   die;
// }
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
  var btn_count = 0;
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
        "ajax":function(data,callback, setting){
             $.ajax( {
                   "url": "https://cdc-1.jcvi.org:8081/solr/primer/select",
                 "type":"GET",
                 "data": $.extend( {}, data, {'wt':'json',  'q':'Primer:'+escapeRegExp(data.search.value)+' Or Target:'+escapeRegExp(data.search.value) +' Or FWD:'+escapeRegExp(data.search.value)+' Or REV:'+escapeRegExp(data.search.value)
                                               ,'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),'rows':data.length}),
                 "dataType": "jsonp",
                 "jsonp":"json.wrf",
                 "success": function(json) {
                   var o = {
                     recordsTotal: json.response.numFound,
                     recordsFiltered: json.response.numFound,
                     data: json.response.docs
                   };        
                   callback(o);
                    console.log(escapeRegExp(data.search.value));

                 }
               } );
        },

        "columns": [
            // {
            //     "className":      'details-control',
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
                  { "data": "Primer",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = '<a href=browse.php?primer=' + data + ' target=_blank>' + data + '</a>';
                }
                return data;
                }
            },
             { "data": "Target",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = data ;
                }
                return data;
                }
            },
            { "data": "FWD",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = data ;
                }
                return data;
                }
            },
             { "data": "REV",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = data ;
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
            <h2 class="title">Primer Catalog</h2>
            <div class="separator-2"></div>
                <div style="line-height: 150%;"> <p style="text-align:justify"; >Primer Catalog provides listing and advance search of all primers available in the system. For more information see <a href="help.php#primer_search_page_help" target="_blank">help page</a>.</p><hr>
                  <div id='btn_container'>
                  </div>
                </div>
<!--  <img src="images/details_open.png" alt="+ sign" style="display:inline;"> -->
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <!-- <th></th> -->
                <th>Primer</th>
                <th>Target</th>
                <th>FWD</th>
                <th>REV</th>
            </tr>
        </thead>
   
    </table>
            </div>
        </div>
   </div>  
</section>

<?php include 'includes/footerx.php';?>


