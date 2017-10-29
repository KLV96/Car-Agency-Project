<!DOCTYPE html> 

<?php 

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');
require_once('Menu.php');

$day = date('w');
date_default_timezone_set('Europe/Prague');

if ( $day >= 1 )
{
	$week_start = date('Y-m-d', strtotime("previous monday"));
} else {
	$week_start = date('Y-m-d', strtotime("next monday"));
}

if ($day == 0)
{
	$week_start = date('Y-m-d', strtotime("previous monday"));
	$week_end = date('Y-m-d', strtotime("sunday"));
} else {
	$week_end = date('Y-m-d', strtotime("next sunday"));
}


echo '<p>' . 'Start of the week : ' . $week_start . '</p>';
echo '<p>' .'End of the week : ' . $week_end . '</p>';

$query = "SELECT  itemorder.orderID, ItemID, Name, Quantity, Date_entered 
		  FROM itemorder, users, tblorder 
		  WHERE users.customerID = itemorder.customerID
		  AND itemorder.orderID = tblorder.orderID
		  AND FROM_UNIXTIME(Date_entered) BETWEEN  '$week_start' AND '$week_end' 
		  ORDER BY Date_entered DESC";


$response = @mysqli_query($dbc, $query);
$orderID_arr = array();
$orderID_arr_placeHolder = array();

if($response){

echo '
<table class= "jtabla">

<thead>
	<tr>
		<th><b>Name</th>
		<th><b>Item Type</th>
		<th>Quantity</th>
		<th>Date Entered</th>
		<th>Time Entered</th>
		<th>Delete order</th>
	</tr>
<thead/>';

// mysqli_fetch_array will return a row of data from the query
// until no further data is available

$counter = 0;

while($row = mysqli_fetch_array($response)){

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
			global $nm,$date,$time;
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

echo '</table>';





} else {

echo "Couldn't issue database query<br />";

echo mysqli_error($dbc);

}

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