<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require('Menu.php');

$msg = "";

if (isset($_POST['username']) && isset($_POST['newPassword']) )
{

	$username = $_POST['username'];
	$newPassword = trim($_POST['newPassword']);

	if (!empty($username) && !empty($newPassword))
	{	

		$uppercase = preg_match('@[A-Z]@', $newPassword);
		$lowercase = preg_match('@[a-z]@', $newPassword);
		$number    = preg_match('@[0-9]@', $newPassword);
		$specialChars = preg_match('@[^\w]@', $newPassword);

		if (!preg_match('/^[A-Za-z\d_]{2,20}$/i', stripslashes(trim($_POST['username']))) )
		{
			$msg = 'Please check the format for Username';
		}

		elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPassword) < 8 || strlen($newPassword) > 30 )
		{
			$msg = 'Please check the format of the password';
		}

		else
		{

			require_once('inc/mysql_connect.php');

			$query_check_username = "SELECT Name FROM users WHERE username='$username'";

			$response_username_check = @mysqli_query($dbc, $query_check_username);

			$row_cnt = $response_username_check->num_rows;
			
			if($row_cnt>0)
			{

				$hashed_pw = password_hash($newPassword, PASSWORD_DEFAULT);
				
				 $query = "UPDATE users SET Password= '$hashed_pw' WHERE username='$username'";

		        $response = @mysqli_query($dbc, $query);
		        mysqli_close($dbc);

			}

			else
			{

				$msg= 'Please check the username';

			}
		}
	}



}



?>

<!DOCTYPE html> 

<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cz Organisation</title>
	<link rel="stylesheet" type="text/css" href="/Project_Reb/style.css"/>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>	

	<link rel="shortcut icon" type="image/png" href="/Project_Reb/img/favicon.png"/>
</head>

<body>


	<?php if (!empty($msg))
	{ ?>

	<div class="ChangePassword_errorMessage">	
		<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
	</div>

	<?php } ?>
	<form action="changePassword.php" method="POST">
		<div class="change_Password">
			<input type="text" name= "username" placeholder="Enter Username" required>
			<input type="password" name= "newPassword" placeholder="Enter New Password" required>	

			<br/><br/>
			<input type="submit" name="change_password" value="CHANGE PASSWORD" id = "btn_addCustomer" required>
		</div>
		
	</form>

</body>



</html>


