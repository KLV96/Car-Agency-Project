
<!DOCTYPE html> 



<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('Menu.php');
require_once('inc/mysql_connect.php');

$query = "SELECT CustomerID, Username,Name, city, country FROM users WHERE Username <> 'admin'";


$response = @mysqli_query($dbc, $query);

if($response){



echo '<table class= "jtabla">

<thead>

	<tr>
		<th>Name</th>
		<th><b>Username</th>
		<th>City</th>
		<th>Country</th>
		<th>Delete Customer</th>
	</tr>
<thead/>';

// mysqli_fetch_array will return a row of data from the query
// until no further data is available
while($row = mysqli_fetch_array($response)){


$customerID = $row['CustomerID'];
echo '<tbody>
<tr>
<td align="left">' .  
$row['Name'] . '</td><td align="left">' .
$row['Username'] . '</td><td align="left">' . 
$row['city'] . '</td><td align="left">' .
$row['country']  .'
<div class= "deleteOrder">
	<td style="color: #CC0000"><a href="deleteCustomer.php?customerID=',$customerID,'">Delete</td>
</div>
	';

echo '</tbody></tr>';
}

echo '</table>';

} else {

echo "Couldn't issue database query<br />";

echo mysqli_error($dbc);

}

// Close connection to the database
mysqli_close($dbc);

?>
