<?php

session_start();

if ( $_SESSION['logged_in'] != 1  ) 
{
	header('location: index.php');
}


if( (isset($_POST['message_box'])) && (!empty($_POST['message_box'])) ) 
{

  require_once('inc/mysql_connect.php');
  $convID = $_SESSION['convID'];

  $txt = stripslashes(trim($_POST['message_box']));
  $txt_no_special_char = htmlspecialchars($txt, ENT_QUOTES);
  
  $un = $_SESSION['uname'];
  $sql_get_cusID =  mysqli_query($dbc,"SELECT CustomerID FROM users WHERE username= '$un'"); 
  $rslt = mysqli_fetch_row($sql_get_cusID);
  $cusID = $rslt[0];


  $sql = "INSERT INTO conversations_messages (Sender_Admin, Conversation_id, Customer_id, Message_date, Message_text) 
        VALUES (0,'$convID', '$cusID', UNIX_TIMESTAMP(),'$txt_no_special_char')";

        @mysqli_query($dbc, $sql);  		

        $sql2 = "INSERT INTO conversations_members (conversation_id, CustomerID, conversation_Last_View) 
        VALUES ('$convID', '$cusID', UNIX_TIMESTAMP())";

        @mysqli_query($dbc, $sql2);

mysqli_close($dbc);

header('location: customer_view_conversation.php');


}

else
{
  header('location: customer_view_conversation.php');
}

