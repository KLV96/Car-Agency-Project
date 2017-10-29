<?php


session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');
require_once('Menu.php');

$query = "";

$query = "SELECT itemorder.orderID, ItemID, Name, Quantity, Date_entered FROM itemorder, users, tblorder WHERE users.customerID = itemorder.customerID AND itemorder.orderID = tblorder.orderID ORDER BY Date_entered DESC, OrderID";


$response = @mysqli_query($dbc, $query);

$orderID_arr = array();
$orderID_arr_placeHolder = array();





echo '
<table class= "jtabla">

<thead>
<tr>
	<form action="View_orders_of_the_week.php" method="POST">
	<input type="submit" name="submit" value="Orders of the week" style="width:100%;font-size: 20px; background-color: #F9EEDA" />
	</form>
	</tr>

	<tr>
		<th><b>Name</th>
		<th><b>Item Type</th>
		<th>Quantity</th>
		<th>Date Entered</th>
		<th>Time Entered</th>
		<th>Delete order</th>
	</tr>
<thead/>';

$counter = 0;

date_default_timezone_set('Europe/Prague');

if (mysqli_num_rows($response) >= 1)
{

	// mysqli_fetch_array will return a row of data from the query
	// until no further data is gupnp_root_device_get_available(root_device)
	while($row = mysqli_fetch_array($response))
	{

		switch ($row['ItemID']) 
		{
		 	case '1':
		 		$type = 'A';
		 		break;
		 	case '2':
		 		$type = 'C';
		 		break;

		 	case '3':
		 		$type = 'E';
		 		break;

		 	case '4':
		 		$type = 'M';
		 		break;
		 	case '5':
		 		$type = 'L';
		 		break;
		}

		if (count($orderID_arr) == 0)
		{

			$nm = $row['Name'] ;
			$date = date('d/m/Y', $row['Date_entered']);
			$time = date('H:i:s', $row['Date_entered']);

		}



			$orderID = $row['orderID'];
		
			if(in_array($orderID, $orderID_arr))
			{
				$typeArr[$counter] = $type;
				$QuantityArr[$counter] = $row['Quantity'];

				

			}

			else
			{
				if ( (!empty($typeArr)) && (!empty($QuantityArr)) )
				{

					$query_get_dt = mysqli_query($dbc, "SELECT Date_entered FROM itemorder,users,tblorder WHERE tblorder.OrderID= '".$orderID_arr[0]."' ");
					$Un_nm_dt = mysqli_fetch_row($query_get_dt);
					$timestamp = $Un_nm_dt[0];
					$date = date('d/m/Y', $timestamp);
					$time = date('H:i:s', $timestamp);


					$query_get_ID = mysqli_query($dbc, "SELECT CustomerID FROM itemorder WHERE OrderID= '".$orderID_arr[0]."' ");
					$Un_ID = mysqli_fetch_row($query_get_ID);
					$CustID = $Un_ID[0];
					$query_get_Nm = mysqli_query($dbc, "SELECT Name FROM users WHERE CustomerID= '".$CustID."' ");
					$Un_nm = mysqli_fetch_row($query_get_Nm);
					$nm = $Un_nm[0];
					
					

					echo ' <tbody>
					<tr>
					<td align="left">' .  
					$nm. '</td><td align="left">' .
					implode(' |  ', $typeArr).'  ' . '</td><td align="left">' .
					implode(' | ', $QuantityArr).'  ' . '</td><td align="left">' .
					$date  . '</td><td align="left">' .
					$time.'
					<div class= "deleteOrder">
						<td style="color: #CC0000"><a href="deleteOrder.php?orderID=',$orderID_arr[0],'">Delete</td>
					</div>';

					unset($typeArr); 
					$typeArr = array(); 

					unset($QuantityArr); 
					$QuantityArr = array(); 
						
					// $orderID_arr[count($orderID_arr)] = $orderID_arr[0];
					if (count($orderID_arr) > 1 )
					{

					$orderID_arr_placeHolder[0] = $orderID_arr[0];
					$orderID_arr[count($orderID_arr)] = $orderID_arr_placeHolder[0];
					array_splice($orderID_arr, 0, 1);

					}




				}

				$orderID_arr[0] = $orderID;
				$typeArr[$counter] = $type;
				$QuantityArr[$counter] = $row['Quantity'];

				
					
				
			}

			$counter = $counter + 1;
			
	
	}

	

	if (count($orderID_arr) >= 1)
	{
			global $orderID, $nm,$typeArr,$QuantityArr,$date,$time;

			$query_get_ID = mysqli_query($dbc, "SELECT CustomerID FROM itemorder WHERE OrderID= '".$orderID_arr[0]."' ");
					$Un_ID = mysqli_fetch_row($query_get_ID);
					$CustID = $Un_ID[0];
					$query_get_Nm = mysqli_query($dbc, "SELECT Name FROM users WHERE CustomerID= '".$CustID."' ");
					$Un_nm = mysqli_fetch_row($query_get_Nm);
					$nm = $Un_nm[0];

			$query_get_dt = mysqli_query($dbc, "SELECT Date_entered FROM itemorder,users,tblorder WHERE tblorder.OrderID= '".$orderID_arr[0]."' ");
					$Un_nm_dt = mysqli_fetch_row($query_get_dt);
					$timestamp = $Un_nm_dt[0];
					$date = date('d/m/Y', $timestamp);
					$time = date('H:i:s', $timestamp);
					
			printToScreen($orderID, $nm,$typeArr,$QuantityArr,$date,$time);
	}


		

	echo '</tbody></tr>';


}
	echo '</table>';



	function printToScreen($orderID, $nm,$typeArr,$QuantityArr,$date,$time )
	{
		echo ' <tbody>
					<tr>
					<td align="left">' .  
					$nm. '</td><td align="left">' .
					implode(' |  ', $typeArr).'  ' . '</td><td align="left">' .
					implode(' | ', $QuantityArr).'  ' . '</td><td align="left">' .
					$date  . '</td><td align="left">' .
					$time.'
					<div class= "deleteOrder">
						<td style="color: #CC0000"><a href="deleteOrder.php?orderID=',$orderID,'">Delete</td>
					</div>';
	}




// Close connection to the database
mysqli_close($dbc);

?>

	