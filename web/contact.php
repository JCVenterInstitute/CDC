<?php include 'includes/header.php';?>
		<!-- Web Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>


			<section class="main-container">

				<div class="container">
					<div class="row">

						<!-- main start -->
						<!-- ================ -->
						<div class="main col-md-12">

							<!-- page-title start -->
							<!-- ================ -->
							<h1 class="page-title">Contact Us</h1>
							<!-- page-title end -->
							<div class="row">
								<div class="col-md-6">
									<p>If you have questions about or would like assistance using the ARMdb, please contact us using the form below.</p>
									<div class="alert alert-success hidden" id="MessageSent">
										We have received your message, we will contact you very soon.
									</div>
									<div class="alert alert-danger hidden" id="MessageNotSent">
										Oops! Something went wrong please refresh the page and try again.
									</div>
									<div class="contact-form">
										<form id="contact-form" role="form">
											<div class="form-group has-feedback">
												<label for="name">Name*</label>
												<input type="text" class="form-control" id="name" name="name" placeholder="">
												<i class="fa fa-user form-control-feedback"></i>
											</div>
											<div class="form-group has-feedback">
												<label for="email">Email*</label>
												<input type="email" class="form-control" id="email" name="email" placeholder="">
												<i class="fa fa-envelope form-control-feedback"></i>
											</div>
											<div class="form-group has-feedback">
												<label for="subject">Subject*</label>
												<input type="text" class="form-control" id="subject" name="subject" placeholder="">
												<i class="fa fa-navicon form-control-feedback"></i>
											</div>
											<div class="form-group has-feedback">
												<label for="message">Message*</label>
												<textarea class="form-control" rows="6" id="message" name="message" placeholder=""></textarea>
												<i class="fa fa-pencil form-control-feedback"></i>
											</div>
											<input type="submit" value="Submit" class="submit-button btn btn-default">
										</form>
									</div>
								</div>
								<div class="col-md-6">
									<!-- google maps start -->
									<div id="map-canvas"></div>
									<!-- google maps end -->
								</div>
							</div>
						</div>
						<!-- main end -->
						
					</div>
				</div>
			</section>

		<!-- JavaScript files placed at the end of the document so the pages load faster
		================================================== -->
		<!-- Contact form -->
		<script src="plugins/jquery.validate.js"></script>

		<!-- Google Maps javascript -->
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiSUdEgI_FImweM3idNl7M9z3Iarl1eTI&callback&amp;sensor=false"></script>
		<script type="text/javascript" src="js/google.map.config.js"></script>



<?php include 'includes/footer.php';?>
