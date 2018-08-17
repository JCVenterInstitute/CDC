<?php
include 'includes/header.php';
include 'includes/config.inc.php';
?>
<script type="text/javascript">
// count for variants, claassifcation, and antibiogram
  var count =1;
  var v_count=0;
  var a_count=1;
</script>
	<!--     Fonts and icons     -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
    <!--   Core JS Files   -->
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <!-- <script src="js/bootstrap.min.js" type="text/javascript"></script> -->
    <script src="js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

    <!--  Plugin for the Wizard -->
    <script src="js/gsdk-bootstrap-wizard.js"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/  -->
    <script src="js/jquery.validate.min.js"></script>

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-12">

                <div class="card wizard-card" data-color="red" id="wizardProfile">
				<form name="example" action="submit_primer_db.php" method="POST" enctype="multipart/form-data" onSubmit="return validateForm()">   
				             <!--        You can switch ' data-color="orange" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->
                    	<div class="wizard-header">
                        	<h3>The form allow the user to submit new data in AMRdb </h3>
                       	    <p>The user is able to submit new AMR data into AMRdb using the form. The data is split into six different sections or tabs: 1) Identification details of the AMR sequence, 2) any metadata associated with the AMR sequence, 3) antibiogram data if any, 4) threat level of the organism, 5) taxonomy of the organism and 6) protein and nucleotide sequence.For more information see <a href="help.php#location">help page</a>.</p>
                    	</div>

						<div class="wizard-navigation">
							<ul>
	                            <li><a href="#about" data-toggle="tab">Primer</a></li>
	                                                 
	                        </ul>
						</div>

                        <div class="tab-content"  style="background:white;">
                            <div class="tab-pane" id="about">
                            	<!-- first tab  -->
                              <div class="row">
									<div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="primer" class="control-label">Primer</label>
	                                        <input type="text" class="form-control" name="primer"   value="" placeholder="IMP"  >
	                                    </div>
	                                </div>
	                                <div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="target" class="control-label">Target</label>
	                                        <input type="text" class="form-control" name="target"   value="" placeholder="Screening of MBL"  >
	                                    </div>
	                                </div>
	                                <div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="primer" class="control-label">FWD</label>
	                                        <input type="text" class="form-control" name="fwd"   value="" placeholder="GGTTTGGTGGTTCTTG"  >
	                                    </div>
	                                </div>
	                                <div class="col-sm-10 col-sm-offset-1">
		                                <div class="form-group">
		                                    <label for="primer" class="control-label">REV</label>
	                                        <input type="text" class="form-control" name="rev"   value="" placeholder="ATAATTTGGCGGACTTTGGC"  >
	                                    </div>
	                                </div>
	                               
                              </div>
                            </div>


                        <div class="wizard-footer height-wizard">
                            <div class="pull-right">

                            	<!-- Hidden fields -->
                            	<input type="Hidden" id='c_count' name="c_count" value="1">
                            	<input type="Hidden" id='a_count' name="a_count" value="1">
                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd btn-sm' name='next' value='Next' />
                                <input type='submit' class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' name='method' value='Submit' />

                            </div>

                            <div class="pull-left">
                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' value='Previous' />
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </form>
			</div>
		</div>
	</div><!-- end row -->
</div> <!--  big container -->
<?php include 'includes/footerx.php';?>
    
