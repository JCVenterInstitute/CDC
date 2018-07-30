<?php include 'includes/header.php';?>
<style>
a, .text-default {
	color: black;
}
a:hover,
a:focus {
	color: white;
}
</style>
		   <section class="main-container">
				<div class="container">
 		        	<div class='row'>
			        	<div class='main col-md-12'>
<?php
		$to="";
		$filec = file("data/stat.txt");
		$i="0";
## First table		               
			           echo "<div class='panel-group' id='accordion'>
								<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4 class='panel-title'>
											<a data-toggle='collapse' data-parent='#accordion' href='#collapseOne'>
												General Statistics of FMD
											</a>
										</h4>
									</div>
								<div id='collapseOne' class='panel-collapse collapse in'>
									<div class='panel-body'>";
								echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Samples</th>
										<th>Projects</th>
										<th>Body Sites</th>
										<th>Pubmed Entries</th>
										<th>Countries</th>
										<th>States/Sub-Divison</th>
										<th>Cities</th>
									</tr>
								</thead>
								<tbody>
									<tr>";
									foreach ($filec as $my){
										if (preg_match("/^T/i", $my)){ 
										$data = explode(" ", $my);
										echo "<td> $data[1] </td>";
									}
								}	

								echo	"</tr>
								</tbody>
							</table>
								</div>
							</div>
						</div>";			

## Second Table
							echo "<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4 class='panel-title'>
											<a data-toggle='collapse' data-parent='#accordion' href='#collapseTwo' class='collapsed'>
												Samples per body site
											</a>
										</h4>
									</div>
									<div id='collapseTwo' class='panel-collapse collapse'>
										<div class='panel-body'>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Throat</th>
										<th>Palatine Tonsils</th>
										<th>Tongue dorsum</th>
										<th>Subgingival plaque</th>																														
										<th>Saliva</th>
										<th>Attached Keratinized gingiva</th>
										<th>Buccal mucosa</th>
										<th>Hard palate</th>
										<th>Supragingival plaque</th>														
									</tr>
								</thead>
								<tbody>
									<tr>";
									foreach ($filec as $my){
										if (preg_match("/^1B Throat/i", $my)){ $data="";									
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Palatine_Tonsils/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Tongue_Dorsum/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Subgingival_Plaque/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Saliva/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Attached_Keratinized_Gingiva/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Buccal_Mucosa/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Hard_Palate/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Supragingival_Plaque/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}

								echo	"</tr>
								</tbody>
								<thead>
									<tr>
										<th>Left Retroauricular crease</th>																				
										<th>Right Retroauricular crease</th>										
										<th>Anterior nares</th>
										<th>Left Antecubital fossa</th>
										<th>Right Antecubital fossa</th>										
										<th>Posterior fornix</th>
										<th>Vaginal introitus</th>
										<th>Mid vagina</th>
										<th>Stool</th>														
									</tr>
								</thead>
								<tbody>
									<tr>";
									foreach ($filec as $my){
										if (preg_match("/^1B Left_Retroauricular_Crease/i", $my)){ $data="";									
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Right_Retroauricular_Crease/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Anterior_Nares/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Left_Antecubital_Fossa/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Right_Antecubital_Fossa/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Posterior_Fornix/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Vaginal_Introitus/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Mid_Vagina/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
									foreach ($filec as $my){
										if (preg_match("/^1B Stool/i", $my)){ $data="";
										$data = explode(" ", $my);
										echo "<td> $data[2] </td>";
										}
									}
								echo	"</tr>
								</tbody>
							</table>
							</div>							
						</div>
					</div>";	
							
							
 
				
####### Table 3
						echo "<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4 class='panel-title'>
											<a data-toggle='collapse' data-parent='#accordion' href='#collapseThree' class='collapsed'>
												Distribution of samples per body site per location
											</a>
										</h4>
									</div>
									<div id='collapseThree' class='panel-collapse collapse'>
										<div class='panel-body'>
									        <div class='col-md-6'>";

###
							echo "<table class='table table-striped' width='40%'>
								<thead>
									<tr>
										<th>Body Site</th>
										<th>Country Name</th>
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^1C/i", $my)){ 
										$data = explode(" ", $my); 
										echo "<tr><td>"; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td> $data[3] </td></tr>";										
										}
									}	
								echo	"
								</tbody>
							</table>";
							echo "<table class='table table-striped' width='40%'>
								<thead>
									<tr>
										<th>Body Site</th>
										<th>Country Name</th>
										<th>State Name</th>
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^1S/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td> "; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[3]); echo "</td>";
										echo "<td> $data[4] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>";	
   						echo "</div>";

		                echo "<div class='col-md-6'>";
							echo "<table class='table table-striped' width='40%'>
								<thead>
									<tr>
										<th>Body Site</th>
										<th>Country Name</th>
										<th>State Name</th>
										<th>City Name</th>										
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^1T/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td>"; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[3]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[4]); echo "</td>";
										echo "<td> $data[5] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>
									</div>
								</div>
							</div>
						</div>";
		                
####### Table 3
						   echo "<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4 class='panel-title'>
											<a data-toggle='collapse' data-parent='#accordion' href='#collapseFour' class='collapsed'>
												Distribution of samples per location
											</a>
										</h4>
									</div>
									<div id='collapseFour'  class='panel-collapse collapse'>
										<div class='panel-body'>
							                <div class='col-md-6'>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Country Name</th>
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^2C/i", $my)){ 
										$data = explode(" ", $my); 
										echo "<tr><td>"; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td> $data[2] </td></tr>";										
										}
									}	
								echo	"
								</tbody>
							</table>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Country Name</th>
										<th>State Name</th>
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^2S/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td> "; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td> $data[3] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>";	
   						echo "</div>";
   						
		                echo "<div class='col-md-6'>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Country Name</th>
										<th>State Name</th>
										<th>City Name</th>										
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^2T/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td>"; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[3]); echo "</td>";
										echo "<td> $data[4] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>		                
  									</div>
								</div>
							</div>
						</div>";

####### Table 4
						echo "<div class='panel panel-default'>
									<div class='panel-heading'>
										<h4 class='panel-title'>
											<a data-toggle='collapse' data-parent='#accordion' href='#collapseFive' class='collapsed'>
												Distribution of samples per project per location
											</a>
										</h4>
									</div>
									<div id='collapseFive'  class='panel-collapse collapse'>
										<div class='panel-body'>
							                <div class='col-md-6'>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Project Name</th>
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^3P/i", $my)){ 
										$data = explode(" ", $my); 
										echo "<tr><td>"; echo "<a href='https://www.ncbi.nlm.nih.gov/pubmed/?term=$data[3]'>";echo str_replace("_", " ", $data[1]); echo "</a></td>";
										echo "<td> $data[2] </td></tr>";										
										}
									}	
								echo	"
								</tbody>
							</table>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Project Name</th>
										<th>Country Name</th>
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^3C/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td> "; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td> $data[3] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Project Name</th>
										<th>Country Name</th>
										<th>State Name</th>								
										<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^3S/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td> "; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[3]); echo "</td>";										
										echo "<td> $data[4] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>";	
   						echo "</div>";
   						
		                echo "<div class='col-md-6'>";
							echo "<table class='table table-striped'>
								<thead>
									<tr>
										<th>Project Name</th>																		<th>Country Name</th>
										<th>State Name</th>
										<th>City Name</th>																		<th>Number Samples</th>
									</tr>
								</thead>
								<tbody>";
									foreach ($filec as $my){
										if (preg_match("/^3T/i", $my)){ 
										$data = explode(" ", $my);
										echo "<tr><td>"; echo str_replace("_", " ", $data[1]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[2]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[3]); echo "</td>";
										echo "<td>"; echo str_replace("_", " ", $data[4]); echo "</td>";										
										echo "<td> $data[5] </td></tr>";
										}
									}	
								echo	"
								</tbody>
							</table>
								</div>
							</div>
						</div>
					</div>
				</div>";
		#fclose ($filec);							
 ?>
					<div>
				</div>
			</div>
			</section>					
<?php include 'includes/footer.php';?>
