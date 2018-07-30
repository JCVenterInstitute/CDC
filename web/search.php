<?php include 'includes/header.php';?>

<script type="text/javascript">
function chkcontrol(j){
        var total=0;
        for(var i=0; i < document.forms["myForm"].elements.length; i++){
                var e=document.forms["myForm"].elements[i];
                if((document.forms["myForm"].elements[i].checked)&&(e.name !='ids')&&(e.name !='names')&&(e.name !='drug')&&(e.name !='allele')&&(e.name !='taxonomy'))
				{alert(total)}
                if(total < 1){
			jQuery(function(){
    			var max = 1;
			var checkboxes = $('input:checkbox[id^="dkkd"]');                
    			checkboxes.change(function(){
        			var current = checkboxes.filter(':checked').length;
        			checkboxes.filter(':not(:checked)').prop('disabled', current >= max);
    			});
			});

			    alert("Please Select only Seven Fields")
        		document.forms["myForm"].elements[j].checked = false ;
        		return false;
                }
        }
} 
</script>
<script type="text/javascript">
	function validateForm(){
		var x=document.forms["myForm"]["txt"].value
		if (x==null || x=="" || x==" " || x=="*"){
			alert("Pleasee enter a keyword");
			return false;
		}
	}

function paste_example1(){document.myForm.txt.value = "FJ666073.1";}
function paste_example2(){document.myForm.txt.value = "ACQ82815.1";}
function paste_example3(){document.myForm.txt.value = "C4NY34";}
function paste_example4(){document.myForm.txt.value = "PDC-10";}
function paste_example5(){document.myForm.txt.value = "SHV-133";}
function paste_example6(){document.myForm.txt.value = "Beta-lactamase";}
function paste_example7(){document.myForm.txt.value = "Beta-lactam";}
function paste_example8(){document.myForm.txt.value = "Class_C_AmpC";}
function paste_example9(){document.myForm.txt.value = "OXA";}
function paste_example10(){document.myForm.txt.value = "OXA-63-like";}
function paste_example11(){document.myForm.txt.value = "Pseudomonas aeruginosa";}

function jj(){document.getElementById("check10").checked=true;}
function kk(){document.getElementById("check11").checked=true;}
function ll(){document.getElementById("check12").checked=true;}
function oo(){document.getElementById("check15").checked=true;}
function ff(){document.getElementById("check6").checked=true;}
function mm(){document.getElementById("check13").checked=true;}
function nn(){document.getElementById("check14").checked=true;}
</script>



<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
			<h2 class="title">Search AMRdb</h2>
		    <div class="separator-2"></div>

	<div style="line-height: 150%;"> <p style="text-align:justify"; >Search the AMRdb. For more information see <a href="help.php#location">help page</a></p><hr></hr>
	</div>
	<div class="main col-md-12">
	<H3 align=center>Query Submission Form</H3>
	</div>
	<div class="main col-md-12">
	<form name="myForm" action="search_kw_sub_cpp.php" method="POST" enctype="multipart/form-data" onSubmit="return validateForm()"> 
	Please paste/insert/type your query to be searched:<input type="text" name="txt" size="30" placeholder="Enter Keyword ...." value=""><INPUT TYPE = "SUBMIT" VALUE = "Search">
	<p align=left><b>Select Fields to be Searched</b> </p>
	</div>
	<div class="main col-md-12" style= "line-height: 250%;">	
	<input type=checkbox id="check3" name=ids value='ids' checked > Genbank ID/Protein ID/Uniprot ID 
	[<input type="button"  value= "FJ666073.1" onClick="paste_example1();c() ">]
	[<input type="button"  value= "ACQ82815.1" onClick="paste_example2();c() ">]
	[<input type="button"  value= "C4NY34" onClick="paste_example3();c() ">]
	</div>
	<div class="main col-md-12" style= "line-height: 250%;">
	<input type=checkbox id="check2" name=names value='names' checked > Gene Symbol\Gene names\Protein names 
	[<input type="button"  value= "PDC-10" onClick="paste_example4();b()">]
	[<input type="button"  value= "SHV-133" onClick="paste_example5();b()">]
	[<input type="button"  value= "Beta-lactamase" onClick="paste_example6();b()">] 
	</div>
	<div class="main col-md-12" style= "line-height: 250%;">
	<input type=checkbox id="check18" name=drug value='drug' checked >Drug family\Drug Class 
	[<input type="button"  value= "Beta-lactam" onClick="paste_example7();r()">]
	[<input type="button"  value= "Class_C_AmpC" onClick="paste_example8();r()">] 
	</div>
	<div class="main col-md-12" style= "line-height: 250%;">
	<input type=checkbox id="check6" name=allele value='allele'  >Parent Allele\Parent Allele Family
	[<input type="button"  value= "OXA" onClick="paste_example9();ff()">]
	[<input type="button"  value= "OXA-63-like" onClick="paste_example10();ff()">]
	</div>
	<div class="main col-md-12" style= "line-height: 250%;">
	<input type=checkbox id="check13" name=taxonomy value='taxonomy'  >
	Taxonomic lineage [<input type="button"  value="Pseudomonas aeruginosa" onClick="paste_example11();mm()">]
	</div>
	<div class="main col-md-6" style= "line-height: 250%;">
	</div>
	<div class="main col-md-6" style= "line-height: 250%;">
	<input value="Reset or Clear Form" type="reset"  class="btnx btn-default">
	<input value="Submit for AMRdb Search" name="send" type="submit"  class="btnx btn-default">
	</div>
	</form>

			</div>
	    </div>
   </div>  
</section>
<?php include 'includes/footer.php';?>
