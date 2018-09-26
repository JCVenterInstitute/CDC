<?php include 'includes/header.php';


if(!isset($_SESSION['userID'])){
  echo '<br><br><br><br><h2 align="center">Please Log in as an admin</h5><br><br><br><br><br><br><br>';
include 'includes/footerx.php';
  die;
}
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<style type="text/css" class="init">

tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
tfoot {
    display: table-header-group;
}
</style>
<script type="text/javascript" class="init">
  // escape RegExp and helper escape special character
function escapeRegExp_helper(str){
    let regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
      if(str.trim()==''){
        console.log('s');
        return;
      }
      if(!regex.test(str)){
         console.log('here');
        return '*'+str+'*';
      }
      return '"*'+str+'*"';
  }
  var btn_count = 0;
  // 
  function escapeRegExp(str) {
    if(str.trim()=="" ){
      return "*";
    }
    // split str by space then format it as => (+A+B+C) where A,B,C are each words splited by space
    let regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;

    let new_st=str.split(' ');
   
    const final_st = new_st.map(x=> escapeRegExp_helper(x));
// console.log('(+'+final_st.join('+')+')');
  
  return '(+ '+final_st.join(' AND ')+')';

  // return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|\&|\!|\"|\~|\:|\']/g, "\\$&");
}
// Load this page with the table data.
// scripts/data.json
$(document).ready(function() {
    var table = $('#example').DataTable({

        // "processing":    true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
                               // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        "serverSide":    true, 
        // "bLengthChange": false, // disable page entities
        // "searching":     false,       // disable search
        // "stateSave":     true,// when refresh stay on the same page 

        // "ajax": 'ztest.txt',// work with local file 
        // "displayLength" : 0,
        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "/solr/my_core_exp/select",
                 "type":"GET",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'all_fields:'+escapeRegExp(data.search.value),
                                'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),
                                'rows':data.length} ),
                 "dataType": "jsonp",
                 "jsonp":"json.wrf",
                 "success": function(json) {
                   var o = {
                     recordsTotal: json.response.numFound,
                     recordsFiltered: json.response.numFound,
                     data: json.response.docs
                   };        
                   callback(o);
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
            { "data": "id",
              "render": function(data, type, row, meta){
                if(type === 'display'){
 data = '<a href=edit.php?id=' + data + ' target=_blank>' + data + '</a>';                }
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
            { "data": "Allele" ,
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
                     }
	        }
        ],
        "order": [[ 0, "asc" ]]
    });


} );

</script>

<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
            <h2 class="title">AMRdb Browse/Search Module</h2>
		    <div class="separator-2"></div>
 				<div style="line-height: 150%;"> <p style="text-align:justify"; >Browse and search the data contained within the AMRdb. Commonly searched data types are preferentially displayed. Each data type is also sortable.  <br><b>Example search function</b>: To display all <i>Escherichia coli </i>genes of the beta-lactam drug class, enter "Escherichia coli beta-lactam" in the main search bar. AMRdb data will be filtered and only those AMR genes found in Escherichia coli AND that belong to the beta-lactam drug class are displayed. For more information see <a href="help.php#location" target="_blank">help page</a>.</p><hr></hr>
				</div>

<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <!-- <th></th> -->
                <th>Identity ID</th>
                <th>Gene Symbol</th>
                <th>Allele</th>                
                <th>Protein/Product Name</th>
                <th>Protein ID</th>
                <th>Drug Family</th>
                <th>Organism</th>
            </tr>
        </thead>
    </table>
            </div>
        </div>
   </div>  
</section>
<?php include 'includes/footerx.php';?>
