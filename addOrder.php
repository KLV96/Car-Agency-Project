<?php

session_start();

if ( $_SESSION['logged_in'] != 1 ) 
{
	header('location: index.php');
}
	
	
	$msg = "";

if (isset($_POST['Quantity_ClassA']) && isset($_POST['Quantity_ClassC']) && isset($_POST['Quantity_ClassE']) && isset($_POST['Quantity_ClassM']) && isset($_POST['Quantity_ClassL'])) {


	$Qnt_Class_A = stripslashes(trim($_POST['Quantity_ClassA']));
	$Qnt_Class_C = stripslashes(trim($_POST['Quantity_ClassC']));
	$Qnt_Class_E = stripslashes(trim($_POST['Quantity_ClassE']));
	$Qnt_Class_M = stripslashes(trim($_POST['Quantity_ClassM']));
	$Qnt_Class_L = stripslashes(trim($_POST['Quantity_ClassL']));


	function checkAllBoxesEmpty($Qnt_Class_A,$Qnt_Class_C,$Qnt_Class_E,$Qnt_Class_M,$Qnt_Class_L) 
	{
		if (empty($Qnt_Class_A) && empty($Qnt_Class_C) && empty($Qnt_Class_E) && empty($Qnt_Class_M) && empty($Qnt_Class_L))
		{
			Global $msg;
			$msg = 'Please choose at least one item';
			return false;
		}

		return true;
	}

	function checkSyntax($Qnt_Class_A,$Qnt_Class_C,$Qnt_Class_E,$Qnt_Class_M,$Qnt_Class_L)
	{
		if (!empty($Qnt_Class_A)) 
		{
			if ((!preg_match('/^[0-9]{1,20}$/i', $Qnt_Class_A)))
			{
				Global $msg;
				$msg = 'Please check syntax of Quantity';
				return false;
			} 
		}		
			
		if (!empty($Qnt_Class_C)) 
		{
			if (!preg_match('/^[0-9]{1,20}$/i', $Qnt_Class_C))
			{
				Global $msg;
				$msg = 'Please check syntax of Quantity';
				return false;
			}
		}			

		if (!empty($Qnt_Class_E)) 
		{
			if (!preg_match('/^[0-9]{1,20}$/i', $Qnt_Class_E))
			{
				Global $msg;
				$msg = 'Please check syntax of Quantity';
				return false;
			}
		}	

		if (!empty($Qnt_Class_M)) 
		{
			if (!preg_match('/^[0-9]{1,20}$/i', $Qnt_Class_M))
			{
				Global $msg;
				$msg = 'Please check syntax of Quantity';
				return false;
			}
		}
				

		if (!empty($Qnt_Class_L)) 
		{
			if (!preg_match('/^[0-9]{1,20}$/i', $Qnt_Class_L))
			{
				Global $msg;
				$msg = 'Please check syntax of Quantity';
				return false;
			}
		}
		
		return true;	


	}





	if ((checkAllBoxesEmpty($Qnt_Class_A, $Qnt_Class_C, $Qnt_Class_E, $Qnt_Class_M, $Qnt_Class_L))
		&& checkSyntax($Qnt_Class_A,$Qnt_Class_C,$Qnt_Class_E,$Qnt_Class_M,$Qnt_Class_L) ) 
	{

	 		require_once('inc/mysql_connect.php');

	 		$un = $_SESSION['uname'];
	 		$get_CustomerID_query= mysqli_query($dbc,"SELECT CustomerID FROM users WHERE Username='$un' ");
	 		$reslt = mysqli_fetch_row($get_CustomerID_query);
			$CusID = $reslt[0];

			$Insert_ordeID = mysqli_query($dbc,"INSERT INTO tblorder VALUES (NULL,UNIX_TIMESTAMP(),'$CusID')");
			$get_orderID = mysqli_query($dbc,"SELECT OrderID FROM tblorder WHERE CustomerID= '$CusID' ORDER BY OrderID DESC");
			$reslt = mysqli_fetch_row($get_orderID);
			$ordID = $reslt[0];

			if (!empty($Qnt_Class_A)) 
			{
				$query_A = "INSERT INTO itemorder (Quantity,OrderID,ItemID,CustomerID) 
					 VALUES ('$Qnt_Class_A','$ordID','1','$CusID')";
			        @mysqli_query($dbc, $query_A);
			}
					

			if (!empty($Qnt_Class_C)) 
			{
				$query_C = "INSERT INTO itemorder (Quantity,OrderID,ItemID,CustomerID) 
				VALUES ('$Qnt_Class_C','$ordID','2','$CusID')";
			    @mysqli_query($dbc, $query_C);
			}
					

			if (!empty($Qnt_Class_E)) 
			{
				$query_E = "INSERT INTO itemorder (Quantity,OrderID,ItemID,CustomerID) 
				VALUES ('$Qnt_Class_E','$ordID','3','$CusID')";
			    @mysqli_query($dbc, $query_E);
			}		

			if (!empty($Qnt_Class_M))
			{
				$query_M = "INSERT INTO itemorder (Quantity,OrderID,ItemID,CustomerID) 
				VALUES ('$Qnt_Class_M','$ordID','4','$CusID')";
			    @mysqli_query($dbc, $query_M);
			}

					

			if (!empty($Qnt_Class_L)) 
			{
				$Qnt_Class_L = "INSERT INTO itemorder (Quantity,OrderID,ItemID,CustomerID) 
				VALUES ('$Qnt_Class_L','$ordID','5','$CusID')";
			    @mysqli_query($dbc, $Qnt_Class_L);
			}

		 	mysqli_close($dbc);

			$msg = 'Order Placed Successfully';
		
	}


}

?>

<!DOCTYPE html> 

<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cz Organisation</title>
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

	<?php if (!empty($msg) & (!$msg == 'Order Placed Successfully' || !$msg == 'Please choose at least one item') ) : ?>

		<div class="customer_error_msg">	
			<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
		</div>

	<?php elseif ($msg == 'Order Placed Successfully'): ?>
		<div class="customer_error_msg">	
			<h4><b><span style="color:#339933"> <?php echo $msg ?></span></b></h4>
		</div>
	

	<?php elseif ($msg == 'Please choose at least one item') : ?>
		<div class="customer_error_msg">	
			<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
		</div>

	<?php elseif ($msg == 'Please check syntax of Quantity') : ?>
		<div class="customer_error_msg">	
			<h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
		</div>
	
	<?php endif; ?>

	<form action="addOrder.php" method="POST">
		<div class="adding_orders">
			<h3> Mercedes-Benz </h3>
			
			<table class= addOrder>

				<tr>
			        <td align="center"><font size ="4"><strong>CLASS A </strong></font></td>
			        <td align="center"><input type="text" name= "Quantity_ClassA" placeholder="Quantity" default=0></strong></td>
			    </tr>

			    <tr>
			        <td align="center"><font size ="4"><strong>CLASS C </strong></font></td>
			        <td align="center"><input type="text" name= "Quantity_ClassC" placeholder="Quantity" default="0"></strong></td>
			    </tr>

			    <tr>
			        <td align="center"><font size ="4"><strong>CLASS E </strong></font></td>
			        <td align="center"><input type="text" name= "Quantity_ClassE" placeholder="Quantity" default="0"></strong></td>
			    </tr>

			    <tr>
			        <td align="center"><font size ="4"><strong>CLASS M </strong></font></td>
			        <td align="center"><input type="text" name= "Quantity_ClassM" placeholder="Quantity" default="0"></strong></td>
			    </tr>

			    <tr>
			        <td align="center"><font size ="4"><strong>CLASS L </strong></font></td>
			        <td align="center"><input type="text" name= "Quantity_ClassL" placeholder="Quantity" default="0"></strong></td>
			    </tr>

			    <div class= buttonmid>
			      	<tr>
			        	<td colspan="2" align="center" valign="bottom"><input type="submit" name="place" value="PLACE ORDER" id = "btn_addCustomer" required></td>
			    	</tr>
			    </div>

			  

			</table>

		</div>
		
	</form>

</body>



</html>


