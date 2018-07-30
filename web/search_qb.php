<?php include 'includes/header.php'; 
/*  author: Ye Wang
    This script is to search data
*/
if(!isset($_POST["submit"])){

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


<!-- no Organism ID  -->
<?php
    $ls = ['Select Field','Protein ID','Gene Symbol','Drug Family','Taxon Species','GenBank ID','Parent Allele','Parent Allele_Family','Allele','Drug Class','Drug_Sub Class','Taxon Phylum','Taxon Class','Taxon Order','Taxon Family','Taxon Genus','Taxon Strain','Plasmid','BioProject ID','Biosample'];
    $ls_string='<SELECT id=fn[] NAME="field_name[]">';
    // $place_holder=['e.g. ADH03009.1','e.g. AAC(1)','e.g. Aminoglycoside','e.g. baumannii','e.g. KX531051','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.','e.g.'];
    foreach ($ls as $item) {
        $new_item=$item;

        if($item=='GenBank ID'){
          $new_item ='Source_ID';    
        }

    	$new_val = str_replace(' ', '_', $new_item);
        $ls_string.='<OPTION class="inval"  VALUE='.$new_val.'>'.$new_item.'';    
    }   
    $ls_string.='</SELECT>';

    $ls_operator='<SELECT NAME="field_operator[]">
                        <OPTION VALUE="=">  =
                        <OPTION VALUE=">="> >= 
                        <OPTION VALUE="<="> <=
                        <OPTION VALUE="like">LIKE
                        <OPTION VALUE="nlike">NOT LIKE
                  </select>';
    $ls_andOR='<SELECT NAME="andORor_[]">
                    <OPTION VALUE="AND">AND 
                    <OPTION VALUE="OR">OR 
               </select>';
?>
<style type="text/css">
.red_button {
    background: white; 
    border-radius: 5%; 
    color: #e84c3d; 
    border: 
}

.red_button:hover {
    color: white;
    background-color: #e84c3d;
    border: .15em white solid;
    box-shadow: none;
}
</style>
<script>
    var field_name = <?php echo json_encode("$ls_string", JSON_HEX_TAG); ?>; //Don't forget the extra semicolon!
    var field_opt = <?php echo json_encode("$ls_operator", JSON_HEX_TAG); ?>; 
    var field_andOr = <?php echo json_encode("$ls_andOR", JSON_HEX_TAG); ?>; 
</script>
<script type="text/javascript">
        //form validation before submit 
    function validateForm() {
      var selected = $('select[name="field_name[]"]').map(function(){
    // get the list for input select field
      if ($(this).val()){
         return $(this).val();
      }
    }).get();

      if(selected.includes("Select_Field")){
        alert('Please Select an option: '+selected);
        // alert(selected);
        return false;
      }
    }



</script>
<html ng-app="dynamicFieldsPlugin">
  <body>
        <div ng-controller="dynamicFields" class="container">

          <?php echo "<form name='myForm' id='myForm' action='{$_SERVER[PHP_SELF]}' onsubmit='return validateForm()' method=post > ";?>

            <h1>Dynamic Form Fields Creation Plugin</h1>
            <TABLE class ="table table-striped" ID="tblPets" border="1">
                 <thead class="thead-dark">
                <TR><TH scope="col">Field Name</TH><TH scope="col" WIDTH="70">Operator</TH><TH scope="col" WIDTH="230">Values</TH><TH scope="col" WIDTH="80">And / Or </TH scope="col"><TH ALIGN="center"><INPUT TYPE="Button" class="red_button"onClick="addRow('tblPets')" VALUE="+ Add"></TH></TR> </thead>
                <!-- three field -->
                <TR><TD><?php echo $ls_string; ?></TD><TD><?php echo $ls_operator; ?></TD><TD><input type="text"  name ="in_val[]" placeholder="e.g.AA123" required="required"></TD><TD><?php echo $ls_andOR;?></TD><TD><INPUT TYPE="Button" CLASS="Button" onClick="delRow()" VALUE="Delete Row"></TD></TR>
              
              
            </TABLE>
     <input type="submit"  name="submit" value="Submit">
    </Form>
</body>

<SCRIPT TYPE="text/javascript">

  var count = "1";
  function addRow(in_tbl_name)
  {
    var tbody = document.getElementById(in_tbl_name).getElementsByTagName("TBODY")[0];
    // create row
    var row = document.createElement("TR");
    // create table cell 1
    var td1 = document.createElement("TD")
    var strHtml1 = field_name;
    td1.innerHTML = strHtml1.replace(/!count!/g,count);
    // create table cell 2
    var td2 = document.createElement("TD")
    var strHtml2 = field_opt;
    td2.innerHTML = strHtml2.replace(/!count!/g,count);
    // create table cell 3
    var td3 = document.createElement("TD")
    var strHtml3 = "<INPUT TYPE=\"text\" placeholder=\"e.g.AA123\" NAME=\"in_val[]\">";
    td3.innerHTML = strHtml3.replace(/!count!/g,count);
    // create table cell 4
    var td4 = document.createElement("TD")
    var strHtml4 =field_andOr;
    td4.innerHTML = strHtml4.replace(/!count!/g,count);
    // create table cell 5
    var td5 = document.createElement("TD")
    var strHtml5 = "<INPUT TYPE=\"Button\" CLASS=\"Button\" onClick=\"delRow()\" VALUE=\"Delete Row\">";
    td5.innerHTML = strHtml5.replace(/!count!/g,count);
    // append data to row
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    // add to count variable
    count = parseInt(count) + 1;
    // append row to table
    tbody.appendChild(row);
  }
  function delRow()
  {
    var current = window.event.srcElement;
    //here we will delete the line
    while ( (current = current.parentElement)  && current.tagName !="TR");
         current.parentElement.removeChild(current);
  }
</SCRIPT>
<?php
}
if(isset($_POST["submit"])) {
$search_qq="";
for ($i=0; $i<count($_POST['field_name']); $i++) { 
    // check if its the first one.  if not  then
    if($i!=0){
        $search_qq.=' '.$_POST['andORor_'][$i].' ';
    }

    switch ($_POST['field_operator'][$i]){
    case '=':
        $search_qq.= ' '.$_POST['field_name'][$i].':"'.$_POST['in_val'][$i].'" ';
        break;
    case '>=':
        $search_qq.= ''.$_POST['field_name'][$i].':['.$_POST['in_val'][$i].' TO *]';
        break;
    case '<=':
         $search_qq.= ''.$_POST['field_name'][$i].':[* TO '.$_POST['in_val'][$i].']';
        break;
    case 'like':
         $search_qq.= ''.$_POST['field_name'][$i].':*'.$_POST['in_val'][$i].'*';
        break;
    case 'nlike':
         $search_qq.= '-'.$_POST['field_name'][$i].':*'.$_POST['in_val'][$i].'*';
        break;
    }
}

// echo $search_qq;
// die();
/* set the search string*/
$_SESSION['test_str']=$search_qq;
/*Or clause is not working properly */
// try {

//     $search ="http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=".$search_q."&rows=5000&wt=json";
//     $jsonString = file_get_contents($search);
//     // txt file 
//      preg_match('/("docs"[\s\S]*)/', $jsonString, $m);
//     $d_string="{\n".$m[0];
//     $d_string= str_replace("docs","data",$d_string);
//     $d_string[strlen($d_string)-1]="";
//     $d_string[strlen($d_string)-2]="";
//     $d_string = trim($d_string); 
//     $_SESSION['search_qb_sess']=$d_string;
    $_SESSION['search_qb_post_info']=$_POST;
// } catch (Exception $e) {
//     $_SESSION['search_qb_sess']='';
//     $_SESSION['search_qb_post_info']=$_POST;
// }
?>
<script type="text/javascript">
window.location = "submit_search_qb.php";
</script> 


<?php
}

?>

<?php include 'includes/footerx.php';?>


