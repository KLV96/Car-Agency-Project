<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');


if(isset($_GET['customerID'])){
  $Passed_customerID = $_GET['customerID']; 
  $customerID = mysql_real_escape_string($Passed_customerID); 


$query_delete_Customer = "DELETE from users where customerID='$customerID' ";

$query_delete_orders_of_Customer = "DELETE from itemorder where customerID='$customerID' ";

$response1 = @mysqli_query($dbc, $query_delete_Customer);
$response2 = @mysqli_query($dbc, $query_delete_orders_of_Customer);

header('location: View_and_delete_Customers.php');

}




?>