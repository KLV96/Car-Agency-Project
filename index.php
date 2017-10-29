
<!DOCTYPE html> 
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Car Agency</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>	
	<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css"/>	

	<link href="css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="shortcut icon" type="image/png" href="../img/favicon.png"/>
	<?php 
		
		error_reporting(E_ALL & ~E_NOTICE);

		if ($_COOKIE['image_type'] != 'wrong_password')
		{
			$_COOKIE['image_type'] = 'favicon';
		}
	?>
</head>
<body>

	<div class="container">

	<img src="img/<?php echo $_COOKIE['image_type'] ?>.png">
	<br><br>

		<form action="type.php" method="POST">
			<div class="form-input">
			<input type="text" name="username" placeholder="Enter Username" 
			maxlength = "30" value= "<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" required>
			</div>

			<div class="form-input">
			<input type="password" name="password" placeholder="Enter Password" maxlength = "30" value= "<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" required>
			</div>

			<input type="submit" name="submit" value="LOGIN" class="btn-login">
		</form>

	</div>

</body>
</html>

