<?php include 'includes/header.php';?>
<style>
div#adds > ins{
    margin-right: 5px;
}
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<section class="main-container">
   <div class="container">
        <div class="row">
            <div class="main col-md-12">
	            <h3 class="title" align="center"> The form allow the user to submit new data in AMRdb</h3>
	  		    <div class="form-group">				<div class="col-sm-2"></div>
					<label for="gene_symbol" class="col-sm-2 control-label" >Please Enter JCVI ID:</label>
					<div class="col-sm-4">
					<input type="text" class="form-control" id="gene_symbol" align="middle">
					</div>
					<div class="col-sm-4"></div>
				</div>
			</div>
            <div class="main col-md-12">

							<!-- Tabs start -->
							<!-- ================ -->
							<div class="tabs-style-2">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs" role="tablist">
									<li class="active"><a href="#h2tab1" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Identity</a></li>
									<li><a href="#h2tab2" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Antibiogram</a></li>
									<li><a href="#h2tab3" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Threat Level</a></li>
									<li><a href="#h2tab4" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Taxonomy</a></li>									
									<li><a href="#h2tab5" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Sequence</a></li>									
									<li><a href="#h2tab6" role="tab" data-toggle="tab"><i class="fa fa-file-o pr-10"></i> Submit Data</a></li>																		
								</ul>
								<!-- Tab panes -->
								<div class="tab-content">
								<form class="form-horizontal" role="form">
									<div class="tab-pane fade in active" id="h2tab1">									
										<h1 class="text-center title">Identification details</h1>									
										<div class="row">
											<div class="form-group">
												<label for="gene_symbol" class="col-sm-2 control-label">Gene Symbol</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="gene_symbol" placeholder="e.g. Blacmy">
												</div>
											</div>
		
											<div class="form-group">
												<label for="gene_aliases" class="col-sm-2 control-label">Gene Aliases</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="gene_aliases" placeholder="e.g. CMY-7 blacmy-7">
												</div>
											</div>
		
											<div class="form-group">
												<label for="parent_allele" class="col-sm-2 control-label">Parent Allele</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="parent_allele" placeholder="e.g. CMY">
												</div>
											</div>
		
											<div class="form-group">
												<label for="parent_allele_family" class="col-sm-2 control-label">Parent Allele Family</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="parent_allele_family" placeholder="e.g. OXA-55-like">
												</div>
											</div>
		
											<div class="form-group">
												<label for="genbank_id" class="col-sm-2 control-label">Genbank ID</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="genbank_id" placeholder="e.g. AJ011291.1">
												</div>
											</div>
		
											<div class="form-group">
												<label for="protein_id" class="col-sm-2 control-label">Protein ID</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="protein_id" placeholder="e.g. CAB36900.1">
												</div>
											</div>
		
											<div class="form-group">
												<label for="uniprot_id" class="col-sm-2 control-label">Uniprot ID</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="uniprot_id" placeholder="e.g. Q9S6R4">
												</div>
											</div>
		
											<div class="form-group">
												<label for="allele" class="col-sm-2 control-label">Allele</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="allele" placeholder="e.g. 7">
												</div>
											</div>
		
											<div class="form-group">
												<label for="snp" class="col-sm-2 control-label">SNP</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="snp" placeholder="e.g. Y114F,M109I,V165I">
												</div>
											</div>
											
											<div class="form-group">
												<label for="drug_family" class="col-sm-2 control-label">Drug Family</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="drug_family" placeholder="e.g. Beta-lactam">
												</div>
											</div>
											
											<div class="form-group">
												<label for="drug_class" class="col-sm-2 control-label">Drug Class</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="drug_class" placeholder="e.g. Class_C_Carbapenemase">
												</div>
											</div>
		
											<div class="form-group">
												<label for="sub_drug_class" class="col-sm-2 control-label">Sub Drug Class</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="sub_drug_class" placeholder="e.g. Resistance-Nodulation_Cell_Division">
												</div>
											</div>
											
											<div class="form-group">
												<label for="bioproject_id" class="col-sm-2 control-label">Bioproject ID</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="bioproject_id" placeholder="e.g. PRJNA225">
												</div>
											</div>
											
											<div class="form-group">
												<label for="biosample_id" class="col-sm-2 control-label">Biosample ID</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="biosample_id" placeholder="e.g. SAMN02604091">
												</div>
											</div>
											
											<div class="form-group">
												<label for="plasmid" class="col-sm-2 control-label">Plasmid</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="plasmid" placeholder="e.g. SAMN02604091">
												</div>
											</div>
										</div>
									</div>
								
									<div class="tab-pane fade" id="h2tab2">
										<h1 class="text-center title">Antibiogram</h1>
										<div class="row">
											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Antibiotic</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Drug Symbol</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Laboratory Typing Method</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Laboratory Typing Method Version or Reagent</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>
											
											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Measurement</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Measurement Sign</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Measurement Units</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Resistance Phenotype</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Testing Standard</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Vendor</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>						
										</div>
									</div>
									
									
									<div class="tab-pane fade" id="h2tab3">
										<h1 class="text-center title">Threat Level</h1>
										<div class="row">
											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Threat Level</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. SAMN02604091">
												</div>									
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade" id="h2tab4">
										<h1 class="text-center title">Taxonomy</h1>
										<div class="row">
											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Phylum</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. Proteobacteria">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Class</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. Enterobacterales">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Family</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. Enterobacteriaceae">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Genus</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. Escherichia">
												</div>									
											</div>
											
											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Species</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. Escherichia coli">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Strain</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="e.g. CMC">
												</div>									
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade" id="h2tab5">
										<h1 class="text-center title">Sequence</h1>
										<div class="row">
											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Protein Sequence</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="">
												</div>									
											</div>

											<div class="form-group">
												<label for="anti" class="col-sm-2 control-label">Nucleotide Sequence</label><div class="col-sm-1"></div>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="anti" placeholder="">
												</div>									
											</div>
										</div>
									</div>									


									<div class="tab-pane fade" id="h2tab6">									
										<div class="row">
											<div class="col-md-12" align="center">
												<input value="Submit the data into AMRdb database" align="middle" name="method" type="submit" class="btn btn-default">
											</div>
										</div>
									</div>
								</form>								
							</div>
							<!-- tabs end -->


			</div>
	    </div>
   </div>  
</section>
<?php include 'includes/footerx.php';?>


