
<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');
require_once('Menu.php');

date_default_timezone_set('Europe/Prague');

if(isset($_GET['ConversationID']))
{
  $Passed_ConversationID = $_GET['ConversationID']; 
  $ConversationID = mysql_real_escape_string($Passed_ConversationID); 

  $convID = (int)$Passed_ConversationID;
  $_SESSION['convID']= $convID;

  $sql = "SELECT conversations_messages.message_date,
  				 conversations_messages.message_text,
  				 conversations_messages.Customer_id,
           conversations_messages.Sender_Admin AS sender_Admin
  		  FROM conversations_messages
  		  WHERE conversations_messages.conversation_id = '$convID'
  		  ORDER BY conversations_messages.message_date ";

  $res = mysql_query($sql);

  $messages = array();

  $sql_insert_last_view = "UPDATE conversations_members SET Conversation_Last_view= UNIX_TIMESTAMP()
    WHERE conversation_id= '$convID' AND  CustomerID != 0";

  $run_sql = mysql_query($sql_insert_last_view);

  while(($row = mysql_fetch_array($res)))
  {
  	$messages[] = array 
  	(
  		'date'		=> $row['message_date'],
  		'text'		=> $row['message_text'],
  		'CustomerID' => $row['Customer_id'],
      'sender_Admin' => $row['sender_Admin'],

  	);
  }


}




?>

	<?php foreach($messages as $message) {  ?>

  		<div class="message">
  			 <?php if($message['sender_Admin'] == 1) { ?> 

            <p class="heading"> <?php echo 'Admin'; ?>
            (<?php echo date('d/m/Y - H:i:s', $message['date']); ?>) </p>
            <p class= "text_admin"> <?php echo $message['text']; ?> </p> <?php } ?>


          <?php if($message['sender_Admin'] != 1) {  ?> 
            <p class="heading"> 
            <?php 
            $csID = $message['CustomerID'];
            $sql = mysqli_query($dbc,"SELECT Username FROM users WHERE CustomerID = '$csID'");
            $reslt = mysqli_fetch_row($sql);
            $CustUN = $reslt[0];
            echo  $CustUN ?> ( <?php echo date('d/m/Y - H:i:s', $message['date']); ?> )  
            </p>

            <p class= "text_customer"> <?php echo $message['text'] ?> </p>  
           
      <?php } } ?> 

      </div>

</br> </br>

<?php 

$sql_get_txt = mysqli_query($dbc,"select Message_text from conversations_messages WHERE conversation_id = '$convID' ORDER BY Message_date DESC limit 1");
$txt_Getter = mysqli_fetch_row($sql_get_txt);
$txt = $txt_Getter[0];

if (strlen($txt) > 150 && strlen($txt) < 210) {
?>
<div class="space"></div>
<?php } elseif(strlen($txt) > 210) {?>
<div class="Long_space"></div>
<?php } ?>



<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

<form action= "add_message.php" method= "POST">
  <div class= "Sender_in_Inbox">
    <textarea name="message_box"  placeholder="Press Enter to send the message" type="text" id="message_box"></textarea>
  </div> 
  <script>
  
  var textarea = document.getElementById('message_box');
  textarea.scrollTop = textarea.scrollHeight;

$(document).ready(function(){
    $('#message_box').keypress(function(e){
      if(e.which == 13){
           // submit via ajax or
           $('form').submit();
       }
    });
});
</script>
</form> 



</head>