<?php include 'includes/header.php';
$ran = $_GET["ran"];
$pid= $_GET["pid"];

$newpid=exec("ps -aef | grep $pid | grep -v python | grep -v 'grep' | perl -pi -e 's/\s+/\t/g' | cut -f2");

$dir="tmp/$ran";

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
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
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
// $var = 'ABCDEFGH:/MNRPQR/';
// echo substr_replace($var, '<mark>'."A".'</mark>', 1,0) . "<br />\n";
// echo $var.'<br>';
// $t_s="ABCD";
// echo strlen($t_s);
// $t_s[strlen($t_s)-1]="E";
// echo $t_s;

/*This php script add additional information to the 
  data that we recieved from Solr*/
  


  // if pid is empty then here otherwise
if($newpid==''){

#$dirfilep = "$dir/outx/*_summary_file.txt";
$dirfilepx = glob("$dir/outx/*_summary_file.txt"); #print_r ($dirfilepx); echo "******NNNNNNNNN*******<br>";
$dirfilep = $dirfilepx[0];
// die();
#if (file_exists($dirfilep)){  echo "******NNNNNNNNN*******<br>"; 
if ($dirfilepx[0] != ''){  
        $file = fopen($dirfilep,"r") or die('No file');
        feof($file);
        $line1 = fgets($file);
        $local_id= preg_split('/\s+/', $line1);
        $line2 = fgets($file);
        // $split_string = explode('|',$line2);
        $split_by_tab = preg_split('/\s+/', $line2);
        array_shift($split_by_tab);

        for ($i=0; $i<sizeof($local_id) ; $i++) { 
            if($i!=0&&$i!=sizeof($local_id)-1){
                $data_string[$local_id[$i]][]=$local_id[$i];
				        $detail_string = explode('|',$split_by_tab[$i-1]);
                    for ($q=0; $q <count($detail_string); $q++) { 
                        $data_string[$local_id[$i]][]=$detail_string[$q];
                        // echo $split_string[$q]."<br>";
                        // $counter++;        
                     }
             }
        }
        fclose($file);
        $tmp_data =$data_string;
        // $prepare_solr_data['job_id']=$ran;
        // foreach ($data_string as $key => $value) {
        //   $prepare_solr_data['id']=$ran;
        //   $prepare_solr_data['id_id'][]=$value[0];
        //   $prepare_solr_data['Identity'][]=$value[1];
        //   $prepare_solr_data['E_Value'][]=$value[4];
        //   $uid= explode(':', $value[3]);
        //   $prepare_solr_data['User_ID'][]=$uid[0];
        //   $total_d[]=$prepare_solr_data;
        // }



      // print_r($tmp_data);
      // echo "<br>";
        // hight light snp 
    	foreach (array_keys($data_string) as $value) {
        $dirfil_complex = "$dir/outx/alignments/".$value."_alignments.phy";
        $cdata[$value]='<h2>Sequence Alignment</h2><pre>';
        $cfile = fopen($dirfil_complex,"r");
        $cfiles = file($dirfil_complex);
        $i=0;
        preg_match_all('/(\d+)/m',$data_string[$value][6],$target_letter_pos);
        $seq_number=explode ('/', $data_string[$value][3]);
        $seq_number=sizeof($seq_number)+1;

        $line_words=1;
        $line_number=0;
        for ($x=1;$x<=sizeof($cfiles);$x++) {
            $cline = $cfiles[$x];
	          $cline = str_replace(array("\n","\r\n","\r"), '<br>', $cline);
                    if((trim($cline)!='')){
                        if(!($line_number%$seq_number)){
                            rsort($target_letter_pos[0]);
                           foreach ($target_letter_pos[0] as $pos) {
                            // if it is negtive and not excess 50  then current pos will be within this range.
                            $diff=$line_words-$pos;
                              if(($diff<0)&&($diff>-50) ){
                                // echo "total size: ".strlen($cline) ;
                                $extra_space=$diff/10;
                                $extra_space= ceil(abs($extra_space));
                                $replay_str = "<span style='background-color:#0080FF; color:white;'>".$cline[strlen($cline)+1*$extra_space+$diff]."</span>";
                                $replay_pos =  (strlen($cline)+1*$extra_space+$diff);
                                 // echo "<br>inseting: ".$pos."-" .$replay_str." at pos: ".$replay_pos." extra :".$extra_space."diff: ".$diff."<br>";
                                $cline =substr_replace($cline, $replay_str,$replay_pos,1);
                              }               
                           }
                        $line_words=$line_words+50;
                    }
                    $line_number++;
                  }
		            //$cline=$cline."\n";
                $cdata[$value].= $cline;

        }
        //fclose($handle);
        // echo '<b>'.$line_words.'</b>';
       $cdata[$value].="</pre>";
      }



        foreach ($tmp_data as $key => $value) {
          $prepare_solr_data['id_id'][]=$value[0];
          $uid=explode(':', $value[3]);
          $value[3]=$uid[0];
        }


        $search_ids= implode(' OR ', $prepare_solr_data['id_id']);
        $search_ids='('.$search_ids.')';
        // var_dump($prepare_solr_data);



    // get the total number first then get all the records
      $post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "id:$search_ids"  }'`;
      $js= json_decode($post_fetch);
      $max_row_no=$js->response->numFound;
      $post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "id:$search_ids" ,fields:["id","Gene_Symbol","Allele","Protein_Name","Protein_ID","Drug_Family", "NA_Sequence","AA_Sequence"] ,limit : $max_row_no }'`;
      $js= json_decode($post_fetch);
      $readytowrite_data=$js->response->docs;
      
      $write_local_file="{\"data\":[";
          foreach ($readytowrite_data as $obj ) {
          $uid=explode(':', $tmp_data[$obj->id][3]);
          $write_local_file.="{"."\"id\":\"".$obj->id."\",\"UserID\":\"".$uid[0]."\","."\"Identity\":\"".$tmp_data[$obj->id][1]."\",";
          $write_local_file.="\"E_Value\":\"".$tmp_data[$obj->id][4]."\",";
          $write_local_file.="\"Gene_Symbol\":\"".$obj->Gene_Symbol."\",";
          $write_local_file.="\"Allele\":\"".$obj->Allele."\",";          
          $write_local_file.="\"Protein_Name\":\"".$obj->Protein_Name."\",";
          $write_local_file.="\"Protein_ID\":\"".$obj->Protein_ID."\",";
          $write_local_file.="\"Drug_Family\":\"".$obj->Drug_Family."\",";
          $write_local_file.="\"child_snp\":\"".$tmp_data[$obj->id][6]."\",";
          $write_local_file.="\"ComplexFile\":\"".$cdata[$obj->id]."\",";
          $write_local_file.="\"Snp_view\":\"<a href=".$dir."/outx/alignments/".$obj->id."_hit.html target=_blank>link text</a>\"";

          $write_local_file.="},";

          // var_dump($obj->NA_Sequence);

          // write AA fasta and NA fasta 
          $write_NA_fasta_file=">".$obj->id."\n";
          $write_NA_fasta_file.=$obj->NA_Sequence;

          $myNAfile = fopen($dir."/".$obj->id."_NA.fasta", "w");
          fwrite($myNAfile, $write_NA_fasta_file);
          fclose($myNAfile);

          $write_AA_fasta_file=">".$obj->id."\n";
          $write_AA_fasta_file.=$obj->AA_Sequence;

          $myAAfile = fopen($dir."/".$obj->id."_AA.fasta", "w");
          fwrite($myAAfile, $write_AA_fasta_file);
          fclose($myAAfile);

          

      }
      $write_local_file.="]}";
      $write_local_file = str_replace('},]', '}]', $write_local_file);
      
        $myfile = fopen($dir.'/data.json', "w");
        fwrite($myfile, $write_local_file);
        fclose($myfile);
      // create zip file to download
      $cur_dir = getcwd();
      // $new_dir=getcwd()."/$dir/outx/alignments";
      // echo "<br>$cur_dir<br>";
      // chdir($new_dir);
      // // zip allignment files 
      // `zip -r alignments.zip *.phy`;
      // // zip NA AA fasta file 
      // chdir($cur_dir);
      $new_dir2=getcwd()."/$dir";
      chdir($new_dir2);
      `zip -r Sequence_Alignment.zip *.fasta outx/alignments/*.phy`;
      chdir($cur_dir);
      // $zip_path = "/$dir/outx/alignments/alignments.zip";
      $zip_path_seq = "/$dir/Sequence_Alignment.zip";
  }


  /*check if the input file is generated.
   if it is generated check if it has hits found. 
   will end the page once there is no hits found.*/ 

  $result_dir="$dir/outx";
  $filep=$filen="";
  $filep=file_exists("$dir/outx/input_user.fasta.xml");
  $filen=file_exists("$dir/outx/input_user_translated.fa.xml");
  if($filep == "1") { $input_fasta_xml="$dir/outx/input_user.fasta.xml";}
  if($filen == "1") { $input_fasta_xml="$dir/outx/input_user_translated.fa.xml";}
#  $rrna ="$dir/outx/*_rRNA_summary_file.txt";
  #$summary_file="$dir/outx/*_summary_file.txt";
  $combine_file="$dir/outx/*combined_summary_file.txt"; 
  $summary_file = $dirfilepx[0];

    if($dirfilepx[0] == ''){
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br>';
        echo "<h2 align='center'><b><i>There are no BLAST results that meet the cutoffs. Ensure that your inputs are correct (Nucleotide or Peptide File)</i></b></h2>";
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
        include 'includes/footerx.php';
        die();        
      no_output_response('error'); 
    }
    if(file_exists($result_dir)){
      if($dirfilepx[0] == ''){
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br>';
        echo "<h2 align='center'><b><i>There are no BLAST results that meet the cutoffs. Ensure that your inputs are correct (Nucleotide or Peptide File)</i></b></h2>";
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
        include 'includes/footerx.php';
        die();          
          no_output_response('error'); 
        }
       #if((!file_exists($rrna))&&(!file_exists($summary_file))&&(!file_exists($combine_file))){
         #no_output_response('error'); 
       #}
      // echo "Here";
       
  	//check if the xml file has no hits/
       // $xfile = fopen($input_fasta_xml,"r");
       // while (!feof($xfile)) {
       //  if (strpos(fgets($xfile), 'No hits found')) {
       //    no_output_response('not hit');
       //  }
       // }
           // chekc if output file and _rRNA file exist if not no hit
       // echo "file_exists($rrna) :".file_exists($input_fasta_xml);

    }
   function no_output_response($error_msg){
    if($error_msg!='not hit'){
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br>';
        echo "<h2 align='center'><b><i>There are no BLAST results that meet the cutoffs. Ensure that your inputs are correct (Nucleotide or Peptide File)</i></b></h2>";
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
        include 'includes/footerx.php';
        die();
    }else{
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br>';
        echo "<h2 align='center'><b><i>No hit found againt the ARM-DB. Please try again with a different sequence</i></b></h2>";
        echo'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
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
    var zip_path = <?php echo json_encode($zip_path, JSON_HEX_TAG);; ?>;
    var zip_path_seq = <?php echo json_encode($zip_path_seq, JSON_HEX_TAG);; ?>;

    // alert(zip_path);
    // console.log(cdata_file);
    var data_id = <?php echo json_encode($data_string, JSON_HEX_TAG);; ?>; //Don't forget the extra semicolon!
     var id_query='';
     for(let v in data_id ){
        id_query+='id:'+v +" OR " ;
     }
    id_query = id_query.substring(0, id_query.length - 3);
</script>
<script>
    var data = <?php echo json_encode("$dir/data.json", JSON_HEX_TAG); ?>; //Don't forget the extra semicolon!
</script>

<script type="text/javascript" class="init">

function format ( d ) {

  let return_cells='<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

  if(d.child_snp!=""){
      return_cells+=
        '<tr>'+
            '<td>SNP:</td>'+
            '<td>'+d.child_snp+'</td>'+
        '</tr>';
  }
  if(d.ComplexFile!=""){
       return_cells+='<tr>'+
            '<td>Alignment:</td>'+
            '<td>'+d.ComplexFile+'</td>'+
        '</tr>';        
  }
   if(d.child_snp!=""){
       return_cells+='<tr>'+
            '<td>Snp View:</td>'+
            '<td>'+d.Snp_view+'</td>'+
        '</tr>';        
  }
  return_cells+='</table>';
  return return_cells;
}
$(document).ready(function() {
    var table = $('#example').DataTable({
        "ajax": data,
        // "processing":    true, //  Enable or disable the display of a 'processing' indicator when the table is being processed
                               // This is particularly useful for tables with large amounts of data where it can take a noticeable amount of time to sort the entries.
        // "serverSide":    true, 
        // "bLengthChange": false, // disable page entities
        // "searching":     false,       // disable search
        // "stateSave":     true,// when refresh stay on the same page 
        //"ajax": '../ajax/data/arrays.txt'
        // "displayLength" : 0,
        // "ajax":function(data,callback, setting){
        //      $.ajax( {
        //          "url": "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select",
        //          "type":"GET",
        //          "data": $.extend( {}, data, {'wt':'json', 'q':'('+id_query+') AND ( id :*'+data.search.value+'* OR Gene_Symbol:*'+data.search.value+'* OR Drug_Family:*'+data.search.value+'* OR Taxon_ID:*'+data.search.value+'* OR Taxon_Species:*'+data.search.value+'* OR Protein_Name:*'+data.search.value+'* OR Protein_ID:*'+data.search.value+'* OR Taxon_Genus:*'+data.search.value+'*)' + " AND Is_Active:1" ,'sort':(data.columns[data.order[0].column].data==null?'': data.columns[data.order[0].column].data+' '+data.order[0].dir),'rows':data.length}),
        //          "dataType": "jsonp",
        //          "jsonp":"json.wrf",
        //          "success": function(json) {
        //             // callback function 
        //            var o = {
        //                  recordsTotal: json.response.numFound,
        //                  recordsFiltered: json.response.numFound,
        //                  data: json.response.docs
        //                };   
        //            // insert evale and others local data into the data requested from solr
                    
        //             // console.log(o.data);
        //             for (let v in o.data){
        //                 o.data[v]['Identity']=data_id[o.data[v].id][1]+'%';
        //                 o.data[v]['E_Value']= data_id[o.data[v].id][4];
        //                 o.data[v]['ComplexFile']= cdata_file[o.data[v].id];
        //                 let user_id="";
        //                 for (var i = 0; i < data_id[o.data[v].id][3].split('/').length; i++) {
        //                   user_id+=" "+data_id[o.data[v].id][3].split('/')[i].match(/[^/:]*/i)[0];
        //                 }
        //                  o.data[v]['UserID']=user_id;
        //                  o.data[v]['child_snp']=data_id[o.data[v].id][6]!='' ? data_id[o.data[v].id][6] :" ";
        //                 // o.data[v]['child_snp']= data_id[o.data[v].id][3].match(/[^/:]*/i)[0];
        //                 // console.log('adding data to :'+data_id[o.data[v].id][6]);

        //             }
        //             callback(o);
        //             console.log(o.data);

        //          }
        //        } );
        // },
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "id",
            "render": function(data, type, row, meta){
                if(type == 'display'){
                    data = '<a href=singlex.php?id=' + data + ' target=_blank>' + data + '</a>';
                }
                return data;
                }


             },
            { "data": "UserID" },
            { "data": "Identity"},
            { "data": "E_Value"},
            { "data": "Gene_Symbol"},
            { "data": "Protein_Name"},
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
                        data=new_d.map(x=> x).join();;
                    } 
                        return data;
                    }  
            },            
            // show SNP if Present after 5th pipe child 
                { "data" : "child_snp", 
             "render": function(data, type, row, meta){
                     if(data === 'undefined'||data==null||data.trim()=='' ){
                      // console.log("hi");
                        return "";    
                    }
                    if(type === 'display'){
                        data =  data;
                    }
                      console.log(data);

                    return data;
                    } ,"visible": false ,"hideIfEmpty": true   },     
            { "data" : "ComplexFile", 
             "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data =  data;
                    }
                    return data;
                    } ,"visible": false   }  ,
            { "data" : "Snp_view", 
             "render": function(data, type, row, meta){
                     if(data === 'undefined'||data ==null ){
                        return "";    
                    }
                    if(type === 'display'){
                        data =  data;
                    }
                    return data;
                    } ,"visible": false   }      
        ],
         // hideEmptyCols: true
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

#$dirfilep = "$dir/outx/*_summary_file.txt";
if ($dirfilepx[0] != ''&&$newpid==''){

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
  echo "<p align='justify'>The best hit based on AMR-Finder result is shown for each query sequence combined with the metadata of the AMRdb hit. All the columns of the table are sortable and searchable. To display the alignment between the query sequence and the AMRdb best hit sequence, click on the (+) sign in front of Identity ID.<p>  <div id='btn_container'><button class='dt-button buttons-copy buttons-html5'style='float:right' id='download_btn' onclick='downloadAllignment()'>Download Alignment</button>
                  </div>";
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
                <th>User ID</th>
                <th>Identity</th>
                <th>E_Value</th>
                <th>Gene Symbol</th>
                <th>Protein Name</th>
                <th>Protein ID</th>
                <th>Drug Family</th>                
                <th>Snp</th>
                <th>Sequence Alignment</th>
                <th>Snp View</th>

            </tr>
        </thead>
    </table>';
	}
else {
  echo '<p align="center"> <img src="images/wait.gif"  class="center" alt="Please Wait"><br></p>'; 
	echo "<h1 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes. <br> "; 
	echo "<h1 align='center'> OR <br>";
	echo "Visit the following link <br><a href='http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran'>http://cdc-1.jcvi.org:8081/blastoutput.php?ran=$ran&pid=$pid</a> </h1>";
	echo "<meta http-equiv='refresh' content='5;url=blastoutput.php?ran=$ran&pid=$pid'>";
}	
		
?> 
			</div>
	    </div>
   </div>  
</section>
<script type="text/javascript">
  function downloadAllignment(){
    // window.open(zip_path);
    window.open(zip_path_seq);
    // alert(zip_path_seq);
  }

// console.log(document.getElementById('btn_container'));
</script>
<?php include 'includes/footerx.php';?>

