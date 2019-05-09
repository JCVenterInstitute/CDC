<?php session_start();
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
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">    
		<script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<link href="css/style.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="front no-trans">
		<!-- scrollToTop -->
		<!-- ================ -->

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
        <div class="col-md-2">

          <!-- header-left start -->
          <!-- ================ -->
          <div class="header-left clearfix">

            <!-- logo -->
            <div class="logo">
              <div style="float:left;"><a href="index.php"><img id="logo" src="images/logo.png" alt="logo"></a></div>
              <!--<div class="slogan">Forensic Microbiome Database</div>-->
            </div>
          </div>
          <!-- header-left end -->

		</div>
        <div class="col-md-8">

          <!-- header-right start -->
          <!-- ================ -->

          <div class="header-left clearfix">

            <!-- main-navigation start -->
            <!-- ================ -->
            <div class="main-navigation animated"  style="float:left;">

              <!-- navbar start -->
							<!-- ================ -->
								<nav class="navbar navbar-default" role="navigation">
									<div class="container-fluid">
									
										<ul class="nav navbar-nav navbar-right">
											<li class="active"><a href="index.php">Home</a></li>
											<li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">ANALYSIS</a>
												<ul class="dropdown-menu">
													<li><a href="amr_map.php">AMR Map</a></li>
													<li><a href="sequence.php">Sequence Similarity Search Module</a></li>
													<li><a href="browse.php">Browse and Search</a></li>
												</ul>
											</li>
											<li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">PRIMER</a>
												<ul class="dropdown-menu">
													<li><a href="search_primer.php">Primer Catalog</a></li>
													<li><a href="validate_primer.php">Primer Finder</a></li>
												</ul>
											</li>
											<li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">Support</a>
												<ul class="dropdown-menu">
													<li><a href="help.php">User Manual</a></li>
													<li><a href="faq.php">Admin Manual</a></li>
												</ul>
											</li>						
											<li><a href="contact.php">Contact</a></li>
                      <?php  if($user_login->is_logged_in())
                      {
                      	$_SESSION['userID']=$row['ID'];
					  					?>											
											<li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">Admin </a>
												<ul class="dropdown-menu">
													<li><a href="submitx.php">Submit Data</a></li>
													<li><a href="browse_admin.php">Edit Data</a></li>
													<li><a href="data_refresh.php">Rebuild Reference Database</a></li>
												</ul>
											</li>
											<?php } ?>	
										</ul>
									</div>
								</nav> 
							
              <!-- navbar end -->

            </div>
            <!-- main-navigation end -->

          </div>
          <!-- header-right end -->

        </div>		
        <div class="col-md-2">

          <!-- header-right start -->
          <!-- ================ -->

          <div class="header-right clearfix">

            <!-- signup start -->
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
                                                <a href="fpass.php"><button class="btn btn-group btn-default btn-sm" name="btn-login">Forgot your password ?</button></a>
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
												</form>
											</li>
										</ul>
									</div>
                                    <?php } ?>
									

								</div>

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
