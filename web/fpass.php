<?php include 'includes/header.php';?>
<?php
//session_start();
//require_once 'class.user.php';
$user = new USER();

if($user->is_logged_in()!="")
{
	$user->redirect('index.php');
}

if(isset($_POST['reset_submit']))
{
	$email = $_POST['txtemail'];
	
	$stmt = $user->runQuery("SELECT User_ID,First_Name FROM Actor WHERE Email=:email LIMIT 1");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
	if($stmt->rowCount() == 1)
	{
		$id = base64_encode($row['User_ID']);
		$code = md5(uniqid(rand()));
		
		$stmt = $user->runQuery("UPDATE Actor SET Enc_Password=:token WHERE Email=:email");
		$stmt->execute(array(":token"=>$code,"email"=>$email));
		// echo "HERE";
		$fname=$row["First_Name"];
		// var_dump($row);
		// die();
		// var_dump($row)
		$message=<<<HH
				   Hello $fname,
				   <br /><br />
				   You have requested a password change. If this was not you, please disregard this message.
				   <br /><br />
				   Click the following link to reset your password:
				   <br /><br />
				   <a href=http://cdc-1.jcvi.org:8081/resetpass.php?id=$id&code=$code>Reset password</a>
				   <br /><br />
HH;
		$subject = "Password Reset";
		
		$user->send_mail($email,$message,$subject);
		
		$msg = "<div class='alert alert-success'>
					<button class='close' data-dismiss='alert'>&times;</button>
					We've sent an email to $email.
                    Please click on the password reset link in the email to generate new password. 
			  	</div>";
	}
	else
	{
		$msg = "<div class='alert alert-danger'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry!</strong>  this email not found. 
			    </div>";
	}
}
?>



    <div class="container">

      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Forgot Password</h2><hr />
        
        	<?php
			if(isset($msg))
			{
				echo $msg;
			}
			else
			{
				?>
              	<div class='alert alert-info'>
				Please enter your email address. You will receive a link to create a new password via email.!
				</div>  
                <?php
			}
			?>
        
        <input type="email" class="input-block-level" placeholder="Email address" name="txtemail" required />
     	<hr />
        <button class="btn btn-danger btn-primary" type="submit" name="reset_submit">Generate new Password</button>
      </form>

    </div> <!-- /container -->

<?php include 'includes/footer.php';?>
