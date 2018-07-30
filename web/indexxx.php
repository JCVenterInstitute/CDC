<?php include 'includes/header.php';?>
<script type="text/javascript" src="jquery-1.4.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
        $("#loding1").hide();
        $("#loding2").hide();
        $(".country").change(function()
        {
                $("#loding1").show();
                var id=$(this).val();
                var dataString = 'id='+ id;
                $(".state").find('option').remove();
                $(".city").find('option').remove();
                $.ajax
                ({
                        type: "POST",
                        url: "get_state.php",
                        data: dataString,
                        cache: false,
                        success: function(html)
                        {
                                $("#loding1").hide();
                                $(".state").html(html);
                        }
                });
        });


        $(".state").change(function()
        {
                $("#loding2").show();
                var id=$(this).val();
                var dataString = 'id='+ id;

                $.ajax
                ({
                        type: "POST",
                        url: "get_city.php",
                        data: dataString,
                        cache: false,
                        success: function(html)
                        {
                                $("#loding2").hide();
                                $(".city").html(html);
                        }
                });
        });

});


</script>

<style>
.map {
	height: 500px;
	width: 100%;
	background: #f00 url(images/slider1.png);
	background-size: 100% 100%;
	background-repeat: no-repeat;
}
.main-text {
	position: absolute;
	top: 50px;
	width: 96.66666666666666%;
	color: #FFF;
}
.btn-min-block {
	min-width: 170px;
	line-height: 26px;
}
.btn-clear {
	color: #000;
	background-color: #fff;
	border-color: #FFF;
	margin-right: 15px;
}
.btn-clear:hover {
	color: #fff;
	background-color: transparent;
}
.carousel-caption {
	top: 0;
	left: 0;
	right: 0;
	padding-top: 0px;
}
.map-text {
	background-color: #000;
	width: 100%;
	padding: 10px 20px;
	color: #fff;
	font-size: 18px;
}
@media only screen and (max-width: 640px) {
	.map {
		height: 225px;
	}
	.map-text { 
	font-size:13px;
	padding: 5px 10px;
	}
}
</style>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="item active">
      <div class="map"></div>
      <!--<img src="images/slider1.png"  alt="First slide">-->
      <div class="carousel-caption">
        <p class="map-text"> The Forensic Microbiome Database (FMD) is a human microbiome analysis resource that correlates publicly available 16S rRNA sequence data <a href="about.php">more..</a> </p>
      </div>
    </div>
  </div>
</div>
    <!-- banner start -->
    <!-- ================ -->
    <div class="banner">

        <!-- slideshow end -->
        <?php //echo "<pre>"; print_r($_POST);	?>
        <div class="sorting-filters">
            <div class="container">
                <form class="form-inline"  name="location" method="post" action="krona.php"  >
                    <div class="form-group">
			<input type="hidden" name="body" value="16">
                        <label>-Select Country (Stool Samples)-</label>
                        <select name="country" class="form-control country">
                            <option selected="selected">--Select Country--</option> 
                            <?php
                            $result = mysql_query("SELECT * FROM country Where body_id ='16'");
                            if (mysql_num_rows($result) > 0) {
                                while($row = mysql_fetch_array($result)) {
                                    $selectedCountry = ($row['country_id']==$_POST['country']) ? ' selected="selected"' : '';
				    echo "<option value='" . $row['country_id'] . "' ".$selectedCountry.">" . str_replace('_', ' ',$row['country_name']) . "</option>";
                              #      echo "<option value='" . $row['country_id'] . "' ".$selectedCountry.">"; echo str_replace("_", " ", $row['country_name'])"; echo "</option>";
                            ?>
                           <!-- <option value="<?php //echo $row['country_id']; ?>"><?php //echo $row['country_name']; ?></option>-->
                            <?php }
                            } else {
                            echo "0 results";
                            }
                            ?>
                        </select> 
                    </div>
                    <div class="form-group">
                        <label>-Select State/Sub-Divison-</label>
                        <select name="state" class="form-control state">
                            <option selected="selected">--Select State--</option>
                        </select>
                        <img src="ajax-loader.gif" id="loding1"></img>
                    </div>
                    <div class="form-group">
                        <label>-Select City-</label>
                        <select name="city" class="form-control city">
                            <option selected="selected">--Select City--</option>
                        </select>
                        <img src="ajax-loader.gif" id="loding2"></img>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" value="Submit" class="btn btn-default">
                    </div>
                </form>
            </div>
        </div>
    </div>
			<!-- banner end -->

<?php include 'includes/footer.php';?>
