<!-- 
    Author: Ye 08/17/2018 
    this page gather the input from validate_primer.php and display Primer as a datatable

 -->


<?php include 'includes/header.php';?>
<!-- <script type="text/javascript" src="./10321_annoted.js"></script> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"> -->
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<?php


    function exit_die($messages){
        
        echo "<br><br><br><br><h2 align='center'><b><i>".$messages."</i></b></h2><br><br><br><br><br><br><br><br><br><br><br><br>";
        include 'includes/footer.php';
            die();
    }
    function valid_file_upload($fileName){
        $name = explode('.', $_FILES[$fileName]['name']);
        if($name[count($name)-1]=='txt'||$name[count($name)-1]=='fasta'||$name[count($name)-1]=='fa'){
            return 1;
            }
            return 0;
        }
    if (isset($_POST['method'])) {
        # code...

        if(trim($_FILES['forward_seq_fileToUpload']['name'])==''&&trim($_POST['primer_f_seq_txtfield'])==''){

            exit_die("Please either upload a file or paste paste primary sequence in the textarea box");
        }
        // check file upload inputs
    
        if(!valid_file_upload('forward_seq_fileToUpload')&&trim($_FILES['forward_seq_fileToUpload']['name'])!=''){
            exit_die("Please upload correct file format");
        }
        // check if file upload has error
        if(trim($_FILES['rev_seq_fileToUpload']['name'])==''&&trim($_POST['primer_r_seq_txtfield'])==''){

            exit_die("Please either upload a file or paste paste primary sequence in the textarea box");
        }
        // check file upload inputs
    
        if(!valid_file_upload('rev_seq_fileToUpload')&&trim($_FILES['rev_seq_fileToUpload']['name'])!=''){
            exit_die("Please upload correct file format");
        }

        // generate a temp folder consist random numbers to store input and output files 

        $ran= rand(100, 100000);
        $dir = "/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran";
        $output_path="/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out";
        exec("mkdir $dir");
        exec("chmod 777 $dir"); 
         exec("mkdir $output_path");
        exec("chmod 777 $output_path"); 
    
    
    // use a string to check the input type. 
    $f_prime_input_file_type =trim($_POST['primer_f_seq_txtfield'])!='' ?'text':'file';
    $r_prime_input_file_type =trim($_POST['primer_r_seq_txtfield'])!=''?'text':'file';


    //when forward input is a file point to the file. 
    // if not create a local file using input text. then point to the file.
    $f_input_path='';
    $r_input_path='';
    $green_light_to_excute=0;


    // if the user uploaded a file, then move the file to correspond directory. 
    // if the user 

    if($f_prime_input_file_type=='file'){

        $tmp_name = $_FILES["forward_seq_fileToUpload"]["tmp_name"];
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["forward_seq_fileToUpload"]["name"]);
        echo "move ---------    ".$name.' to :  -------  '."/usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/".$name.'<br>';
        $succ= move_uploaded_file($tmp_name, "$dir/$name");
        $green_light_to_excute++;
        $f_input_path="$dir".$_FILES['forward_seq_fileToUpload']['tmp_name'];
    }else{
        $my_file_f = "$dir/frd_primer.fasta";
        $handle = fopen($my_file_f, 'w') or die('Cannot open file:  '.$my_file_f);
        $data =$_POST['primer_f_seq_txtfield'];
        fwrite($handle, $data);
        $green_light_to_excute++;

    }   
    if($r_prime_input_file_type=='file'){
        $tmp_name = $_FILES["rev_seq_fileToUpload"]["tmp_name"];
        $name = basename($_FILES["rev_seq_fileToUpload"]["name"]);
        $r_input_path="$dir".$_FILES['rev_seq_fileToUpload']['tmp_name'];
        move_uploaded_file($tmp_name, "$dir/$name");
        $green_light_to_excute++;
    }else{
        $my_file_r = "$dir/rvs_primer.fasta";
        $handle = fopen($my_file_r, 'w') or die('Cannot open file:  '.$my_file_r);
        $data =$_POST['primer_r_seq_txtfield'] ;
        fwrite($handle, $data);
        $green_light_to_excute++;

    }

    // execute cmd to run jobs

    // if(isset($_POST['reference_genebank'])){
    //  if($green_light_to_excute==2){
    //   $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --genbank_id ".trim($_POST['reference_genebank'])." --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output /usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out/ --email yewang@jcvi.org"); 
    //  }
    // }elseif(isset($_POST['reference_fastafile'])){
    //   $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database $dir/$user_file --forward_primers $my_file_f --reverse_primers $my_file_r --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output /usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out/ --email yewang@jcvi.org"); 
    // }else{
    //  if($green_light_to_excute==2){
    //           $cmdresponse=shell_exec("python /usr/local/projdata/8500/projects/CDC/server/pcr_validator/pcr_validator.py --database /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/dbs/amr_dbs/amrdb_nucleotides.fasta --forward_primers $dir/frd_primer.fasta --reverse_primers $dir/rvs_primer.fasta --simulate_PCR /usr/local/projdata/8500/projects/CDC/server/pcr_validator/simulate_PCR.pl --output /usr/local/projdata/8500/projects/CDC/primer_examples/tmp/$ran/out/ --email yewang@jcvi.org"); 
    //  }
    // }


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
$data = `cat Tabular_Output2.tsv | perl -pi -e 's/\t/","/g' | perl -pi -e 's/^/["/g' | perl -pi -e 's/\n/"],\n/g'`; 
?>

<script type="text/javascript" class="init">

$(document).ready(function() {



    var table = $('#example').DataTable( {
        ajax: "data.json", // expecting a file   we can use data then  format of the data will be [  [1,2,3], [2,3,4]]  https://datatables.net/examples/data_sources/js_array.html
        // data: dataset,

        "columns": [
            // {
            //     "className":      'details-control',
            //     "orderable":      false,
            //     "data":           null,
            //     "defaultContent": ''
            // },
            //   { title: "amplicon_len" },
            // { title: "HitName" },
            // { title: "FP_ID" },
            // { title: "FP_seq" },
            // { title: "RP_ID" },
            // { title: "RP_seq" }
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
            { "data": "FP_seq",
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
        { "data": "RP_seq",
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
<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
            <h2 class="title">Intro ........</h2>
            <div class="separator-2"></div>
                <div style="line-height: 150%;"> <p style="text-align:justify"; >description..... <a href="help.php#location">help page</a>.</p><hr></hr>
                </div>

<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <!-- <th></th> -->
                <th>amplicon_len</th>
                <th>HitName</th>
                <th>FP_ID</th>
                <th>FP_seq</th>
                <th>RP_ID</th>
                <th>RP_seq</th>
            
            </tr>
        </thead>  
        <tfoot>
            <tr>
                <!-- <th></th> -->
                <th>amplicon_len</th>
                <th>HitName</th>
                <th>FP_ID</th>
                <th>FP_seq</th>
                <th>RP_ID</th>
                <th>RP_seq</th>                    
            </tr>
        </tfoot>
    </table>
            </div>
        </div>
   </div>  
</section>
<?php include 'includes/footerx.php';?>
