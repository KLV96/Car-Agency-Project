<?php

// Defined as constants so that they can't be changed
DEFINE ('DB_USER',);
DEFINE ('DB_PASSWOED',);
DEFINE ('DB_HOST',);
DEFINE ('DB_NAME',);

// $dbc will contain a resource link to the database
// The @ sign is used to prevent the user from getting the default error message.



if ($dbc = mysql_connect (DB_HOST, DB_USER, DB_PASSWOED)) 
{

	if (!mysql_select_db (DB_NAME)) 
	{ // If it can’t select the database.

		trigger_error('Could not select the database!<br />');
		exit();

	}

} else {

trigger_error('Could not connect to RDBMS!<br />');

exit();

}

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWOED, DB_NAME)
OR die('Could not connect to MYSQL '. mysqli_connect_error() );

?>



