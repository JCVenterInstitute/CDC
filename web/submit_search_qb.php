<?php include 'includes/header.php';?>
<!-- /* This script is to designed to output the search request from the user 
   1.request data from the Solr and out put it
*/ -->

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<!--   <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script> -->
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

<?php
    /* This part of the script is make sure if the query has data as output 
        If no out put data. will generate output with user query information and a link to go back.*/ 
    if(((trim($_SESSION['test_str'])==''))){
        $search_q="";
        for ($i=0; $i<count($_SESSION['search_qb_post_info']['field_name']); $i++) {
            if($i!=0){
                $search_q.=' '.$_SESSION['search_qb_post_info']['andORor_'][$i].' ';
            }

            switch ($_SESSION['search_qb_post_info']['field_operator'][$i]) {
            case '=':
                $search_q.= '('.$_SESSION['search_qb_post_info']['field_name'][$i].' = '.$_SESSION['search_qb_post_info']['in_val'][$i].')';
                break;
            case '>=':
                $search_q.= '('.$_SESSION['search_qb_post_info']['field_name'][$i].' >= '.$_SESSION['search_qb_post_info']['in_val'][$i].')';
                break;
            case '<=':
                 $search_q.= '('.$_SESSION['search_qb_post_info']['field_name'][$i].' <= '.$_SESSION['search_qb_post_info']['in_val'][$i].')';
                break;
            case 'like':
                 $search_q.= '('.$_SESSION['search_qb_post_info']['field_name'][$i].' Like '.$_SESSION['search_qb_post_info']['in_val'][$i].')';
                break;
            case 'nlike':
                 $search_q.= '(-'.$_SESSION['search_qb_post_info']['field_name'][$i].' not like '.$_SESSION['search_qb_post_info']['in_val'][$i].')';
                break;
            }
        }
        echo "<br><br><br><br><h1 align='center'>  No available data is showing <br>Please go back check your query "; 
        echo "<h1 align='center'> Your Search Query is : <b>$search_q</b> </h1>";
        echo "<h1 align='center'><a href=search_qb.php><button>Click here to go back</button></a> <br><br><br><br><br><br>";
        include 'includes/footerx.php';
        die();
    }
?>
<script>
    var datax = <?php echo json_encode($_SESSION['test_str'], JSON_HEX_TAG); ?>; //Don't forget the extra semicolon!
    // format the passing output 
    var post_toFechPage = <?php echo json_encode(json_encode($p_string), JSON_HEX_TAG); ?>;
    // alert(datax);
</script>
<script type="text/javascript" class="init">
/* Formatting function for row details - modify as you need
   Checking for unfedined and null data if a data cell is null then display: false */

// var da ="1";
/*This data will be sent to search_fetch page using post. However, the datatable will 
    finish trying to read the data from the search_fetch page before the data is processed.  */
$(document).ready(function(){
    /*  declear to search fetch php and get the databack for datatable to display.
        */
	        var http = new XMLHttpRequest();
			// var params = 'orem=ipsum&name=binny';
			http.open('POST', 'search_fetch.php', true);
			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = function() {//Call a function when the state changes.
			    if(http.readyState == 4 && http.status == 200)
			        console.log('getting: '+http.responseText); // debug step
			}
            console.log("Sending : "+post_toFechPage);
			http.send(post_toFechPage);
});
// Load this page with the table data.
// scripts/data.json
$(document).ready(function() {

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
  /* load the data from search_fetch script*/
    var table = $('#example').DataTable({
         // "searchDelay": 350,
        "processing": true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
    // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        "serverSide": true,
        // "ajax": da,
         "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "/solr/my_core_exp/select",
                 "type":"GET",
                 "data": $.extend( {}, data, {'wt':'json', 'q':datax+'AND ( id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'* OR Protein_ID:*'+data.search.value+'* OR Taxon_Genus:*'+data.search.value+'* '+')' + " AND Is_Active:1" ,'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),'rows':data.length} ),
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
        // dom: 'lBfrtip',
        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ],
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": '',
                "visible": false
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
        "order": [[1, 'asc']]
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
            <div style="line-height: 150%;"> <p style="text-align:justify"; >Browse and search the data contained within the AMRdb. Commonly searched data types are preferentially displayed. Each data type is also sortable.  <br><b>Example search function</b>: To display all <i>Escherichia coli </i>genes of the beta-lactam drug family, enter "Escherichia coli beta-lactam" in the main search bar. AMRdb data will be filtered and only those AMR genes found in Escherichia coli AND that belong to the beta-lactam drug family are displayed. For more information see <a href="help.php#location" target="_blank">help page</a>.</p><hr></hr>
                </div>

<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>Identity ID</th>
                <th>Gene Symbol</th>
                <th>Allele</th>                
                <th>Protein Name</th>
                <th>Protein ID</th>
                <th>Drug Family</th>
                <th>Organism</th>
            </tr>
        </thead>
       
    </table>
			</div>
	    </div>
        <br><br>
        <button  onclick="goBack()">Go back</button>
   </div>  
</section>
<script>
function goBack() {
    window.history.back();
}
</script>
<?php include 'includes/footerx.php';?>


