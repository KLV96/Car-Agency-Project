<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}

require('Menu.php');

$msg = "";

if (isset($_POST['to']) && isset($_POST['msgArea']) && isset($_POST['send']))
{
	if(empty($_POST['to']))
	{
		$msg = 'Please enter at least one name';
	}
	elseif (!preg_match('/^[A-Za-z\d_]{2,20}$/i', stripslashes(trim($_POST['to']))) )
	{
		$msg = 'Please check the format for username';
	}
	elseif(empty($_POST['msgArea']))
	{
		$msg = "Message cannot be empty";
	}
	elseif (!preg_match('/^[A-Za-z\d_,.!?\s\S]+$/i', stripslashes(trim($_POST['msgArea']))) )
	{
		$msg = 'Please check the format for message';
	}
	else
	{
		global $un;
		$un = stripslashes(trim($_POST['to']));
		$txt = stripslashes(trim($_POST['msgArea']));
		require('inc/mysql_connect.php');

		$Ch_un_exist_query= "SELECT CustomerID FROM users WHERE username='$un'";

		$reslt = mysql_query($Ch_un_exist_query);
		$get_cs_ID = mysql_fetch_row($reslt);
		$cs_ID = $get_cs_ID[0];

		 if ((mysql_num_rows($reslt)!=0))
		 {
		 	if ((checkConvExist() == 1))
		 	{
		 		$Set_Converstation_ID = "INSERT INTO conversations (setter) VALUES ('1') ";
	        @mysqli_query($dbc, $Set_Converstation_ID);


	        $Set_Converstation_ID= mysqli_query($dbc,"SELECT Conversation_id FROM conversations ORDER BY Conversation_id DESC LIMIT 1");
	 		$reslt = mysqli_fetch_row($Set_Converstation_ID);
			$coversaionID = $reslt[0];

	        $sql = "INSERT INTO conversations_messages (Sender_Admin, Conversation_id, Customer_id, Message_date, Message_text) 
	        VALUES (1,'$coversaionID', '$cs_ID', UNIX_TIMESTAMP(),'$txt')";

	        @mysqli_query($dbc, $sql);  		

	        $sql2 = "INSERT INTO conversations_members (conversation_id, CustomerID, conversation_Last_View) 
	        VALUES ('$coversaionID', '$cs_ID', UNIX_TIMESTAMP())";

	        @mysqli_query($dbc, $sql2); 
		 	}
		 	else
		 	{
		 		$msg = "There is a conversations with " . $un;
		 	}

		 }
		 else
		 {
		 	$msg = "Please check the username of the customer";
		 }
	}
}

function checkConvExist()
{
	require('inc/mysql_connect.php');
	$uname = stripslashes(trim($_POST['to']));
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
	<title>Cz Organisation</title>
	<link rel="stylesheet" type="text/css" href="/Project_Reb/style.css"/>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>		

	<link rel="shortcut icon" type="image/png" href="/Project_Reb/img/favicon.png"/>
</head>

<body>

	<?php if ( (!empty($msg)) && ( ($msg == "Please check the username of the customer") ||
			($msg == "Please check the format for username") ||
			($msg == "Please check the format for message" ) ) )
	{ ?>

	<div class="bigger_height_error_msg">	
		<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
	</div>

	<?php } elseif(!empty($msg)) { ?>
	<div class="customer_error_msg">	
		<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
	</div>
	<?php }  ?>


	<form action="Admin_Message.php" method="POST" class="sending_Messages">
	

		<div>
			<input type = "text" name="to" id="to" placeholder="To Customer" required />
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


