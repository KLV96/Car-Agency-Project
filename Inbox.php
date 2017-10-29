<?php


session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}

date_default_timezone_set('Europe/Prague');
require_once('inc/mysql_connect.php');
require_once('Menu.php');


// Close connection to the database



function fetch_conversations()
{
	
	$sql = "SELECT conversations.conversation_id, 
					MAX(conversations_messages.message_date) AS conversation_last_reply,
					MAX(conversations_messages.Message_date) > MAX(conversations_members.Conversation_Last_view) AS conversation_unread
			FROM conversations

			LEFT JOIN conversations_messages ON conversations.conversation_id = conversations_messages.conversation_id
			INNER JOIN conversations_members ON conversations.conversation_id = conversations_members.conversation_id

			GROUP BY conversations.conversation_id
			ORDER BY conversation_last_reply desc";

	 $result = @mysql_query($sql);
	 $conversations = array();
	 global $UsersArr;
	 $UsersArr = array();

	while($row = mysql_fetch_array($result)) 
	 {

		$conversations[] = array 
			(
				'id'					=> $row['conversation_id'],
				'last_reply'			=> $row['conversation_last_reply'],
				'unread_messages'		=> ($row['conversation_unread'] == 1),
			);
	 }

	  
return $conversations;

}





?>

<div class= "conversations" >
	<?php


	$conversations = fetch_conversations();


	$z=0;

 	foreach ($conversations as $conversation) { 

 		$convID = $conversation['id'];

 		$sql_get_CusID= mysqli_query($dbc,"SELECT Customer_id FROM conversations_messages WHERE Customer_id NOT IN (SELECT Username from users WHERE Username= 'Admin') AND conversation_id = '$convID'");

 		$get_CusID = mysqli_fetch_row($sql_get_CusID);

		$CusID = $get_CusID[0];

		$CusID = (int)$CusID;


 		$sql_get_usn = mysqli_query($dbc,"SELECT Username FROM users WHERE CustomerID = '$CusID' ");

 		$get_Uns = mysqli_fetch_row($sql_get_usn);

 		$Un = $get_Uns[0];



		
 		?>

		<div class="conversation"> <?php if ($conversation['unread_messages']==1) { ?>
 			<h2 style="color: #850909">	
 			<td style="color: #850909"><a href="deleteConversation.php?ConversationID= <?php echo $convID ?>" >[X] </td>&nbsp;
 			<a href="view_conversation.php?ConversationID=<?php echo $convID ?>"><?php echo $Un ?> </a>
 			</h2>
 			<p>Last Reply: <?php echo date('d/m/Y H:i:s',$conversation['last_reply']); ?></p>
 			<?php 
 			} else { 
 			?>

 		<div class="conversation">  
 			<h2 style="color: #FFFFFF">
 			<td style="color: #850909"><a href="deleteConversation.php?ConversationID= <?php echo $convID ?>" >[X] </td>&nbsp;
 			<a href="view_conversation.php?ConversationID=<?php echo $convID ?>"><?php echo  $Un ?> </a>
 			</h2>
 			<p>Last Reply: <?php echo date('d/m/Y H:i:s',$conversation['last_reply']); ?></p>
 			<?php 
 			} 
 			?>

		</div>

	<?php 
	}
		mysqli_close($dbc);
	?>
</div>

