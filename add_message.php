<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


if(isset($_POST['message_box']) && (!empty($_POST['message_box'])))
{

  require_once('inc/mysql_connect.php');
  $convID = $_SESSION['convID'];

  echo $convID;

  $txt = stripslashes(trim($_POST['message_box']));

  $txt_no_special_char = htmlspecialchars($txt, ENT_QUOTES);
  
  $sql = "INSERT INTO conversations_messages (Sender_Admin, Conversation_id, Customer_id, Message_date, Message_text) 
        VALUES (1,'$convID', '0', UNIX_TIMESTAMP(),'$txt_no_special_char')";

        @mysqli_query($dbc, $sql);  		

        $sql2 = "INSERT INTO conversations_members (conversation_id, CustomerID, conversation_Last_View) 
        VALUES ('$convID', '0', UNIX_TIMESTAMP())";

        @mysqli_query($dbc, $sql2);

        header("location: view_conversation.php?ConversationID=".$convID);

}

else
{
  $convID = $_SESSION['convID'];
  header("location: view_conversation.php?ConversationID=".$convID);
}
