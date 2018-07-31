<?php include 'includes/header.php';
$ran = $_GET["ran"];
// modify this for a dummy file
$dir = "tmp/$ran"; // used to be 
#$dir="tmp/46523";

/*
1. get the file information from the submitseq and get the id and other prameter
2. use solr to produce the output data. then add the information from the file into the 
accoding json field. 
3. redirect to the blastoutput.php
*/
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
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
	


</style>
<style type="text/css" class="init">
    
td.details-control {
    background: url('images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('images/details_close.png') no-repeat center center;
}

</style>

<?php
/*This php script add additional information to the 
  data that we recieved from Solr*/
$dirfilep = "$dir/outx/_summary_file.txt";
// die();
if (file_exists($dirfilep)){
        $file = fopen($dirfilep,"r") or die('No file');
        feof($file);
        $line1 = fgets($file);
        $local_id= preg_split('/\s+/', $line1);
        $line2 = fgets($file);
        $split_string = explode('|',$line2);
        $counter=0;
        for ($i=0; $i<sizeof($local_id) ; $i++) { 
            if($i!=0&&$i!=sizeof($local_id)-1){
                $data_string[$local_id[$i]][]=$local_id[$i];
                    for ($q=0; $q <5; $q++) { 
                    // take fist five entries
                        $data_string[$local_id[$i]][]=$split_string[$counter];
                        $counter++;        
                     }
             }
        }
        fclose($file);
    	foreach (array_keys($data_string) as $value) {
        $dirfil_complex = "$dir/outx/alignments/".$value."_alignments.phy";
        $cdata[$value]='<h2>Sequence Alignment</h2><pre>';
        $cfile = fopen($dirfil_complex,"r") or die('No file');
        // var_dump($cfile);
        // array_shift($cfile);
        $i=0;
        while (!feof($cfile)) {
         # code...

           $cline = fgets($cfile);
        if($i==0){
            // echo "Here";
            $i++;
            continue;
          }else{
           $cdata[$value].= $cline;
          }
        }
       $cdata[$value].="</pre>";
      }
  }


  /*check if the input file is generated.
   if it is generated check if it has hits found. 
   will end the page once there is no hits found.*/ 

  $input_fasta_xml="$dir/outx/input_user.fasta.xml";

  if(file_exists($input_fasta_xml)){
      //
    // echo "Here";
     $xfile = fopen($input_fasta_xml,"r");
     while (!feof($xfile)) {
      // $line=fgets($xfile)."<br>";
        // echo "string";
      // echo $line;
      if (strpos(fgets($xfile), 'No hits found')) {
               echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo "<h2 align='center'><b><i>No hit found againt the ARM-DB. Please try again with a different sequence</i></b></h2>";
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        echo'<br>';
        include 'includes/footerx.php';
 
        die();
      }
     }


  }

?>



<script>
	// Here the json file will be created then, edit the json file 
	// add more field to it.
    var cdata_file = <?php echo json_encode($cdata, JSON_HEX_TAG);; ?>;
    // alert(cdata_file);
    var data_id = <?php echo json_encode($data_string, JSON_HEX_TAG);; ?>; //Don't forget the extra semicolon!
     var id_query='';
     for(let v in data_id ){
        id_query+='id:'+v +" OR " ;
     }
    id_query = id_query.substring(0, id_query.length - 3);
</script>

<script type="text/javascript" class="init">

$(document).ready(function() {
    function format ( d ) {
    let rt_string ='<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    if(d.ComplexFile!=null&&(typeof d.ComplexFile != "undefined")&&(d.ComplexFile.trim()!="")){
       rt_string+='<tr><td style="padding-left:10em"></td><td>'+ d.ComplexFile+'</td></tr>';
    }
    rt_string+='</table>';
    return  rt_string;
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
        "ajax":function(data,callback, setting){
             $.ajax( {
                 "url": "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select",
                 "type":"GET",
                 "data": $.extend( {}, data, {'wt':'json', 'q':'('+id_query+') AND ( id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'* OR Protein_ID:*'+data.search.value+'* OR Taxon_Genus:*'+data.search.value+'*)' ,'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),'rows':data.length}),
                 "dataType": "jsonp",
                 "jsonp":"json.wrf",
                 "success": function(json) {
                    // callback function 
                   var o = {
                         recordsTotal: json.response.numFound,
                         recordsFiltered: json.response.numFound,
                         data: json.response.docs
                       };   
                   // insert evale and others local data into the data requested from solr
                    
                    // console.log(o.data);
                    for (let v in o.data){
                        o.data[v]['Identity']=data_id[o.data[v].id][1]+'%';
                        o.data[v]['Similarity']=data_id[o.data[v].id][2]+'%';
                        o.data[v]['E_Value']= data_id[o.data[v].id][4];
                        o.data[v]['ComplexFile']= cdata_file[o.data[v].id];
                        console.log('adding data to :'+cdata_file[v]);
                    }
                    callback(o);
                 }
               } );
        },
        //    dom: 'lBfrtip',
        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ],
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
             { "data": "Identity",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = data ;
                }
                return data;
                }
            },
             { "data": "Similarity",
              "render": function(data, type, row, meta){
                if(type === 'display'){
                    data = data ;
                }
                return data;
                }
            },               
            { "data": "E_Value" ,
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
                        data=new_d.map(x=> x).join();;
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
            },    
            { "data" : "ComplexFile", 
             "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data =  data ;
                    }
                    return data;
                    } ,"visible": false   }       
        ],
        // "order": [[ 0, 'desc' ], [ 1, 'desc' ]] // will not work on serverside fetching
    });
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
         
            
<?php
$dirfilep = "$dir/outx/_summary_file.txt";
if (file_exists($dirfilep)){

?>
   <div class="main col-md-12">
              <div class="space"></div>
              <div class="tabs-style-2">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="active"><a href="#h2tab1" role="tab" data-toggle="tab"><i class="fa fa-home pr-5"></i>BLAST Output against AMRdb</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane fade in active" id="h2tab1">
                    <div class="space-bottom"></div>
                    <div class="row">
                      <div class="col-md-12">
<?php 
  echo "<p align='justify'>The BLAST output results are combined with the metadata of genes/proteins present in AMRdb and are displayed below in a tabular format. All the columns of the table are sortable as well as searchable. For alignment between the user sequence and the AMR-db sequence, click on the (+) sign in front of Identity ID.<p>";
?>                      
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
<?php
	echo '<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
               <th></th>
                <th>Identity ID</th>
                <th>Identity</th>
                <th>Similarity</th>
                <th>E_Value</th>
                <th>Drug Family</th>
                <th>Organism</th>
                <th>Protein_Name</th>
                <th>Protein ID</th>
                <th>More information</th>
            </tr>
        </thead>
        </tfoot>
    </table>';
	}
else {
	echo "<h1 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes. <br> "; 
	echo "<h1 align='center'> OR <br>";
	echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran </a> </h1>";
	echo "<meta http-equiv='refresh' content='5;url=blastoutput.php?ran=$ran'>";
}	
		
?> 
			</div>
	    </div>
   </div>  
</section>
<?php include 'includes/footerx.php';?>

