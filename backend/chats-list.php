<? 
session_start();
if(!isset($_SESSION['id'])){
    $location = 'client/login';
echo "<script type='text/javascript'>document.location('$location');</script>";

}
//get user chats
include('Chat.class.php');
$chat = new Chats();
$chats = $chat->getChats($_SESSION['id']);

//get user chats
include('User.class.php');
$new_user = new User();

if(isset($_GET['uid']) and isset($_GET['uname']) and isset($_GET['dp'])) {
$sid = $_GET['uid'];
$rid = $_SESSION['id'];

$chat->setSeen($sid,$rid);
}
?>



<!doctype html>
<html>
<head>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">




	<link rel="stylesheet" href="/client/static/css/bootstrap.min.css" >
  <link rel="stylesheet" href="/client/static/css/chats-list.css" >
  
  <link rel="stylesheet" href="/client/static/css/custom.css" >
	<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
<link rel="preload" href="/font-awesome/css/font-awesome.min.css" as="style" >

</head>
<body>
<div class="page">
<? if(isset($_GET['uid'])):  ?>
  
						<input type="hidden" name="user_id" id="user_id" value="<? echo $_GET['uid']; ?>">
						<input type="hidden" name="reciever" id="rid" value="<? echo $_GET['uid']; ?>">
						<input type="hidden" name="sender" id="sid" value="<? echo $_SESSION['id']; ?>">
						
<? endif; ?>

<input type="hidden" name="sender" id="sess_user" value="<? echo $_SESSION['id']; ?>">
  <div class="marvel-device nexus5">
  
    <div class="screen">
      <div class="screen-container">
      
	  <div class="chat">
          <div class="chat-container">
		  
	<div class="loading">Loading&#8230;</div>
            <div class="user-bar">
              <div class="back">
                <i class="fa fa-arrow-left" onClick="goBack();"></i>
              </div>
              <?if (isset($_GET['uid'])):  ?>
              <div class="avatar hidden-xs">
				<a href="<? echo $_GET['dp']; ?>">
				<img src="<? echo $_GET['dp']; ?>" alt="Avatar">
				</a>
              </div>
              <div class="name hidden-xs">
				<span style="color:white" ><? echo $_GET['uname']; ?></span>
					
					<? if ($_GET['last_active'] != null): ?>
				<span class="status online-status" ></span>
					<? endif; ?>
              </div>
            
                <? else: ?>
              <div class="name">
                <span> Messages</span>
              </div>
              <? endif; ?>
          
             <div class="actions more" >
			 
			  <span class="attach hidden-xs">
						<input type="file" id="file" name="file">
					</span>
	
					<span class="pull-right">
              <a class="navbar-brand xchat-logo" style="background:red;color:white" href="/">Xchat</a>
					</span>
              </div>
            </div>
            <div class="conversation">
              <div class="conversation-container">


<div class="hidden-xs">
<div class="wrapper">
  <!-- Sidebar Holder -->
  <nav id="sidebar">
   
      <span class="chats-title">Chats list  </span>
      <span class="list-toggle">
          <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                                <i class="glyphicon glyphicon-align-left"></i>    
                            </button>
                           
 </span>
      
            <div class="conversation">
              <div class="conversation-container">

<ul class="list-group msg-list">
<? 
if(mysqli_num_rows($chats) > 0):
$ids=array();
foreach($chats as $row): 
    if($row['sender']==$_SESSION['id']){
	$user_id=$row['reciever'];
	}else{
		$user_id=$row['sender'];
    }

	if(!in_array($user_id,$ids)):
$user = $new_user->getUserById($user_id);
$msg_len =15;
?>
<li style="padding:3px; color:grey; !important" class="list-group-item">
<a href="chats-list.php?uname=<? echo $user['username']; ?>&dp=<? echo $user['dp']; ?>&uid=<? echo $user['id']; ?>&last_active=<? echo $user['last_active']; ?>">

<? echo $user['username'];  ?><br >
<img style="width:30px;height:30px" src="<? echo $user['dp']; ?>" /> 

<? if($row['type'] == 'image') :?>
<i class="fa fa-file"></i>
<? else:
    if($row['seen'] == 0):
$newCount = $chat->countNewUserChats($_SESSION['id'], $user_id);
    ?>
<strong><span id="<? echo $row['id']; ?>" class="msg-text" style="color:black; font-weight:bold" font-size="10px"><?   echo $chat->limited_word($row['msg'], $msg_len) ?> </span></strong>
<? if($newCount > 0): ?> <sup class=""><span class="badge"><span  style="color:brown" class="msg-count"> <? echo $newCount; ?></span></span></sup>
<? endif; ?>
    <? else: ?>

<strong><span id="<? echo $row['id']; ?>" class="msg-text" style="color:grey; font-weight:bold" font-size="10px"><? echo $chat->limited_word($row['msg'], $msg_len) ?> </span></strong>
<?
endif;
endif; ?>
</a>

<em class="msg-time"><? echo $chat->time_elapsed_string($row['time']); ?> </em>

</li>

<?
endif;
      $ids[]=$user_id;
endforeach; 
else:
?>
<li> Your chats will appear here </li>
<? endif; ?>
</ul>
</div></div>
    <ul class="list-unstyled CTAs">
      <li><a href="/index" class="article">Chat more people</a></li>
    </ul>
  </nav>

  <!-- Page Content Holder -->
  <div id="content">
      <div class="conversation-bs">
      <div class="conversation-container">
        
      </div>

        	<div class="chat-inp">
				<div class="emoji input-group-addon"></div>
				<div class="chat-input">
					<div id="text-input" class="input" contenteditable="true">
				</div>
				</div>
				<div id="scroll-div"></div>
				<div class="opts input-group-addon">
					<a class="send"></a>
					<a class="send"></a>
				</div>
			</div>
			  <div class="emoji-dashboard">
				<ul class="emojis">
				
					<li class="emoji" data-clipboard-text="fire"><i class="em em-fire"></i></li>
					<li class="emoji" data-clipboard-text="first_place_medal"><i class="em em-first_place_medal"></i></li>
					 
	                <li class="emoji" data-clipboard-text="ok_hand"><i class="em em-ok_hand"></i></li>
 
	                <li class="emoji" data-clipboard-text="middle_finger"><i class="em em-middle_finger"></i></li>

					<li class="emoji" data-clipboard-text="point_left"><i class="em em-point_left"></i></li>
					<li class="emoji" data-clipboard-text="point_right"><i class="em em-point_right"></i></li>
				
					<li class="emoji" data-clipboard-text="crossed_fingers"><i class="em em-crossed_fingers"></i></li>

					<li class="emoji" data-clipboard-text="face_the_horns"><i class="em em-the_horns"></i></li>
					<li class="emoji" data-clipboard-text="facepunch"><i class="em em-facepunch"></i></li>
					<li class="emoji" data-clipboard-text="fist"><i class="em em-fist"></i></li>

					<li class="emoji" data-clipboard-text="--1"><i class="em em---1"></i></li>
					<li class="emoji" data-clipboard-text="-1"><i class="em em--1"></i></li>
					<li class="emoji" data-clipboard-text="100"><i class="em em-100"></i></li>
					
					<li class="emoji" data-clipboard-text="baby"><i class="em em-baby"></i></li>
						<li class="emoji" data-clipboard-text="money_mouth_face"><i class="em em-money_mouth_face"></i></li>
					<li class="emoji" data-clipboard-text="moneybag"><i class="em em-moneybag"></i></li>
					

					<li class="emoji" data-clipboard-text="wink"><i class="em em-wink"></i></li>
					<li class="emoji" data-clipboard-text="yum"><i class="em em-yum"></i></li>
					<li class="emoji" data-clipboard-text="zany_face"><i class="em em-zany_face"></i></li>

					<li class="emoji" data-clipboard-text="mask"><i class="em em-mask"></i></li>
					<li class="emoji" data-clipboard-text="angel"><i class="em em-angel"></i></li>
					<li class="emoji" data-clipboard-text="anger"><i class="em em-anger"></i></li>
					<li class="emoji" data-clipboard-text="angry"><i class="em em-angry"></i></li>
					<li class="emoji" data-clipboard-text="anguished"><i class="em em-anguished"></i></li>
                    <li class ="emoji"  data-clipboard-text="hugging_face"> <i class="em em-hugging_face" aria-role="presentation" aria-label="HUGGING FACE"></i></li>
					

					<li class="emoji" data-clipboard-text="nerd_face"><i class="em em-nerd_face"></i></li>
					<li class="emoji" data-clipboard-text="rage"><i class="em em-rage"></i></li>
					<li class="emoji" data-clipboard-text="open_mouth"><i class="em em-open_mouth"></i></li>


					<li class="emoji" data-clipboard-text="sweat"><i class="em em-sweat"></i></li>
					<li class="emoji" data-clipboard-text="thinking_face"><i class="em em-thinking_face"></i></li>
					<li class="emoji" data-clipboard-text="stuck_out_tongue"><i class="em em-stuck_out_tongue"></i></li>
<li class="emoji" data-clipboard-text="stuck_out_tongue_winking_eye"><i class="em em-stuck_out_tongue_winking_eye"></i></li>



	                <li class="emoji" data-clipboard-text="shrug"><i class="em em-shrug"></i></li>
	                <li class="emoji" data-clipboard-text="shushing_face"><i class="em em-shushing_face"></i></li>
					<li class="emoji" data-clipboard-text="smile"><i class="em em-smile"></i></li>

  <li class="emoji" data-clipboard-text="slightly_smiling_face"><i class="em em-slightly_smiling_face"></i></li>
					<li class="emoji" data-clipboard-text="sleeping"><i class="em em-sleeping"></i></li>

	                <li class="emoji" data-clipboard-text="blush"><i class="em em-blush"></i></li>
	                <li class="emoji" data-clipboard-text="hushed"><i class="em em-hushed"></i></li>
					<li class="emoji" data-clipboard-text="laughing"><i class="em em-laughing"></i></li>
					<li class="emoji" data-clipboard-text="joy"><i class="em em-joy"></i></li>
					<li class="emoji" data-clipboard-text="innocent"><i class="em em-innocent"></i></li>
                    <li class ="emoji"  data-clipboard-text="frowning"> <i class="em em-frowning" aria-role="presentation"></i></li>
                    
					<li class="emoji" data-clipboard-text="grimacing"><i class="em em-grimacing"></i></li>
					<li class="emoji" data-clipboard-text="full_moon_with_face"><i class="em em-full_moon_with_face"></i></li>

					<li class="emoji" data-clipboard-text="grinning"><i class="em em-grinning"></i></li>
                    <li class ="emoji"  data-clipboard-text="confused"> <i class="em em-confused" aria-role="presentation"></i></li>

					
					<li class="emoji" data-clipboard-text="cry"><i class="em em-cry"></i></li>
					<li class="emoji" data-clipboard-text="drooling"><i class="em em-drooling_face"></i></li>
					<li class="emoji" data-clipboard-text="face_with_hand_over_mouth"><i class="em em-face_with_hand_over_mouth"></i></li>
					<li class="emoji" data-clipboard-text="face_with_raised_eyebrow"><i class="em em-face_with_raised_eyebrow"></i></li>
					<li class="emoji" data-clipboard-text="upside_down_face"><i class="em em-upside_down_face"></i></li>
	
					<li class="emoji" data-clipboard-text="face_with_rolling_eyes"><i class="em em-face_with_rolling_eyes"></i></li>
					<li class="emoji" data-clipboard-text="face_with_monocle"><i class="em em-face_with_monocle"></i></li>
                     	<li class="emoji" data-clipboard-text="face_vomiting"><i class="em em-face_vomiting"></i></li>
                    <li class ="emoji"  data-clipboard-text="face_palm"> <i class="em em-face_palm" aria-role="presentation"></i></li>

                      
        
					<li class="emoji" data-clipboard-text="kiss"><i class="em em-kiss"></i></li>
					<li class="emoji" data-clipboard-text="couplekiss"><i class="em em-couplekiss"></i></li>
					<li class="emoji" data-clipboard-text="kissing"><i class="em em-kissing"></i></li>
					<li class="emoji" data-clipboard-text="kissing_smiling_eyes"><i class="em em-kissing_smiling_eyes"></i></li>
					<li class="emoji" data-clipboard-text="kissing_closed_eyes"><i class="em em-kissing_closed_eyes"></i></li>
					<li class="emoji" data-clipboard-text="man-kiss-man"><i class="em em-man-kiss-man"></i></li>
					<li class="emoji" data-clipboard-text="woman-kiss-woman"><i class="em em-woman-kiss-woman"></i></li>
					 <li class ="emoji"  data-clipboard-text="man-running"><i class="em em-man-running" aria-role="presentation" aria-label=""></i></i>
			<li class="emoji" data-clipboard-text="man-facepalming"><i class="em em-man-facepalming"></i></li>

					<li class="emoji" data-clipboard-text="man-gesturing-ok"><i class="em em-man-gesturing-ok"></i></li>
					
					<li class="emoji" data-clipboard-text="man-cartwheeling"><i class="em em-man-cartwheeling"></i></li>
					
					<li class="emoji" data-clipboard-text="woman-cartwheeling"><i class="em em-woman-cartwheeling"></i></li>
					<li class="emoji" data-clipboard-text="woman-gesturing-ok"><i class="em em-woman-gesturing-ok"></i></li>
		

		<li class="emoji" data-clipboard-text="cucumber"><i class="em em-cucumber"></i></li>
		
		<li class="emoji" data-clipboard-text="hot_peper"><i class="em em-hot_pepper"></i></li>
		<li class="emoji" data-clipboard-text="eggplant"><i class="em em-eggplant"></i></li>
		<li class="emoji" data-clipboard-text="snow_cloud"><i class="em em-snow_cloud"></i></li>

                     
					<li class="emoji" data-clipboard-text="heart_eyes"><i class="em em-heart_eyes"></i></li>
					<li class="emoji" data-clipboard-text="heart"><i class="em em-heart"></i></li>
					<li class="emoji" data-clipboard-text="hearts"><i class="em em-hearts"></i></li>
					<li class="emoji" data-clipboard-text="blue_heart"><i class="em em-blue_heart"></i></li>
					<li class="emoji" data-clipboard-text="broken_heart"><i class="em em-broken_heart"></i></li>
					<li class="emoji" data-clipboard-text="cupid"><i class="em em-cupid"></i></li>
					<li class="emoji" data-clipboard-text="heartbeat"><i class="em em-heartbeat"></i></li>
					 <li class ="emoji"  data-clipboard-text="heavy_heart_exclamation_mark_ornament"><i class="em em-heavy_heart_exclamation_mark_ornament" aria-role="presentation" aria-label=""></i></i>

					<li class="emoji" data-clipboard-text="astonished"><i class="em em-astonished"></i></li>
					<li class="emoji" data-clipboard-text="1234"><i class="em em-1234"></i></li>
					<li class="emoji" data-clipboard-text="8ball"><i class="em em-8ball"></i></li>
					<li class="emoji" data-clipboard-text="a"><i class="em em-a"></i></li>
					<li class="emoji" data-clipboard-text="ab"><i class="em em-ab"></i></li>
					<li class="emoji" data-clipboard-text="abc"><i class="em em-abc"></i></li>
					<li class="emoji" data-clipboard-text="abcd"><i class="em em-abcd"></i></li>
					<li class="emoji" data-clipboard-text="accept"><i class="em em-accept"></i></li>
					<li class="emoji" data-clipboard-text="aerial_tramway"><i class="em em-aerial_tramway"></i></li>
					<li class="emoji" data-clipboard-text="airplane"><i class="em em-airplane"></i></li>
					<li class="emoji" data-clipboard-text="alarm_clock"><i class="em em-alarm_clock"></i></li>
					<li class="emoji" data-clipboard-text="alien"><i class="em em-alien"></i></li>
					<li class="emoji" data-clipboard-text="ambulance"><i class="em em-ambulance"></i></li>
					<li class="emoji" data-clipboard-text="anchor"><i class="em em-anchor"></i></li>
					<li class="emoji" data-clipboard-text="ant"><i class="em em-ant"></i></li>
					<li class="emoji" data-clipboard-text="apple"><i class="em em-apple"></i></li>
					<li class="emoji" data-clipboard-text="aquarius"><i class="em em-aquarius"></i></li>
					<li class="emoji" data-clipboard-text="aries"><i class="em em-aries"></i></li>
					<li class="emoji" data-clipboard-text="arrow_backward"><i class="em em-arrow_backward"></i></li>
					<li class="emoji" data-clipboard-text="arrow_double_down"><i class="em em-arrow_double_down"></i></li>
					<li class="emoji" data-clipboard-text="arrow_double_up"><i class="em em-arrow_double_up"></i></li>
					<li class="emoji" data-clipboard-text="arrow_down"><i class="em em-arrow_down"></i></li>
					<li class="emoji" data-clipboard-text="arrow_down_small"><i class="em em-arrow_down_small"></i></li>
					<li class="emoji" data-clipboard-text="arrow_forward"><i class="em em-arrow_forward"></i></li>
					<li class="emoji" data-clipboard-text="arrow_heading_down"><i class="em em-arrow_heading_down"></i></li>
					<li class="emoji" data-clipboard-text="arrow_heading_up"><i class="em em-arrow_heading_up"></i></li>
					<li class="emoji" data-clipboard-text="arrow_left"><i class="em em-arrow_left"></i></li>
					<li class="emoji" data-clipboard-text="arrow_lower_left"><i class="em em-arrow_lower_left"></i></li>
					<li class="emoji" data-clipboard-text="arrow_lower_right"><i class="em em-arrow_lower_right"></i></li>
					<li class="emoji" data-clipboard-text="arrow_right"><i class="em em-arrow_right"></i></li>
					<li class="emoji" data-clipboard-text="arrow_right_hook"><i class="em em-arrow_right_hook"></i></li>
					<li class="emoji" data-clipboard-text="arrow_up"><i class="em em-arrow_up"></i></li>
					<li class="emoji" data-clipboard-text="arrow_up_down"><i class="em em-arrow_up_down"></i></li>
					<li class="emoji" data-clipboard-text="arrow_up_small"><i class="em em-arrow_up_small"></i></li>
					<li class="emoji" data-clipboard-text="arrow_upper_left"><i class="em em-arrow_upper_left"></i></li>
					<li class="emoji" data-clipboard-text="arrow_upper_right"><i class="em em-arrow_upper_right"></i></li>
					<li class="emoji" data-clipboard-text="arrows_clockwise"><i class="em em-arrows_clockwise"></i></li>
					<li class="emoji" data-clipboard-text="arrows_counterclockwise"><i class="em em-arrows_counterclockwise"></i></li>
					<li class="emoji" data-clipboard-text="art"><i class="em em-art"></i></li>
					<li class="emoji" data-clipboard-text="articulated_lorry"><i class="em em-articulated_lorry"></i></li>
					<li class="emoji" data-clipboard-text="atm"><i class="em em-atm"></i></li>
					<li class="emoji" data-clipboard-text="b"><i class="em em-b"></i></li>
				</ul>
			</div>
      </div>
    </div>
    </div>
</div>


		<!-- smaller screens -->
	
<ul class="list-group visible-xs msg-list">
<? 
if(mysqli_num_rows($chats) > 0):
$ids=array();
foreach($chats as $row): 
    if($row['sender']==$_SESSION['id']){
	$user_id=$row['reciever'];
	}else{
		$user_id=$row['sender'];
    }

	if(!in_array($user_id,$ids)):
$user = $new_user->getUserById($user_id);
$msg_len =15;
?>
<li style="padding:3px; color:grey; !important" class="list-group-item">
<a href="chat.php?uname=<? echo $user['username']; ?>&dp=<? echo $user['dp']; ?>&uid=<? echo $user['id']; ?>&last_active=<? echo $user['last_active']; ?>">
<? echo $user['username']; ?><br>
<img style="width:30px;height:30px" src="<? echo $user['dp']; ?>" /> 
 
<? if($row['type'] == 'image') :?>
<i class="fa fa-file"></i>
<? else:
    if($row['seen'] == 0):
$newCount = $chat->countNewUserChats($_SESSION['id'], $user_id);
    ?>
<strong><span id="<? echo $row['id']; ?>" class="msg-text" style="color:black; font-weight:bold" font-size="10px"><?   echo $chat->limited_word($row['msg'], $msg_len) ?> </span></strong>
<? if($newCount > 0): ?> <sup class=""><span class="badge"><span class="msg-count"  style="color:brown"> <? echo $newCount; ?></span></span></sup>
<? endif; ?>
    <? else: ?>

<strong><span id="<? echo $row['id']; ?>" class="msg-text" style="color:grey; font-weight:bold" font-size="10px"><? echo $chat->limited_word($row['msg'], $msg_len) ?> </span></strong>
<?
endif;
endif; ?>
</a>
<em class="msg-time"><? echo $chat->time_elapsed_string($row['time']); ?>  </em>
</li>
<?
endif;
      $ids[]=$user_id;
endforeach; 
else:
?>
<li> your chats will appear here </li>
<? endif; ?>
</ul>

<!-- end of smaller screens -->


</div>


              </div>

        	


        
    </div>
</div>
        </div>
    


 <script src="/client/static/js/jquery-3.5.1.min.js"> </script>

 <script src="/client/static/js/bootstrap.min.js"> </script>

	<script src="https://use.fontawesome.com/aa95071b26.js"></script>
 <script src="/client/static/js/moment-with-locales.min.js"> </script>
 <script src="/client/static/js/moment-timezone-with-data.min.js"> </script>
<!--
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous" async></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous" ></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data.min.js"></script>
	 -->
	 <script  src="/client/static/js/chats-bs.js"></script>
 <script src="/client/static/js/xchat.js"> </script>





 </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){
	   $("#sidebarCollapse").on("click", function () {
        $("#sidebar").toggleClass("active");
        $(this).toggleClass("active");
    });
});
</script>

		</body>
		</html>