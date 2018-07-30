<?php include 'includes/header.php';?>

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

// Load this page with the table data.
// scripts/data.json
$(document).ready(function() {
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
                 "data": $.extend( {}, data, {'wt':'json', 'q':'id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'* OR Protein_ID:*'+data.search.value+'* OR Taxon_Genus:*'+data.search.value+'*' ,'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),'rows':data.length} ),
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
        "order": [[ 1, "asc" ]]
    });


} );

</script>

<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
            <h2 class="title">AMRdb Browse/Search Module</h2>
		    <div class="separator-2"></div>
 				<div style="line-height: 150%;"> <p style="text-align:justify"; >The Browse and Search page allow users to browse the AMRdb as well as search the database. The database is present in a tabular format, with searchable columns. Details of every gene can be displayed by clicking on the (+) sign in front of JCVI ID. For displaying all the <b>Escherichia coli</b> having <b>beta-lactam</b> resistant, user can enter the following <b>Escherichia coli beta-lactam</b> in the search bar. All the data will be filtered and only those IDs which belong to <b>Escherichia coli and beta-lactam </b>are displayed. For more information see <a href="help.php#location">help page</a>.</p><hr></hr>
				</div>

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

            </tr>
        </thead>
    </table>
            </div>
        </div>
   </div>  
</section>
<?php include 'includes/footerx.php';?>