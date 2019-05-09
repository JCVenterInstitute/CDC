<?php include 'includes/header.php';?>
<script type="text/javascript" language="JavaScript">


</script>

<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
			<h2 class="title">Primer Finder</h2>
		    <div class="separator-2"></div>
 				<div style="line-height: 150%;"> <p style="text-align:justify"; >PCR primer finder assesses the specificity of primers on AMR genes or a target genome. PCR primer finder uses open source <a href="https://sourceforge.net/projects/simulatepcr/">Simulate_PCR</a> tools for predicting both desired and off-target amplification products.<br>  For more information see AMRdb <a href="help.php#primer_finder_page">help page</a></p><hr></hr>
				</div>
				<div class="main col-md-12">
					<form name="example" action="validate_primer_que.php" method="POST" enctype="multipart/form-data" onSubmit="return validateForm()">
						Primer Forward Sequence:<br>
						<input type="button" class="btn btn-primary btn-sm" value="use an example primer forward sequence" onClick="paste_f_example()">
						<textarea ALIGN="TOP" ROWS="5" COLS="135" NAME="primer_f_seq_txtfield"></textarea> <br>
						 Select File to upload:
	    				<input type="file" name="forward_seq_fileToUpload" id="forward_seq_fileToUpload" >
						<br>
						Primer Reverse Sequence:<br>
						<input type="button" class="btn btn-primary btn-sm" value="use an example primer reverse sequence" onClick="paste_r_example()">
						<textarea ALIGN="TOP" ROWS="5" COLS="135" NAME="primer_r_seq_txtfield"></textarea> <br>
						 Select File to upload:
	    				<input type="file" name="rev_seq_fileToUpload" id="rev_seq_fileToUpload" >
						<br>
							Reference: 
						<div class="radio">
										<label>
											<input type="radio" name="reference" id="amr_db" value="amr_db" checked="checked" onclick="radioDefault()">AMR-DB(default)<br><br>
											<input type="radio" name="reference" id="reference_fasta_check" value="" onclick="radioFile()">
											<input type="file" name="reference_fastafile" id="reference_fastafile" disabled><br>
											<input type="radio" name="reference" id="reference_genbank_check" value="" onclick="radioText()">GenBank Accession
											<input type="text" name="reference_genebank" id="reference_genebank" disabled>
										</label>
						</div>
						<div class="main col-md-12" align="center">
							<input value="SUBMIT" align="middle" name="method" type="submit" class="btn btn-default" onClick="validate_form()">
						</div>
					</form>
				</div>
			</div>
	    </div>
   </div>  
</section>

<script type="text/javascript">
	function radioDefault(){
		document.getElementById('reference_fastafile').disabled=true;
		document.getElementById('reference_genebank').disabled=true;
	}
	function radioFile(){
		document.getElementById('reference_fastafile').disabled=false;
		document.getElementById('reference_genebank').disabled=true;
	}
	function radioText(){
		document.getElementById('reference_genebank').disabled=false;
		document.getElementById('reference_fastafile').disabled=true;
	}
	function paste_f_example(){
 		document.example.primer_f_seq_txtfield.value = 
 		">1|F\nGCCATCCCTGACGATCAAAC\n>2|F\nTTGGCATAAGTCGCAATCCC\n";
	}
		function paste_r_example(){
 		document.example.primer_r_seq_txtfield.value = ">1|R\nGCCCAATATTATGCACCCGG\n>2|R\nGTTTGATCGTCAGGGATGGC";
	}
</script>



<?php include 'includes/footer.php';?>
