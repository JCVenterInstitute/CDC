<?php include 'includes/header.php';?>

<style type="text/css">
#table td, th { padding: 0.5em; }
.classy0 { color: #234567; }
.classy1 { color: #828282; }
</style>

<style type="text/css">
.dropv, .dropv ul {padding: 0; margin: 0; list-style: none;}
.dropv a {display: inline; width: 600px;}
.dropv li {float: left; padding: 0px 8px 2px 0px;}  /* all list items */
.dropv li ul {position: absolute; background: #F5ECCE; 
  padding: 0px 0px 0px 4px; width: 600px; left: -9999px;} /* second-level lists */
.dropv li:hover ul {left: auto; } /* nest list under hovered list items */
.dropv li ul li a {color: #ffffff; text-decoration: none; display: block;}
.dropv li ul li a:hover {color: yellow; background:#663399;}
</style>
<script type="text/javascript" src="js/o1.js"></script>

<script type="text/javascript">
	function validateForm(){
		var x=document.forms["myForm"]["txt"].value
		if (x==null || x=="" || x=="*"){
			alert("Please enter a keyword");
			return false;
		}
	}

</script>
<script language="javascript">
	function checkAll(){
		for (var i=0;i<document.forms[0].elements.length;i++){
			var e=document.forms[0].elements[i];
			if ((e.name != 'allbox') && (e.type=='checkbox')){
				e.checked=document.forms[0].allbox.checked;
			}
		}
	}
</script>

<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
			<h2 class="title">Search AMRdb</h2>
		    <div class="separator-2"></div>

	<div style="line-height: 150%;"> <p style="text-align:justify"; >Advance Search form for the AMRdb. For more information see <a href="help.php#location">help page</a></p><hr></hr>
	</div>
	<div class="main col-md-12">
	<H3 align=center>Complex Query Submission Form</H3>
	</div>
	<div class="main col-md-12">
	<form name="myForm" action="search_advance.php" method="POST" enctype="multipart/form-data" onSubmit="return validateForm()">

	<div class="main col-md-12" style= "line-height: 150%;">	
	<table bgcolor="#C6BFC5" id="table" width="auto" align=center>
	<thead>
	  <tr>
	    <th align=left>No.</th>
	    <th align=left>Field</th>
	    <th align=left>Condition</th>
	    <th align=left>Query</th>
	    <th align=left>Operator</th>
	    <th align=left>Add</th>
	    <th align=left>Remove</th>
	  </tr>
	</thead>
	<tbody></tbody>
	</table>
	</div>
	
	<div id="panel2">
	</div>

	<div class="main col-md-6" style= "line-height: 250%;">
	</div>
	<div class="main col-md-6" style= "line-height: 250%;">
	<input value="Submit for AMRdb Search" name="send" type="submit"  class="btnx btn-default">
	</div>
	</form>
	</div>
	
			</div>
	    </div>
   </div>  
</section>
<?php include 'includes/footer.php';?>
