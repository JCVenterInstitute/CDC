<?php
session_start();
date_default_timezone_set('America/Chicago'); // eliminate the date warning.
require_once 'class.user.php';
$user_login = new USER();

/*if($user_login->is_logged_in()!="")
{
	$user_login->redirect('home.php');
}
*/
if(isset($_POST['btn-login']))
{
	$email = trim($_POST['txtemail']);
	$upass = trim($_POST['txtupass']);
	
	if($user_login->login($email,$upass))
	{
		$user_login->redirect('index.php');
	}
}

$reg_user = new USER();
/*
if($reg_user->is_logged_in()!="")
{
	$reg_user->redirect('home.php');
}*/

if(isset($_POST['btn-signup']))
{	//echo "post";	echo "<pre>";	print_r($_POST);	die();
	$uname = trim($_POST['txtuname']);
	$upass = trim($_POST['txtpass']);
	$fname = trim($_POST['fname']);
	$lname = trim($_POST['lname']);
	$email = trim($_POST['txtemail']);
	$affiliation = trim($_POST['affiliation']);
	//$country = trim($_POST['country']);
	$title = trim($_POST['title']);
	$occupation = trim($_POST['occupation']);
	$code = md5(uniqid(rand()));
	$SESSION['signUp_status']='pass';
	$SESSION['signIn_status']='pass';
	$stmt = $reg_user->runQuery("SELECT * FROM Actor WHERE Email=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($stmt->rowCount() > 0)
	{
		$msg = "
		      <div class='alert alert-danger'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry !</strong>  email allready exists , Please Try another one
			  </div>
			  ";
		$SESSION['signUp_status']="fail";
	}
	else
	{
		if($reg_user->register($uname, $upass, $fname, $lname, $email, $affiliation, $title, $occupation, $code))
		{			
			$id = $reg_user->lasdID();		
			$key = base64_encode($id);
			$_SESSION['12345']=$id." and enc: ".$key;
			$id = $key;


			
			$message =<<<HH
						<html><body>
						Hello $uname,
						<br /><br />
						Welcome<br/>
						<br />
						<a href='http://cdc-1.jcvi.org:8081/verify.php?id=$id'>Click here</a> to activate your account.
						<br /><br />
						Thank you.</body></html>
HH;
		
			$subject = "Confirm Registration";
						
			try {
				$reg_user->send_mail($email,$message,$subject);	
			

				$msg = "
						<div class='alert alert-success'>
							<button class='close' data-dismiss='alert'>&times;</button>
							<strong>Success!</strong>  We've sent an email to $email.
	                    Please click on the confirmation link in the email to create your account. 
				  		</div>
						";
				$SESSION['signUp_status']="pass";
			} catch (Exception $e) {
				echo "<div class='alert alert-danger'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Error!</strong> we are unable to sent you an email 
			  		</div>";
			  		$SESSION['signUp_status']="fail";
			}

			
		}
		else
		{
			echo " Sorry , Query could no execute...";
			$SESSION['signUp_status']="fail";
		}		
	}
}

?>
<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<title>AMRdb</title>
		<meta name="description" content="AMRdb : Anti-Microbial Resistance database">
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
		<link href="css/skins/red.css" rel="stylesheet">

		<!-- Custom css -->
		<link href="css/custom.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
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
		<div class="page-wrapper">

			<!-- header-top start (Add "dark" class to .header-top in order to enable dark header-top e.g <div class="header-top dark">) -->
			<!-- ================ -->
			<div class="header-top dark">
				<div class="">
					<div class="row">
						<div class="col-xs-2  col-sm-9">

							<!-- header-top-first start -->
							<!-- ================ -->
							<div class="header-top-first clearfix">
								<ul class="list-inline pull-right hidden-sm hidden-xs">
								</ul>
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
									<?php 
                                    if($user_login->is_logged_in())
                                    {
									$stmt = $reg_user->runQuery("SELECT * FROM Actor WHERE ID=:ID");
									$stmt->execute(array(":ID"=>$_SESSION['userSession']));
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> 
										<?php if($stmt->rowCount() > 0)
										{
											echo "Welcome	".$row['User_ID'];
										}	?>
										</button>
                                    </div>    
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Sign Out</button>
                                            <ul class="dropdown-menu dropdown-menu-right dropdown-animation">
                                            <li>                                            
                                            <ul>
                                            <li><a href="logout.php">Sign Out</a></li>
                                            </ul>
                                            </li>
                                        </ul>
                                    </div>
									<?php
                                    }	else { 
                                    ?>
                                    <div class="btn-group dropdown"> 
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Sign In</button>
										<ul class="dropdown-menu dropdown-menu-right dropdown-animation showSignInError">
											<li>
												<div id="login-response" style="display:none;">Authenticating.Pls Wait...</div>
												<?php 
                                                if(isset($_GET['inactive']))
                                                {
                                                	$SESSION['signIn_status']='fail';
                                                    ?>
                                                    <div class='alert alert-danger'>
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
                                                	$SESSION['signIn_status']='fail';
                                                    ?>
                                                    <div class='alert alert-danger'>
                                                        <button class='close' data-dismiss='alert'>&times;</button>
                                                        <strong>Wrong credentials.</strong> 
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">Username</label>
                                                    <input type="email" class="form-control" placeholder="Email address" id="txtemail" name="txtemail" required >
                                                    <i class="fa fa-user form-control-feedback"></i>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">Password</label>
                                                    <input type="password" class="form-control" placeholder="Password" name="txtupass" id="txtupass" required>
                                                    <i class="fa fa-lock form-control-feedback"></i>
                                                </div>
                                                <button type="submit" class="btn btn-group btn-default btn-sm" name="btn-login">Sign in</button>
                                                <ul>
                                                    <li><a href="fpass.php">Forgot your password?</a></li>
                                                </ul>
												</form>
											</li>
										</ul>
									</div>
                                    <div class="btn-group dropdown">
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>Sign Up</button>
										<ul class="dropdown-menu dropdown-menu-right dropdown-animation showLoginError">
											<li>
                                            	
                                            	<?php if(isset($msg)) { ?>
                                                <div class='alert alert-danger'>
                                                <?php echo $msg;  ?>
                                                </div>
                                                <?php } ?>
												<form class="login-form" method="post" id="signup-form">
													
                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">Username</label>-->
														<input type="text" class="form-control" placeholder="Username" name="txtuname" required />
														<i class="fa fa-user form-control-feedback"></i>
													</div>
													<div class="form-group has-feedback">
														<!--<label class="control-label">Password</label>-->
														<input type="password" class="form-control" placeholder="Password" name="txtpass" required />
														<i class="fa fa-lock form-control-feedback"></i>
													</div>
                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">First Name</label>-->
														<input type="text" class="form-control" placeholder="First Name" name="fname" required />
													</div>
                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">Last Name</label>-->
														<input type="text" class="form-control" placeholder="Last Name" name="lname" required />
													</div>
                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">Email</label>-->
														<input type="email" class="form-control" placeholder="Email" name="txtemail" required />
													</div>
                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">Email</label>-->
														<input type="text" class="form-control" placeholder="Affiliation" name="affiliation" />
													</div>

                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">Email</label>-->
														<input type="text" class="form-control" placeholder="Title" name="title" />
													</div>
                                                    <div class="form-group has-feedback">
														<!--<label class="control-label">Email</label>-->
														<input type="text" class="form-control" placeholder="Occupation" name="occupation" />
													</div>
													<button type="submit" class="btn btn-group btn-default btn-sm" name="btn-signup">Sign Up</button>
													<ul>
														<li><a href="fpass.php">Forgot your password?</a></li>
													</ul>
												</form>
											</li>
										</ul>
									</div>
                                    <?php } ?>
									

								</div>
								<!--  header top dropdowns end -->

							</div>
							<!-- header-top-second end -->

						</div>
					</div>
				</div>
			</div>
			<!-- header-top end -->

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
                            <a href="sequence.php" class="dropdown-toggle" data-toggle="dropdown">BLAST</a>
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
                            <a href="faq.php" class="dropdown-toggle" data-toggle="dropdown">Frequently Asked Question</a>
                          </li>
                          <li class="dropdown">
                            <a href="about.php" class="dropdown-toggle" data-toggle="dropdown">Cite AMRDB</a>
                          </li>
                        </ul>
                      </li>
                      <li class="dropdown">
                        <a href="contact.php" class="dropdown-toggle" data-toggle="dropdown">Contact</a>
                      </li>
                      <?php  if($user_login->is_logged_in())
                      {
                      	$_SESSION['userID']=$row['ID'];
					  ?>				
					  <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Admin</a>
                        <ul class="dropdown-menu">
							<li class="dropdown">
                            <a href="submitx.php" class="dropdown-toggle" data-toggle="dropdown">Submit Data</a>
							</li>
							<li class="dropdown">
                            <a href="browse_admin.php" class="dropdown-toggle" data-toggle="dropdown">Edit Data</a>
							</li>
						</ul>
					  </li>
					  <?php } ?>
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
  <?php
	// check for the session if the user has successfully sign up for an account
	if(($SESSION['signUp_status']=='fail')){
		$error_login_drop=<<<HEY
		<script type="text/javascript">
$(document).ready(function () {
        $('.showLoginError').show();
});
</script>
HEY;
		echo $error_login_drop;
}

	// check for the session if the user has successfully sign up for an account
	if(($SESSION['signIn_status']=='fail')){
		$error_login_drop=<<<HEY
		<script type="text/javascript">
$(document).ready(function () {
        $('.showSignInError').show();
});
</script>
HEY;
		echo $error_login_drop;
}

?>


			<!-- header end -->
