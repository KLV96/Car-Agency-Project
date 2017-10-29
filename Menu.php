<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Car Agency</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>	

	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
</head>

<body>
		<nav>	
				<div class="handle">Menu</div>	
				<ul>
					<li><a href="addCustomer.php"> Add Customer </a></li>
					<li><a href="View_and_delete_orders.php"> View &  Delete Orders  </a></li>
					<li><a href="Admin_Message.php"> Send New message </a></li>
					<li><a href="Inbox.php"> Inbox </a></li>
					<li><a href="View_and_delete_Customers.php"> View & Delete Customers  </a></li>
					<li><a href="changePassword.php"> Change Password </a></li>
					<li><a href="logout.php"> Log out </a></li>
				</ul>
			
		</nav>
	<script>
		$('.handle').on('click', function()
		{
			$('nav ul').toggleClass('showing');
		})	;
	</script>


</body>


</html>