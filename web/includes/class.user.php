<?php
include 'dbconfig.php';
class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	//public function register($uname, $fname, $lname, $email, $affiliation, $country, $title, $occupation, $code)
	public function register($uname,  $upass, $fname, $lname, $email, $affiliation, $title, $occupation, $code)
	{
		try
		{	
			//echo "INSERT INTO Actor(User_ID, Email, First_Name, Last_Name,  Affiliation, Title, Occupation, Enc_Password) VALUES($uname,  $email,  $fname, $lname, $affiliation, $title, $occupation, $code)";	die;

			$password = md5($upass);
			$date = date("Y-m-d H:i:s");
			$createBy = '0';
			$modifiedBy = '0';
													 
			$stmt = $this->conn->prepare("INSERT INTO Actor(User_ID, Email, Password, First_Name, Last_Name,  Affiliation, Title, Occupation, Enc_Password, Created_By, Modified_By) 
			                          VALUES(:User_ID, :Email, :Password, :First_Name, :Last_Name,  :Affiliation, :Title, :Occupation, :Enc_Password, :Created_By, :Modified_By)");													 
			$stmt->bindparam(":User_ID",$uname);
			$stmt->bindparam(":Email",$email);
			$stmt->bindparam(":Password",$password);
			$stmt->bindparam(":First_Name",$fname);
			$stmt->bindparam(":Last_Name",$lname);
			$stmt->bindparam(":Affiliation",$affiliation);
			//$stmt->bindparam(":Country",$country);
			$stmt->bindparam(":Title",$title);
			$stmt->bindparam(":Occupation",$occupation);
			$stmt->bindparam(":Enc_Password",$password);
			$stmt->bindparam(":Created_By", $createBy);			
			$stmt->bindparam(":Modified_By",$modifiedBy);			
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{

			echo 'Hi'.$ex->getMessage();
			
		}
	}
	
	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM Actor WHERE Email=:email_id");
			$stmt->execute(array(":email_id"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['Is_Active']=="Y")
				{
					if($userRow['Password']==md5($upass))
					{
						$_SESSION['userSession'] = $userRow['ID'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();   // true in the parameter
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="xwnugpmnfz@gmail.com";  
		$mail->Password="2FNmpGUnwx!";            
		$mail->SetFrom('xwnugpmnfztest@gmail.com','Coding Cage');
		$mail->AddReplyTo("xwnugpmnfztest@gmail.com","Coding Cage");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	
}