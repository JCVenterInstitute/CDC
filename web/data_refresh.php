


<?php 
include 'includes/header.php'; 
include 'includes/config.inc.php';
	
	if(!isset($_SESSION['userID'])){
	  echo '<br><br><br><br><h2 align="center">Please Log in as an admin</h5><br><br><br><br><br><br><br>';
	  include 'includes/footerx.php';
	  die;
	}



?>
<div class="box-style-1 gray-bg object-non-visible animated object-visible fadeInUpSmall" data-animation-effect="fadeInUpSmall" data-effect-delay="200">
												<i class="fa fa-laptop"></i>
												<h2>Rebuild Reference Database</h2>
												<!-- <p>Iure sequi unde hic. Sapiente quaerat labore sequi inventore veritatis cumque.</p> -->
												<form action="data_refresh_submit.php" method="post">
													<!-- <input type="submit" name="refresh_data"> -->
													<button class="btn-default btn" type="submit">Rebuild</button>
												</form>
												<!-- <a href="page-services.html" class="btn-default btn">Read More</a> -->
											</div>

<?php
 	echo "<br><br><br><br><br><br><br>";

include 'includes/footer.php'; 
?>