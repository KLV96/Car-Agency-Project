<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');


if(isset($_GET['ConversationID']))
{
  $Passed_ConversationID = $_GET['ConversationID']; 
  $ConversationID = mysql_real_escape_string($Passed_ConversationID); 
  echo $Passed_ConversationID;

$query_delete_Conversations = "DELETE from conversations where Conversation_id='$ConversationID' ";

$query_delete_Conversations_members = "DELETE from conversations_members where Conversation_id='$ConversationID' ";

$query_delete_Conversations_messages = "DELETE from conversations_messages where Conversation_id='$ConversationID' ";



$response1 = @mysqli_query($dbc, $query_delete_Conversations);
$response2 = @mysqli_query($dbc, $query_delete_Conversations_members);
$response3 = @mysqli_query($dbc, $query_delete_Conversations_messages);

header('location: Inbox.php');

}




?>