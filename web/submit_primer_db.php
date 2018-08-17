<?php include 'includes/header.php';?>
<?php require_once('dbconfig.php');?>
<?php 


 $mydb=  new Database();
$con = $mydb->dbConnection();

// var_dump($_POST);

$sth = $con->prepare("INSERT INTO Primer(Primer, Target, FWD,REV)values (?,?,?,?)");
$sth->bindValue(1,trim($_POST['primer']), PDO::PARAM_STR);
$sth->bindValue(2,trim($_POST['target']), PDO::PARAM_STR);
$sth->bindValue(3,trim($_POST['fwd']), PDO::PARAM_STR);
$sth->bindValue(4,trim($_POST['rev']), PDO::PARAM_STR);
try {
	$sth->execute();
} catch (Exception $e) {
	echo "<br>  <b>Error :</b><br>".$e;
	die();
}

?>



<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<style type="text/css" class="init">
	
td.details-control {
	background: url('images/details_open.png') no-repeat center center;
	cursor: pointer;
}
tr.shown td.details-control {
	background: url('images/details_close.png') no-repeat center center;
}
.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
}
</style>
<section class="main-container">
   <div class="containerx">
        <div class="row">
	         <div class="main col-md-12">
	         	<h2 align="center">Data has been submitted into the ARMdb for admin review. <br><br>Once admin approve the data, it will be visible in AMRdb</h2><br><h5 align="center">Thank you for update new information</h5
	         	
	        </div>
		</div>
	</div>
</section>	

<?php include 'includes/footer.php';?>