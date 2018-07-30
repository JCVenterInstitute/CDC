<?php include 'includes/header.php'; 


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
    $ls = ['Protein_ID','Gene_Symbol','Drug_Family','Organism_ID','Taxon_Species','GenBank_ID','Parent_Allele','Parent_Allele_Family','Allele','Drug_Class','Drug_Sub_Class','Taxon_Phylum','Taxon_Class','Taxon_Order','Taxon_Family','Taxon_Genus','Taxon_Strain','Plasmid','Bioproject','Biosample'];
    $ls_string='<SELECT NAME="field_name[]">';
    foreach ($ls as $item) {
        $ls_string.='<OPTION VALUE='.$item.'>'.$item.'';    
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
<html ng-app="dynamicFieldsPlugin">
  <body>
        <div ng-controller="dynamicFields" class="container">

          <?php echo "<form action='{$_SERVER[PHP_SELF]}' method=post>";?>

            <h1>Dynamic Form Fields Creation Plugin</h1>
            <TABLE class ="table table-striped" ID="tblPets" border="1">
                 <thead class="thead-dark">
                <TR><TH scope="col">Field Name</TH><TH scope="col" WIDTH="70">Operator</TH><TH scope="col" WIDTH="230">Values</TH><TH scope="col" WIDTH="80">And / Or </TH scope="col"><TH ALIGN="center"><INPUT TYPE="Button" class="red_button"onClick="addRow('tblPets')" VALUE="+ Add"></TH></TR> </thead>
                <TR><TD><?php echo $ls_string; ?></TD><TD><?php echo $ls_operator; ?></TD><TD><input type="text" name ="in_val[]" required="required"></TD><TD><?php echo $ls_andOR;?></TD><TD><INPUT TYPE="Button" CLASS="Button" onClick="delRow()" VALUE="Delete Row"></TD></TR>
            </TABLE>
     <input type="submit" class="btn" name="submit"value="Submit">
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
    var strHtml3 = "<INPUT TYPE=\"text\" NAME=\"in_val[]\">";
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
if(isset($_POST["submit"])) {
    $search_q="";
for ($i=0; $i<count($_POST['field_name']); $i++) { 
    
    // check if its the first one.  if not  then
    if($i!=0){
        $search_q.='%20'.$_POST['andORor_'][$i].'%20';
    }

    switch ($_POST['field_operator'][$i]) {
    case '=':
        $search_q.= '('.$_POST['field_name'][$i].':'.$_POST['in_val'][$i].')';
        break;
    case '>=':
        $search_q.= '('.$_POST['field_name'][$i].':['.$_POST['in_val'][$i].'%20TO%20*])';
        break;
    case '<=':
         $search_q.= '('.$_POST['field_name'][$i].':[*%20TO%20'.$_POST['in_val'][$i].'])';
        break;
    case 'like':
         $search_q.= '('.$_POST['field_name'][$i].':*'.$_POST['in_val'][$i].'*)';
        break;
    case 'nlike':
         $search_q.= '(-'.$_POST['field_name'][$i].':*'.$_POST['in_val'][$i].'*)';
        break;
    }
}


  $search ="http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=".$search_q."&rows=3000&wt=json";
// $str="http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q=*:*&rows=3000&wt=json";
// sleep(10);
$jsonString = file_get_contents($search);
// txt file 
 preg_match('/("docs"[\s\S]*)/', $jsonString, $m);
$d_string="{\n".$m[0];
$d_string= str_replace("docs","data",$d_string);
$d_string[strlen($d_string)-1]="";
$d_string[strlen($d_string)-2]="";
$d_string = trim($d_string); 
$_SESSION['search_qb_sess']=$d_string;
// header('Location:search_fetch.php');
// echo $d_string;
?>
<script type="text/javascript">
window.location = "submit_search_qb.php";
</script> 


<?php
}

?>

<?php include 'includes/footerx.php';?>


