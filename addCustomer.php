<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require('Menu.php');

$msg = "";

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['city']) && isset($_POST['country']))
{

	
	$n = trim($_POST['name']);
	$un = trim($_POST['username']);
	$pw = trim($_POST['password']);
	$ci = trim($_POST['city']);
	$ctr = trim($_POST['country']);


	if (!empty($un) && !empty($pw) && !empty($n) && !empty($ci) && !empty($ctr))
	{	

		$upc = preg_match('@[A-Z]@', $pw);
		$lwc = preg_match('@[a-z]@', $pw);
		$num    = preg_match('@[0-9]@', $pw);
		$spcchar = preg_match('@[^\w]@', $pw);

		if (!preg_match('/^[A-Za-z\d_]{2,20}$/i', stripslashes(trim($_POST['username']))) )
		{
			$msg = 'Please check the format for Username';
		}

		elseif (!$upc || !$lwc || !$num || !$spcchar || strlen($pw) < 8 || strlen($pw) > 30 )
		{
			$msg = 'Please check the format of the password';
		}

		elseif ($un==$pw)
		{
			$msg = 'User ID can\'t be the same as the password' ;
		}

		elseif (!preg_match ('%^[A-Za-z\.\' \-]{2,50}$%', stripslashes(trim($_POST['name']))))
		{
			$msg = 'Please check the format for Name';
		}

		elseif (!preg_match ('%^[A-Za-z\.\' \-]{2,25}$%', stripslashes(trim($_POST['city']))))
		{
			$msg = 'Please check the format of the City';
		}

		elseif (!preg_match ('%^[A-Za-z\.\' \-]{2,30}$%', stripslashes(trim($_POST['city']))))
		{
			$msg = 'Country should be less than 30 characters';
		}

		else
		{
			 require_once('inc/mysql_connect.php');
			 $Ch_un_availanle_query= "SELECT name FROM users WHERE username='$un'";

			 $reslt = mysql_query($Ch_un_availanle_query);

			 $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);

			 if (mysql_num_rows($reslt)==0)
			 {
				 $query = "INSERT INTO users (Name,Country,Username,City,Password,CustomerID) 
				 VALUES ('$n','$ctr','$un','$ci','$hashed_pw',NULL)";
		        @mysqli_query($dbc, $query);
		        mysqli_close($dbc);

			 }
			 else
			 {
			 	$msg = "Username taken";
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

	<?php if ( (!empty($msg)) && $msg == "Username taken" )
	{ ?>

	<div class="customer_error_msg">	
		<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
	</div>

	<?php } elseif(!empty($msg)) { ?>
	<div class="bigger_height_error_msg">	
		<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
	</div>
	<?php }  ?>

	<form action="addCustomer.php" method="POST">
		<div class="adding_customers">
			<input type="text" name= "username" placeholder="Enter Username" required>
			<input type="password" name= "password" placeholder="Enter Password" required>	
			<input type="text" name= "name" placeholder="Enter Name" required>
			<input type="text" name= "city" placeholder="Enter City" required>
			<input type="text" name="country" placeholder="Enter Country" required>
			<br/><br/>
			<input type="submit" name="add" value="ADD CUSTOMER" id = "btn_addCustomer" required>
		</div>
		
	</form>

</body>



</html>


