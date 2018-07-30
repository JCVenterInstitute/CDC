<?php include 'includes/header.php';?>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
                                <div class="container">
                                        <div class="row">

                                                <!-- main start -->
                                                <!-- ================ -->
                                                <div class="main col-md-12">
						<h4 align="center">Output of Geographic Location Predictor</h4>
						   <p>The below map displays the location of the most similar 16S microbiome sample in the FMD database along with the Sorensen Similarity Index. The higher the Sorensen similarity value the more similar the user uploaded sample is to the FMD sample. A score of 1.0 means the 16S microbiome taxonomical composition of the user uploaded sample and the FMD sample is 100% identical.</p>
						</div>
					</div>
				</div>

<div style="height:600px; width:100%;">
<?php 
$ran = $_GET["ran"];
include '/export/apache/htdocs/fmd/temp/'.$ran.'/map.html';
?>
</div>
<?php include 'includes/footer.php';?>
