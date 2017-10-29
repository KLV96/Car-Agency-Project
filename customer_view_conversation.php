  
<?php

session_start();

if ( $_SESSION['logged_in'] != 1 || $_SESSION['uname'] != ( 'admin' || 'Admin' )  ) 
{
	header('location: index.php');
}


require_once('inc/mysql_connect.php');
date_default_timezone_set('Europe/Prague');

$un = $_SESSION['uname'];
$sql_get_cusID =  mysqli_query($dbc,"SELECT CustomerID FROM users WHERE username= '$un'"); 

$rslt = mysqli_fetch_row($sql_get_cusID);
$cusID = $rslt[0];


$sql_check_conv_exist = mysqli_query($dbc,"SELECT Conversation_id FROM conversations_messages WHERE customer_id = '$cusID'");


$msg = "";

if(mysqli_num_rows($sql_check_conv_exist) >= 1)
{

  $rslt_covID = mysqli_fetch_row($sql_check_conv_exist);
  $convID = $rslt_covID[0];
  $_SESSION['convID'] = $convID ;


  $sql = "SELECT conversations_messages.message_date,
         conversations_messages.message_text,
         conversations_messages.Customer_id,
         conversations_messages.Sender_Admin AS sender_Admin
         FROM conversations_messages
         WHERE conversations_messages.conversation_id = '$convID'
         ORDER BY conversations_messages.message_date ";

  $res = mysql_query($sql);

  $messages = array();


  while(($row = mysql_fetch_array($res)))
  {

    $messages[] = array 
    (
      'date'    => $row['message_date'],
      'text'    => $row['message_text'],
      'CustomerID' => $row['Customer_id'],
      'sender_Admin' => $row['sender_Admin'],

    );
  }
}

else
  {
    $msg = "There is no previous conversation between you and the Admin, Please start a new one. "; 
  }

?>



<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Car Agency</title>
  <link rel="stylesheet" type="text/css" href="style.css"/>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script> 

  <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
</head>

<body>
    <div class= "typewriter">
    <h1> <?php echo $_SESSION['uname']  ?></h1>
    </div>
    <nav> 
        <div class="handle">Menu</div>  

        <ul>
          <li><a href="addOrder.php"> Place an Order  </a></li>
          <li><a href="Customer_Message.php"> New Message </a></li>
          <li><a href="customer_view_conversation.php"> View Conversation </a></li>
          <li><a href="logout.php"> Log out </a></li>
        </ul>
      
    </nav>
  <script>
    $('.handle').on('click', function()
    {
      $('nav ul').toggleClass('showing');
    });
  </script>



<?php if($msg == "") { ?>
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
           
      <?php } }?>
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

<form action= "add_message_customer.php" method= "POST">
  <div class= "Sender_in_Inbox">
    <textarea name="message_box"  placeholder="Press Enter to send the message" type="text" id="message_box"></textarea>
  </div> 
  <script>
  
  <?php 
if (isset($_POST['message_box']))
{
  echo 'sadfsdf';
  if (count($_POST['message_box'])> 150 ) {  ?>
  </br> </br> </br> </br> </br> </br>
<?php } } ?>

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


<?php } else {  ?>

    <div class="Customer_Inbox_message"> 
      <h4><b><span style="color:#F00"> <?php echo $msg ?></span></b></h4>
    </div>

<?php } ?>



</body>



</html>

