<?php include 'includes/header.php';?>
<?php include_once("analyticstracking.php") ?>
<script type="text/javascript" language="JavaScript">
function paste_example()
{
 document.example.seq.value = ">Test Sequence\nKRVLIVDDAAFQKFIAKWLADADTRLGPVLEVIVNGRYAWMNGIDAIKERNRAPRVQMRMMLKDIITIMKIDPNAKIIVCSAMGQQAMVIEAIKAGAKDFIVKPFQPSRVVEALNKV";
}
</script>
<script language="JavaScript">
function setVisibility(id, visibility) {
document.getElementById(id).style.display = visibility;
}
</script>
<style type="text/css">
divx {
background-color: #f1f1f1;
color: black;
display: none;
}
</style>
<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
			<h2 class="title">BLAST or RGI</h2>
		    <div class="separator-2"></div>
				<form name="example" action ="submitpredict.php" ENCTYPE="multipart/form-data" METHOD="POST">
<H2 align=center></H2>
<p style="text-align:justify"; ><div style="line-height: 150%;"> Find the similar sequences present in the AMRdb using BLAST  or RGI. Default parameters will be used, but user can provide customize parameters. For more information see <a href="help.php#location">help page</a></div></p>
<hr></hr>
<h3>Paste your Protein sequence in FASTA format (single sequence only)
<input value="use an example" type="button"  class="btnx btn-default"  onClick="paste_example()"></h3> 
<div><textarea ALIGN="TOP" ROWS="5" COLS="135" NAME="seq"></textarea>  </div>

<div class="col-md-6">
 <fieldset name=KEYWORD>
 <legend align=center> <input type=button name=type value='Click for BLAST Options' onclick="setVisibility('sub3', 'inline');";>  </legend><br>
  <divx id="sub3">
	<div><b>Select number of top BLAST hits: <select NAME="hitno"><option VALUE="1" SELECTED></b>1<option VALUE="2" >2<option VALUE="3">3<option VALUE="4">4</select>
	</div>
																																	<div><b>Expect Value (e-value)</a>: <select SIZE="1" NAME="expect"><option VALUE="0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001"></b>1e-100<option VALUE="0.0000000001">1e-10<option VALUE="0.00001">1e-5<option VALUE="0.01">0.01<option VALUE="0.1" SELECTED>0.1<option VALUE="1">1<option VALUE="10">10<option VALUE="100">100<option VALUE="100">500 <option VALUE="1000">1000 <option VALUE="2000">2000</select>
	</div>
	
	<div>
	<b>Weight Matrix: <select NAME="matrix"><option VALUE="BLOSUM80"></b>BLOSUM80<option VALUE="BLOSUM62" SELECTED>BLOSUM62<option VALUE="BLOSUM45">BLOSUM45<option VALUE="PAM30">PAM30<option VALUE="PAM70">PAM70</select>
	</div>
  </divx>
</div>
 
 <div class="col-md-6">
 <fieldset name=KEYWORD>
 <legend align=center> <input type=button name=type value='Click for RGI Options' onclick="setVisibility('sub4', 'inline');";>  </legend><br>
  <divx id="sub4">
	<div><b>Select number of top BLAST hits: <select NAME="hitno"><option VALUE="1" SELECTED></b>1<option VALUE="2" >2<option VALUE="3">3<option VALUE="4">4</select>
	</div>
																																	<div><b>Expect Value (e-value)</a>: <select SIZE="1" NAME="expect"><option VALUE="0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001"></b>1e-100<option VALUE="0.0000000001">1e-10<option VALUE="0.00001">1e-5<option VALUE="0.01">0.01<option VALUE="0.1" SELECTED>0.1<option VALUE="1">1<option VALUE="10">10<option VALUE="100">100<option VALUE="100">500 <option VALUE="1000">1000 <option VALUE="2000">2000</select>
	</div>
	
	<div>
	<b>Weight Matrix: <select NAME="matrix"><option VALUE="BLOSUM80"></b>BLOSUM80<option VALUE="BLOSUM62" SELECTED>BLOSUM62<option VALUE="BLOSUM45">BLOSUM45<option VALUE="PAM30">PAM30<option VALUE="PAM70">PAM70</select>
	</div>
  </divx>
</div>
 
 
<input value="Reset or Clear Form" type="reset"  class="btnx btn-default">
<input value="Submit for Similarity Search" name="send" type="submit"  class="btnx btn-default">
</form>
			</div>
	    </div>
   </div>  
</section>
<?php include 'includes/footer.php';?>
