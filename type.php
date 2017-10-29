<?php

	session_start();

	$pw = stripslashes(trim($_POST['password']));


	$upc = preg_match('@[A-Z]@', $pw);
	$lwc = preg_match('@[a-z]@', $pw);
	$num    = preg_match('@[0-9]@', $pw);
	$spcchar = preg_match('@[^\w]@', $pw);


	if (!preg_match('/^[A-Za-z\d_]{2,20}$/i', stripslashes(trim($_POST['username']))) )
	{
		setcookie('image_type', 'wrong_password', time()+5);
		header('location: index.php');
	}

	elseif (!$upc || !$lwc || !$num || !$spcchar || strlen($pw) < 8 || strlen($pw) > 30 )
	{
		setcookie('image_type', 'wrong_password', time()+5);
		header('location: index.php');
	}
	else
	{

		$entered_username = htmlentities(stripslashes(trim($_POST["username"])));
	    $entered_password = htmlentities(stripslashes(trim($_POST["password"])));

		require_once('inc/mysql_connect.php');
		
	 	
	    $query_check_login = mysqli_query($dbc, "SELECT Password,Username FROM users WHERE 
	    	Username = '$entered_username' ");

	    $Un_Pw_data = mysqli_fetch_row($query_check_login);


		if(mysqli_num_rows($query_check_login) == 1)
		{

			$pw_in_db = $Un_Pw_data[0];

			if ( password_verify( $entered_password,$pw_in_db ) )

			{
				if ( ($Un_Pw_data[1] == 'admin') )
				{
					$_SESSION['uname']= $entered_username;
					$_SESSION['logged_in'] = true;
					mysqli_close($dbc);
					header('location: addCustomer.php');
		        
				}

				else
				{
					$_SESSION['uname']= $entered_username;
					$_SESSION['logged_in'] = true;
					mysqli_close($dbc);
					header('location: addOrder.php');
				}
			}
			
			else 
			{
				setcookie('image_type', 'wrong_password', time()+5);
		        header('location: index.php');
			}


		} 
		else 
		{
			echo 'nono ';
			mysqli_close($dbc);
			setcookie('image_type', 'wrong_password', time()+5);
	        header('location: index.php');
	    }
	}


?>