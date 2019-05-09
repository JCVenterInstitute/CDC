
<?php 
include 'includes/header.php'; 
include 'includes/config.inc.php';
	
	if(!isset($_SESSION['userID'])){
	  echo '<br><br><br><br><h2 align="center">Please Log in as an admin</h5><br><br><br><br><br><br><br>';
	  include 'includes/footerx.php';
	  die;
	}



?>
<section class="main-container">
   <div class="containerx">
        <div class="row">
	         <div class="main col-md-12" align="center">
					<i class="fa fa-laptop"></i>
					<h2>Rebuild Reference Database</h2>				
						<form action="data_refresh_submit.php" method="post">
						<button class="btn-default btn" type="submit">Rebuild</button>
						</form>	
			</div>
		</div>
	</div>
</section>

<?php
 	echo "<br><br><br><br><br><br><br>";

include 'includes/footer.php'; 
?>