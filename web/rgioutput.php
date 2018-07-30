<?php include 'includes/header.php';
$ran = $_GET["ran"];
$dir = "tmp/$ran";
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
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
<script>
    var data = <?php echo json_encode("$dir/ajax.txt", JSON_HEX_TAG); ?>; //Don't forget the extra semicolon!
</script>

<script type="text/javascript" class="init">
/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Pass Bitscore:</td>'+
            '<td>'+d.Pass_Bitscore+'</td>'+
        '</tr>'+	    
        '<tr>'+
            '<td>Best Hit ARO:</td>'+
            '<td>'+d.Best_Hit_ARO+'</td>'+
        '</tr>'+        
        '<tr>'+
            '<td>Drug Class:</td>'+
            '<td>'+d.Drug_Class+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Resistance Mechanism:</td>'+
            '<td>'+d.Resistance_Mechanism+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>AMR Gene Family:</td>'+
            '<td>'+d.AMR_Gene_Family+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Predicted Protein:</td>'+
            '<td>'+d.Predicted_Protein+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>CARD Protein Sequence:</td>'+
            '<td>'+d.CARD_Protein_Sequence+'</td>'+
        '</tr>'+				
    '</table>';
}

$(document).ready(function() {
    var table = $('#example').DataTable( {
        "ajax": data,
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "Input_Seq" },
            { "data": "Cut_Off" },
            { "data": "Best_Hit_Bitscore" },
            { "data": "Best_Hit_ARO" },
			{ "data": "Best_Identities" },
			{ "data": "SNPs_in_Best_Hit_ARO" },
	        { "data" : "Pass_Bitscore", "visible": false   },			
	        { "data" : "Best_Hit_ARO", "visible": false   },
	        { "data" : "Drug_Class", "visible": false   },
	        { "data" : "Resistance_Mechanism", "visible": false   },
	        { "data" : "AMR_Gene_Family", "visible": false   },
	        { "data" : "Predicted_Protein", "visible": false   },	        	        	        	        
	        { "data" : "CARD_Protein_Sequence", "visible": false   }	        
	        
        ],
        "order": [[1, 'asc']]
    } );
     
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

<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
							<div class="space"></div>
							<div class="tabs-style-2">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs" role="tablist">
									<li class="active"><a href="#h2tab1" role="tab" data-toggle="tab"><i class="fa fa-home pr-5"></i>RGI Output against CARD</a></li>
								</ul>
								<!-- Tab panes -->
								<div class="tab-content">
									<div class="tab-pane fade in active" id="h2tab1">
										<div class="space-bottom"></div>
										<div class="row">
											<div class="col-md-8">
<?php	
	echo "<h4>Download the RGI Output text file with complete details obtained from CARD</h4>";
?>											
											</div>
											<div class="col-md-4">
<?php	
	echo "<a href='http://cdc-1.jcvi.org:8081/tmp/$ran/out.txt' class='btn btn-primary'>Output file <i class='fa fa-floppy-o pl-10'></i></a>";
?>
											</div>
										</div>
									</div>
								</div>
							</div>

					
<?php
$dirfilep = "$dir/out.json";
if (file_exists($dirfilep)){

#	shell_exec("python /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/rgi-4.0.2/rgi main -i $dir/input_userx.fasta -o $dir/out -t protein -n 4 --clean --include_loose");
	shell_exec("perl /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/convert_ajax.pl $dir/out.txt | perl -pi -e \"s/'/\\\"/g\" | | perl -pi -e \"s/\*/'/g\" > $dir/ajax.txt");
#	sleep(10);
#	  echo "<br>perl /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/scripts/convert_ajax.pl out.txt | perl -pi -e \"s/'/\\\"/g\"> $dir/ajax.txt</br>";

#	echo "<pre>";	include ("$dir/out.txt"); echo "</pre>";
	echo '<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>Input Seq</th>
                <th>Cut Off</th>
                <th>Best Hit Bitscore</th>
                <th>Best Hit ARO</th>
                <th>Best_Identities</th>
                <th>SNPs_in_Best_Hit_ARO</th>
                <th>Pass_Bitscore</th>                
                <th>Best_Hit_ARO</th>    
                <th>Drug_Class</th>    
                <th>Resistance_Mechanism</th>    
                <th>AMR_Gene_Family</th>    
                <th>Predicted_Protein</th>    
                <th>CARD_Protein_Sequence</th>    
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>Input Seq</th>
                <th>Cut Off</th>
                <th>Best Hit Bitscore</th>
                <th>Best Hit ARO</th>
                <th>Best_Identities</th>
                <th>SNPs_in_Best_Hit_ARO</th> 
                <th>Pass_Bitscore</th>            
                <th>Best_Hit_ARO</th>    
                <th>Drug_Class</th>    
                <th>Resistance_Mechanism</th>    
                <th>AMR_Gene_Family</th>    
                <th>Predicted_Protein</th>    
                <th>CARD_Protein_Sequence</th>                        
            </tr>
        </tfoot>
    </table>';
	
	}
else {
	echo "<h1 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes. <br> "; echo "<h1 align='center'> OR <br>"; echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/rgioutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/rgioutput.php?ran=$ran </a> </h1>";
	echo "<meta http-equiv='refresh' content='5;url=rgioutput.php?ran=$ran'>";
}	
		
?> 
			</div>
	    </div>
   </div>  
</section>
<?php include 'includes/footerx.php';?>


