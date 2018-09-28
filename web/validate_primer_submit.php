<?php include "includes/header.php";  ?> 

<!-- <script type="text/javascript" src="./10321_annoted.js"></script> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"> -->
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


<?php

// check file is present if not do not execute the datatable

// if()
// var_dump($_GET);
$f_path = "tmp/";
$ran = $_GET['ran'];
$file_path= "tmp/$ran";
$dirfilep = "tmp/$ran/out/data.json";
$dirfiled = "tmp/$ran/out/Tabular_Output.txt";

 //var_dump($dirfilep);
// if get is empty or the file is not present then do not show data table.
if(file_exists($dirfilep)){
$ran = $_GET['ran'];

    $content=<<<HAHA
        <section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
            <h2 class="title">Validate Primer</h2>
            <div class="separator-2"></div>
                <div style="line-height: 150%;"> <p style="text-align:justify"; >PCR Validator accesses the primers specificity on AMR or target genome. PCR Validator uses open source <a href="https://sourceforge.net/projects/simulatepcr/">Simulate_PCR</a> tools for predicting both desired and off-target amplification products.<a href="help.php#location">help page</a>.</p><div id='btn_container'><hr><a href="$dirfiled" download><button class='dt-button buttons-copy buttons-html5'style='float:right' id='download_btn' onclick='downloadAllignment()'>Download Alignment</button></a><br><br>
                </div>

<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Identity_ID</th>
                <th>amplicon_len</th>
                <th>HitName</th>
                <th>FP_ID</th>
                <th>FP_mismatches</th>
                <th>RP_ID</th>
                <th>RP_mismatches</th>
                <th>Start</th>
                <th>End</th>
                <th>SubjectFullLength</th>
            </tr>
        </thead>  
    </table>
            </div>
        </div>
   </div>  
</section>
HAHA;
    echo $content;
}else{

        echo "<h1 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes. <br> ";   
    echo "<h2 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes.</h2> "; echo "<h2 align='center'> OR <br>"; echo "Visit the following link <br><a href='https://cdc-1.jcvi.org:8081/validate_primer_submit.php?ran=$ran'>https://cdc-1.jcvi.org:8081/validate_primer_submit.php?ran=$ran </a> </h2>";
    echo "<meta  http-equiv='refresh' content='2;url=validate_primer_submit.php?ran=$ran' />";      
    
}
?>



<style type="text/css" class="init">
    
td.details-control {
    background: url('images/details_open.png') no-repeat center center;
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
<?php
// convert output file into required format. 
// https://datatables.net/examples/data_sources/js_array.html
// reference link is above.
// $data = `cat Tabular_Output2.tsv | perl -pi -e 's/\t/","/g' | perl -pi -e 's/^/["/g' | perl -pi -e 's/\n/"],\n/g'`; 


?>
<script type="text/javascript">
    var cdata_file = <?php echo json_encode($file_path, JSON_HEX_TAG);; ?>;
    cdata_file="./"+cdata_file+"/out/data.json";
    // alert(cdata_file);

</script>
<script type="text/javascript" class="init">

$(document).ready(function() {

    var table = $('#example').DataTable( {
        ajax: cdata_file, // expecting a file   we can use data then  format of the data will be [  [1,2,3], [2,3,4]]  https://datatables.net/examples/data_sources/js_array.html
        // data: dataset,

        "columns": [
            { "data": "Identity_ID",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = '<a href=singlex.php?id=' + data + ' target=_blank>' + data + '</a>';
                    } 
                        return data;
                }  },            
            { "data": "amplicon_len",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },            
            { "data": "HitName",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
            { "data": "FP_ID",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
            { "data": "FP_mismatches",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
            { "data": "RP_ID",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
            { "data": "RP_mismatches",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
        { "data": "Start",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
        { "data": "End",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  },
        { "data": "SubjectFullLength",
            "render": function(data, type, row, meta){
                  if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data = data;
                    } 
                        return data;
                }  }                                                 
        ]
        // "order": [[ 0, 'desc' ], [ 1, 'desc' ]] 
    } );
    // console.log(t);
     
    // Add event listener for opening and closing details
    $('#example tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
} );

</script>

<?php include 'includes/footerx.php';?>
