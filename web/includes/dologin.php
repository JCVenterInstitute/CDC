<?php
session_start();
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
	require_once 'dbconfig.php';
	$database = new Database();
	$db = $database->dbConnection();
	extract($_POST);
	$response=array();
	if(isset($txtemail) && isset($txtupass))
	{
		$txtupass=md5($txtupass);
		$stmt = $db->prepare("SELECT * FROM Actor WHERE Email=:ue");
		$stmt->execute(array(':ue'=>$txtemail));
		$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		
		if($stmt->rowCount() == 1)
		{
			if($userRow['userStatus']=="Y")
			{
				if($userRow['userPass']==$txtupass)
				{
					$response['status']=true;
					$_SESSION['userSession'] = $userRow['ID'];
				}
				else
				{
					$response['status']=false;
					$response['message']='InCorrect Credentials';	
				}
			}
			else
			{
					$response['status']=false;
					$response['message']='Account is Inactive';	
			}
		}
		else
		{
			$response['status']=false;
			$response['message']='InCorrect Credentials';	
		}
	echo json_encode($response);
	}
	
}
?>
