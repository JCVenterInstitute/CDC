<?php require_once 'init.php'; ?>
<?php include 'config.inc.php';?>
<!DOCTYPE html>
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title>AMRDB : Anti-Microbial Database</title>
  <meta name="description" content="A database of anti microbial genes">
  <meta name="author" content="htmlcoder.me">

  <!-- Mobile Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico">

  <!-- Web Fonts -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>

  <!-- Bootstrap core CSS -->
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

  <!-- Font Awesome CSS -->
  <link href="fonts/font-awesome/css/font-awesome.css" rel="stylesheet">

  <!-- Fontello CSS -->
  <link href="fonts/fontello/css/fontello.css" rel="stylesheet">

  <!-- Plugins -->
  <link href="plugins/rs-plugin/css/settings.css" media="screen" rel="stylesheet">
  <link href="plugins/rs-plugin/css/extralayers.css" media="screen" rel="stylesheet">
  <link href="plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
  <link href="css/animations.css" rel="stylesheet">
  <link href="plugins/owl-carousel/owl.carousel.css" rel="stylesheet">
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.js"></script>
  <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>

  <!-- Count Down javascript -->
  <script type="text/javascript" src="plugins/jquery.countdown/jquery.plugin.js"></script>
  <script type="text/javascript" src="plugins/jquery.countdown/jquery.countdown.js"></script>
  <script type="text/javascript" src="js/coming.soon.config.js"></script>

  <!-- iDea core CSS file -->
  <link href="css/style.css" rel="stylesheet">

  <!-- Color Scheme (In order to change the color scheme, replace the red.css with the color scheme that you prefer)-->
  <link href="css/skins/brown.css" rel="stylesheet">

  <!-- Custom css -->
  <link href="css/custom.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]--><?php require_once $abs_us_root . $us_url_root . 'users/includes/header.php'; ?>
</head>

<!-- body classes:
          "boxed": boxed layout mode e.g. <body class="boxed">
          "pattern-1 ... pattern-9": background patterns for boxed layout mode e.g. <body class="boxed pattern-1">
  -->
<body class="front no-trans">
<!-- scrollToTop -->
<!-- ================ -->
<div class="scrollToTop"><i class="icon-up-open-big"></i></div>

<!-- page wrapper start -->
<!-- ================ -->
<div id="total-header" class="page-wrapper">

  <!-- header-top start (Add "dark" class to .header-top in order to enable dark header-top e.g <div class="header-top dark">) -->
  <!-- ================ -->
			<div class="header-top">
				<div class="">
					<div class="row">
						<div class="col-xs-2  col-sm-9">

							<!-- header-top-first start -->
							<!-- ================ -->
							<div class="header-top-first clearfix">
							</div>
							<!-- header-top-first end -->

						</div>
						<div class="col-xs-10 col-sm-3">

							<!-- header-top-second start -->
							<!-- ================ -->
							<div id="header-top-second"  class="clearfix">

								<!-- header top dropdowns start -->
								<!-- ================ -->
								<div class="header-top-dropdown">
									
                                   <!-- <div class="btn-group dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Sign Out</button>
                                            <ul class="dropdown-menu dropdown-menu-right dropdown-animation">
                                            <li>                                            
                                            <ul>
                                            <li><a href="logout.php">Sign Out</a></li>
                                            </ul>
                                            </li>
                                        </ul>
                                    </div>-->
									
                                    <div class="btn-group dropdown"> 
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Sign In</button>
										<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
											<li>
												<div id="login-response" style="display:none;">Authenticating.Pls Wait...</div>
												<?php 
                                                if(isset($_GET['inactive']))
                                                {
                                                    ?>
                                                    <div class='alert alert-error'>
                                                        <button class='close' data-dismiss='alert'>&times;</button>
                                                        <strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it. 
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <form class="login-form" method="post" id="login-form">
                                                <?php
                                                if(isset($_GET['error']))
                                                {
                                                    ?>
                                                    <div class='alert alert-success'>
                                                        <button class='close' data-dismiss='alert'>&times;</button>
                                                        <strong>Wrong Details!</strong> 
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="form-group has-feedback">
                                                        <h3 class="title text-center"><i>Coming Soon</i></h3>
                                                        <!-- countdown start -->
                                                        <div class="countdown"></div>
                                                </div>
												</form>
											</li>
										</ul>
									</div>
                                    <div class="btn-group dropdown">
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>Sign Up</button>
										<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
											<li>
                                            	
                                            	  <div  id="register-response" style="display:none;">
                                                 Processing....
                                                </div>
												<form class="login-form" method="post" id="signup-form">
													
                                                    <div class="form-group has-feedback">
                                                        <h3 class="title text-center"><i>Coming Soon</i></h3>
                                                        <!-- countdown start -->
                                                        <div class="countdown"></div>

													</div>
												</form>
											</li>
										</ul>
									</div>
                                   
									

								</div>
								<!--  header top dropdowns end -->

							</div>
							<!-- header-top-second end -->

						</div>
					</div>
				</div>
			</div>


  <!-- header start classes:
            fixed: fixed navigation mode (sticky menu) e.g. <header class="header fixed clearfix">
             dark: dark header version e.g. <header class="header dark clearfix">
        ================ -->
  <header class="header fixed clearfix">
    <!--<div class="container">-->
    <div class="">
      <div class="row">
        <div class="col-md-4">

          <!-- header-left start -->
          <!-- ================ -->
          <div class="header-left clearfix">

            <!-- logo -->
            <div class="logo">
              <div style="float:left;"><a href="index.php"><img id="logo" src="images/logo.png" alt="iDea"></a></div>
              <!--<div class="slogan">Forensic Microbiome Database</div>-->
            </div>

            <!-- name-and-slogan -->
            <!--<div class="site-slogan">
                                Forensic Microbiome Database
                            </div>-->

          </div>
          <!-- header-left end -->

        </div>
        <div class="col-md-8">

          <!-- header-right start -->
          <!-- ================ -->
          <div class="header-right clearfix">

            <!-- main-navigation start -->
            <!-- ================ -->
            <div class="main-navigation animated">

              <!-- navbar start -->
              <!-- ================ -->
              <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">

                  <!-- Toggle get grouped for better mobile display -->
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                  </div>

                  <!-- Collect the nav links, forms, and other content for toggling -->
                  <div class="collapse navbar-collapse" id="navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                      <li class="dropdown active">
                        <a href="index.php" class="dropdown-toggle" data-toggle="dropdown">Home</a>
                      </li>
                      <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">ANALYSIS</a>
                        <ul class="dropdown-menu">
                          <li class="dropdown">
                            <a href="sequence.php" class="dropdown-toggle" data-toggle="dropdown">BLAST and RGI</a>
                          </li>
                          <li class="dropdown">
                            <a href="browse.php" class="dropdown-toggle" data-toggle="dropdown">Browse and Search</a>
                          </li>
                        </ul>
                      </li>

                      <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">PRIMER</a>
                        <ul class="dropdown-menu">
                          <li class="dropdown">
                            <a href="primer.php" class="dropdown-toggle" data-toggle="dropdown">Search primer</a>
                          </li>
                          <li class="dropdown">
                            <a href="primer.php" class="dropdown-toggle" data-toggle="dropdown">Validate primer</a>
                          </li>
                        </ul>
                      </li>
                      <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Support</a>
                        <ul class="dropdown-menu">
                          <li class="dropdown">
                            <a href="help.php" class="dropdown-toggle" data-toggle="dropdown">User Manual</a>
                          </li>
                          <li class="dropdown">
                            <a href="sop.php" class="dropdown-toggle" data-toggle="dropdown">Standard Operating Procedures</a>
                          </li>
                          <li class="dropdown">
                            <a href="faq.php" class="dropdown-toggle" data-toggle="dropdown">Frequently Asked Question</a>
                          </li>
                          <li class="dropdown">
                            <a href="policy.php" class="dropdown-toggle" data-toggle="dropdown">Resource Policy</a>
                          </li>
                          <li class="dropdown">
                            <a href="about.php" class="dropdown-toggle" data-toggle="dropdown">Cite AMRDB</a>
                          </li>
                        </ul>
                      </li>
                      <li class="dropdown">
                        <a href="contact.php" class="dropdown-toggle" data-toggle="dropdown">Contact</a>
                      </li>
                    </ul>
                  </div>

                </div>
              </nav>
              <!-- navbar end -->

            </div>
            <!-- main-navigation end -->

          </div>
          <!-- header-right end -->

        </div>
      </div>
    </div>
  </header>
  <!-- header end -->


