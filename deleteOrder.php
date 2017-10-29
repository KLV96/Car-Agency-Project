<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');


if(isset($_GET['orderID'])){
  $Passed_orderID = $_GET['orderID']; 
  $orderID = mysql_real_escape_string($Passed_orderID); 


$query = "DELETE from itemorder where orderID='$orderID' ";

$response = @mysqli_query($dbc, $query);

header('location: View_and_delete_orders.php');

}




?>