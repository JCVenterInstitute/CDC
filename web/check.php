<?php
$ran = $_GET["ran"];
echo "<meta http-equiv='refresh' content='5;url=check.php?ran=$ran'>";
?>
<?php include 'includes/header.php';?>
<div class="sorting-filters">
				<div class="container">
					<div class="row">

						<!-- main start -->
						<!-- ================ -->
						<div class="main col-md-12">
<?php
						echo "<h1 align='center'> Please wait while we process your results. <br>The results will be ready within five minutes. <br> "; echo "<h1 align='center'> OR <br>"; echo "Visit the following link <br><a href='http://fmd.jcvi.org/check.php?ran=$ran'>http://fmd.jcvi.org/check.php?ran=$ran </a> </h1>";
        $dir = "/export/apache/htdocs/fmd/temp/$ran";
#        $dirfilep = "$dir/map.html";
        $dirfilep = "$dir/out.json";
        if (file_exists($dirfilep)){
        exec("rm -rf $dir/test*");
        #exec("chmod 733 $dir");
        echo "<meta  http-equiv='refresh' content='5;url=polary.php?ran=$ran' />";        }
        #echo "<meta  http-equiv='refresh' content='5;url=map.php?ran=$ran' />";        }
?>
						</div>
					</div>
				</div>




</div>
<?php include 'includes/footer.php';?>
