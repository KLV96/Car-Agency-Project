<?php

session_start();

if ( $_SESSION['logged_in'] != 1 ) 
{
	header('location: index.php');
}

$msg = "";

if (isset($_POST['msgArea']) && isset($_POST['send']))
{

	if(empty($_POST['msgArea']))
	{
		$msg = "Message cannot be empty";
	}
	elseif (!preg_match('/^[A-Za-z\d_,. ]+$/i', stripslashes(trim($_POST['msgArea']))) )
	{
		$msg = 'Please check the format for message';
	}
	else
	{

		if ((checkConvExist() == 1))
		{		
			$txt = stripslashes(trim($_POST['msgArea']));		
			$uname = $_SESSION['uname'];
			require('inc/mysql_connect.php');
			$Set_Converstation_ID = "INSERT INTO conversations (setter) VALUES ('1') ";
	        @mysqli_query($dbc, $Set_Converstation_ID);

	        $get_ID = mysqli_query($dbc,"SELECT customerID FROM users where username= '$uname' ");
	 		$ID_Getter = mysqli_fetch_row($get_ID);
	 		$ID = $ID_Getter[0];
	        $Set_Converstation_ID= mysqli_query($dbc,"SELECT Conversation_id FROM conversations ORDER BY Conversation_id DESC LIMIT 1");
	 		$reslt = mysqli_fetch_row($Set_Converstation_ID);
			$coversaionID = $reslt[0];

	        $sql = "INSERT INTO conversations_messages (Conversation_id, Customer_id, Message_date, Message_text) 
	        VALUES ('$coversaionID', '$ID', UNIX_TIMESTAMP(),'$txt')";

	        @mysqli_query($dbc, $sql);  		

	        $sql2 = "INSERT INTO conversations_members (conversation_id, CustomerID, conversation_Last_View) 
	        VALUES ('$coversaionID', '$ID', UNIX_TIMESTAMP())";

	        @mysqli_query($dbc, $sql2);
	    }
	    else
	    {
	    	$msg = "Conversation Exists. Go to view Conversation";
	    }

	}
			
	
}

function checkConvExist()
{
	require('inc/mysql_connect.php');
	$uname = $_SESSION['uname'];
	$get_ID = mysqli_query($dbc,"SELECT customerID FROM users where username= '$uname' ");
	$ID_Getter = mysqli_fetch_row($get_ID);
	$ID = $ID_Getter[0];
	$Ch_conv_exist_query= "SELECT Conversation_id FROM conversations_messages WHERE Customer_id= '$ID' ";

	$reslt2 = mysql_query($Ch_conv_exist_query);


	if (mysql_num_rows($reslt2)==0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

mysqli_close($dbc);

?>

<!DOCTYPE html> 

<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Car Agency</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>	

	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
</head>

<body>
		<div class= "typewriter">
		<h1> <?php echo $_SESSION['uname']  ?></h1>
		</div>
		<nav>	
				<div class="handle">Menu</div>	

				<ul>
					<li><a href="addOrder.php"> Place an Order  </a></li>
					<li><a href="Customer_Message.php"> New Message </a></li>
					<li><a href="customer_view_conversation.php"> View Conversation </a></li>
					<li><a href="logout.php"> Log out </a></li>
				</ul>
			
		</nav>
	<script>
		$('.handle').on('click', function()
		{
			$('nav ul').toggleClass('showing');
		});
	</script>


	<?php if (!empty($msg))
	{ ?>
	<div class="customer_error_msg">	
		<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
	</div>
	<?php } ?>


	<form action="Customer_Message.php" method="POST" class="sending_Messages">
	

		<div>
			<h3>To:	&nbsp; Admin </h3>
		</div>

		<div>
			<textarea name="msgArea" rows="15" cols="35"></textarea>
		</div>

		</br>
		<div>
			<input type = "submit" value="SEND" name = "send" />
		</div>

	</form>

</body>



</html>


