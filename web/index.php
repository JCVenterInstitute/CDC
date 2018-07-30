<?php include 'includes/header.php'; 

// phpinfo();
?>

<style>
div.relative {
    position: relative;
    top: 200px;
    left: 100px;
    width: 300px;
    }
table {
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
 
.outer{
    align-content: center;
    width: 50%;
    margin-left: auto;
    margin-right: auto;    
    padding:200px 0;
    width:400px;
    height: 100px;
}
</style>
<?php
			$database = new Database();
			$db = $database->dbConnection();
			$conn = $db;

			// geting sumary count for each distinct 
			$stmt = $conn->prepare("SELECT Count( DISTINCT cl.Drug_Class)as dc FROM Classification cl ");
			$stmt->execute();	
			$drug_class=$stmt->fetch(PDO::FETCH_ASSOC);
			

			$stmt = $conn->prepare("SELECT Count( DISTINCT cl.Drug_Family)as dc FROM Classification cl ");
			$stmt->execute();	
			$drug_family=$stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $conn->prepare("SELECT Count( DISTINCT cl.Drug_Sub_Class)as dc FROM Classification cl ");
			$stmt->execute();	
			$drug_sub_class=$stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $conn->prepare("SELECT Count( DISTINCT cl.Gene_Symbol)as dc FROM Identity cl ");
			$stmt->execute();	
			$amr_gene=$stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $conn->prepare("SELECT Count( DISTINCT cl.Taxon_Genus)as dc FROM Taxonomy cl ");
			$stmt->execute();	
			$gene=$stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $conn->prepare("SELECT Count( DISTINCT cl.Taxon_Species)as dc FROM Taxonomy cl ");
			$stmt->execute();	
			$spi=$stmt->fetch(PDO::FETCH_ASSOC);
?>

<section class="main-container">
   <div class="containerx">
        <div class="row">
	         <div class="main col-md-12">
			  	<div class="col-md-5">
			        <p><font style="color: #01A9DB;"><h3 align="center">Drug Classification</h3></font></p>
			        <iframe  src="drug-classification.krona.html" height="500px" width="100%" frameBorder="0">
		            <p>Your browser does not support iframes.</p>
			        </iframe>
			    </div>

			  	<div class="col-md-2"> 
			  	<h3 align="center"><font style="color: #01A9DB;">Stats of AMR-DB</font></h3>
			        <table class=".table-striped ">
			            <tr style="background-color:#01A9DB">
			                <th>Summary</th>
			                <th>Count</th>
			            </tr>
			            <tr>
			                <td>Genus</td>
			                <td><?php echo $gene['dc']; ?></td>
			            </tr>
			            <tr>
			                <td>Species</td>
			                <td><?php echo $spi['dc']; ?></td>
			            </tr>
			            <tr>
			                <td>AMR Genes</td>
			                <td><?php echo $amr_gene['dc']; ?></td>
			            </tr>
			            <tr>
			                <td>Drug Class</td>
			                <td><?php echo $drug_class['dc'] ;?></td>
			            </tr>
			            <tr>
			                <td>Drug Family</td>
			                <td><?php echo $drug_family['dc'] ;?></td>
			            </tr>
			            <tr>
			                <td>Drug Sub-Class</td>
			                <td><?php echo $drug_sub_class['dc'] ;?></td>
			            </tr>
			        </table>
				</div>
	
			  	<div class="col-md-5">
			        <p><font style="color: #01A9DB;"><h3 align="center">Taxonomy Classification</h3></font> </p>
			        <iframe  src="species-classification.krona.html" height="500px" width="100%" frameBorder="0">
			        <p>Your browser does not support iframes.</p>
			        </iframe>
				</div>
			</div>
		</div>
	</div>

</section>	
<?php include 'includes/footer.php'; ?>
