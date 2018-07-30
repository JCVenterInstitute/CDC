<?php include 'includes/header.php'; 
/* This script generate a datatable based on the 
   data returned by browse_fetch.php
*/
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<style type="text/css" class="init">
	
td.details-control {
	/*background: url('images/details_open.png') no-repeat center center;*/
	cursor: pointer;
}
tr.shown td.details-control {
	background: url('images/details_close.png') no-repeat center center;
}
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
/* Formatting function for row details - modify as you need
   Checking for unfedined and null data if a data cell is null then display: false 
    
    split it and link it. if its empty then do not show 

   */
function format ( d ) {
    let rt_string ='<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    if(d.Parent_Allele!=null&&(typeof d.Parent_Allele != "undefined")){
       rt_string+='<tr><td>Parent_Allele</td><td>'+ d.Parent_Allele+'</td></tr>';
    }if(d.Parent_Allele_Family!=null&&(typeof d.Parent_Allele_Family != "undefined")) {
        rt_string+='<tr><td>Parent_Allele_Family</td><td>'+d.Parent_Allele_Family+'</td></tr>';
    }if(d.SNP!=null&&(typeof d.SNP != "undefined")) {
        rt_string+='<tr><td>SNP</td><td>'+ d.SNP+'</td></tr>';
    }if(d.Allele!=null&&(typeof d.Allele != "undefined")) {
       rt_string+='<tr><td>Allele</td><td>'+ d.Allele+'</td></tr>';
    }if(d.Drug_Class!=null&&(typeof d.Drug_Class != "undefined")) {
      rt_string+='<tr><td>Drug_Class</td><td>'+d.Drug_Class+'</td></tr>';
    }if(d.Drug_Sub_Class!=null&&(typeof d.Drug_Sub_Class != "undefined")) {
        rt_string+='<tr><td>Sub_Drug_Class</td><td>'+d.Drug_Sub_Class+'</td></tr>';
    }if(d.Taxon_Phylum!=null&&(typeof d.Taxon_Phylum != "undefined")) {
        rt_string+='<tr><td>Phylum</td><td>'+ d.Taxon_Phylum+'</td></tr>';
    }if(d.Taxon_Class!=null&&(typeof d.Taxon_Class != "undefined")) {
        rt_string+='<tr><td>Class</td><td>'+ d.Taxon_Class+'</td></tr>';
    }if(d.Taxon_Order!=null&&(typeof d.Taxon_Order != "undefined")) {
         rt_string+='<tr><td>Order</td><td>'+d.Taxon_Order+'</td></tr>';
    }if(d.Taxon_Family!=null&&(typeof d.Taxon_Family != "undefined")) {
        rt_string+='<tr><td>Family</td><td>'+ d.Taxon_Family+'</td></tr>';
    }if(d.Taxon_Genus!=null&&(typeof d.Taxon_Genus != "undefined")) {
        rt_string+='<tr><td>Genus</td><td>'+d.Taxon_Genus+'</td></tr>';
    }if(d.Taxon_Strain!=null&&(typeof d.Taxon_Strain != "undefined")) {
        rt_string+='<tr><td>Strain</td><td>'+ d.Taxon_Strain+'</td></tr>';
    }if(d.Plasmid_Name!=null&&(typeof d.Plasmid_Name != "undefined")) {
        rt_string+='<tr><td>Plasmid</td><td>'+ d.Plasmid_Name+'</td></tr>';
    }if(d.BioProject_ID!=null&&(typeof d.BioProject_ID != "undefined")) {
        rt_string+='<tr><td>Bioproject</td><td><a href=https://www.ncbi.nlm.nih.gov/bioproject/'+d.BioProject_ID+' target=_blank>'+d.BioProject_ID+'</a></td></tr>';
    }if(d.Biosample!=null&&(typeof d.Biosample != "undefined")) {
        rt_string+='<tr><td>Biosample</td><td><a href=https://www.ncbi.nlm.nih.gov/biosample/'+d.Biosample+' target=_blank>'+ d.Biosample+'</a></td></tr>';
    }
    rt_string+='</table>';
    return  rt_string;
}

// Load this page with the table data.
// scripts/data.json
$(document).ready(function() {
    // table varible initialize the cells of the table. 
    // alert($('#example').DataTable().page.len());
    // var strUser = ee.options[ee.selectedIndex].text;

   // console.log(ee);
    var fetch_Me="http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=*:*&rows=10&wt=json";
    // function show_Page(x){
    //     return"http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=*:*&rows="+x+"&wt=json"
    // }
    // fetch_Me=show_Page("10");
    var table = $('#example').DataTable({

        // "processing":    true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
                               // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        "serverSide":    true, 
        // "bLengthChange": false, // disable page entities
        // "searching":     false,       // disable search
        "stateSave":     true,// when refresh stay on the same page 

        // "ajax": 'ztest.txt',// work with local file 
        // "displayLength" : 0,
        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select",
                 "type":"GET",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'*', 'rows':data.length} ),
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
                        data = '<a href=https://www.ncbi.nlm.nih.gov/nuccore/' + data + ' target=_blank>' + data + '</a>';
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
                        data=new_d.map(x=>'<a href=https://www.ncbi.nlm.nih.gov/nuccore/'+x + ' target=_blank>' + x + '</a>' ).join();;
                    } 
                        return data;
                    }  
            },
            { "data": "Taxon_ID" ,
              "render": function(data, type, row, meta){
                    if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                        return data;
                    } }, // no such field
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
                        data = '<a href=https://www.ncbi.nlm.nih.gov/nuccore/' + data + ' target=_blank>' + data + '</a>';
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
        "order": []
    });
  var data = table.ajax.params();
  console.log(data);
//      table.on( 'xhr', function () {
//     var data = table.ajax.params();
//     if(data.search.value!=''){
//     // alert( 'Search term was: '+data.search.value );
//     table.ajax.url('http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q={!term%20f=id}10003').load();
//     }
// } );

} );



/* search job*/
// $(document).ready(function() {
//     // Setup - add a text input to each footer cell
//     $('#example tfoot th').each( function () {
//         var title = $(this).text();
//         $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
//     } );
 
//     // DataTable
//     var table = $('#example').DataTable();
 
//     // Apply the search
//     table.columns().every( function () {
//         var that = this;
 
//         $( 'input', this.footer() ).on( 'keyup change', function () {
//             if ( that.search() !== this.value ) {
//                 that
//                     .search( this.value )
//                     .draw();
//             }
//         } );
//     } );
// } );
</script>
<!-- Main container describstion for incoming data -->
<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
            <h2 class="title">AMRdb Browse/Search Module</h2>
		    <div class="separator-2"></div>
 				<div style="line-height: 150%;"> <p style="text-align:justify"; >Browse and search the data contained within the AMRdb. Commonly searched data types are preferentially displayed. Users can search the entire AMRdb using the main search bar, or each main data type can be searched individually using its respective search. Additionally, each data type is sortable. <br><b>Example search function</b>: To display all <i>Escherichia coli </i>genes of the beta-lactam drug class, enter "Escherichia coli beta-lactam" in the main search bar. AMRdb data will be filtered and only those AMR genes found in Escherichia coli AND that belong to the beta-lactam drug class are displayed. For more information see <a href="help.php#location">help page</a>.</p><hr></hr>
				</div>
<!--  <img src="images/details_open.png" alt="+ sign" style="display:inline;"> -->
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>Identity ID</th>
                <th>Gene Symbol</th>
                <th>Drug Family</th>
                <th>Taxon ID</th>
                <th>Organism</th>
                <th>Protein_Name</th>
                <th>Protein ID</th>
     <!--            <th>Parent Allele</th>
                <th>Parent Allele Family</th>
                <th>SNP</th>
                <th>Allele</th>
                <th>Drug Class</th>
                <th>Sub Drug Class</th>
                <th>Phylum</th>
                <th>Class</th>
                <th>Order</th>
                <th>Family</th>
                <th>Genus</th>
                <th>Strain</th>
                <th>Plasmid</th>
                <th>Bioproject</th>
                <th>Biosample</th> -->
            </tr>
        </thead>
    <!--     <tfoot>
            <tr>
                <th></th>
                <th>Identity ID</th>
                <th>Gene Symbol</th>
                <th>Drug Family</th>
                <th>Taxon ID</th>
                <th>Organism</th>
                <th>GenBank ID</th>
                <th>Protein ID</th>
                <th>Parent Allele</th>
                <th>Parent Allele Family</th>
                <th>SNP</th>
                <th>Allele</th>
                <th>Drug Class</th>
                <th>Sub Drug Class</th>
                <th>Phylum</th>
                <th>Class</th>
                <th>Order</th>
                <th>Family</th>
                <th>Genus</th>
                <th>Strain</th>
                <th>Plasmid</th>
                <th>Bioproject</th>
                <th>Biosample</th>                     
            </tr>
        </tfoot> -->
    </table>
			</div>
	    </div>
   </div>  
</section>

<?php include 'includes/footerx.php';?>


