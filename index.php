<?php 
session_abort();
session_start();
if(!isset($_SESSION['id'])){
$location = 'client/login.html';
header("location:$location");
exit();
}

//get user chats
include('backend/User.class.php');
$new_user = new User();
$all_users = $new_user->getUsers($_SESSION['id']);

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Xchat</title>
        
        <link href="/client/static/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="/client/static/css/mdb.min.css" rel="stylesheet">
        <link href="/client/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/client/static/css/custom.css" rel="stylesheet">
        
	<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
        <link href="/client/static/css/chats-list.css" rel="stylesheet">


    </head>

    <body>

   <div class="marvel-device nexus5">
  
    <div class="screen">
      <div class="screen-container">
       
	  <div class="chat">
          <div class="chat-container">
		  
            <div class="user-bar">
         
              <div class="avatar">
			<a href="<?php echo $_SESSION['dp']; ?>">
				<img src="<?php echo $_SESSION['dp']; ?>" alt="Avatar">
				</a>
              </div>
              <div class="name">
					<span style="color:white" ><?php echo $_SESSION['username']; ?></span>
				
				      <a href="backend/process.php?logout" class="btn btn-link"><i class="fa fa-logout"></i>logout</a>

			
              </div>
            
             <div class="actions more">
               
			 <a href="backend/chats-list.php" class="btn"><i class="fa fa-comments"></i>Chats</a>
     
	
					<span class="pull-right">
              <a class="navbar-brand xchat-logo" style="background:red;color:white" href="/">Xchat</a>
					</span>
              </div>
            </div>

     
      <div class="block">
        <div class="row block-item text-center disclaimer">

          <h4><strong></strong>Made with love for the season</strong></h4>
        </div>
</div>
    	<input type="hidden" name="sender" id="sid" value="<? echo $_SESSION['id']; ?>">				
  <div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3" >

   <? if(mysqli_num_rows($all_users) > 0): ?>

<ul class="list-group">
<? foreach($all_users as $user):?> 

<li class="list-group-item hidden-xs">
    <a href="backend/chats-list.php?uname=<? echo $user['username']; ?>&dp=<? echo $user['dp']; ?>&uid=<? echo $user['id']; ?>&last_active=<? echo $user['last_active']; ?>">
<? echo $user['username'];  ?><br>
<img style="width:30px;height:30px" src="<? echo $user['dp']; ?>" /> 
  
    </a>
  <img class="" src="https://lipis.github.io/flag-icon-css/flags/4x3/<? echo  strtolower($user['country']); ?>.svg" style="vertical-align: middle;width:15px;height:15px" title="<? echo  $user['country']; ?> " alt="<? echo $user['country']; ?>">

</li>

<li class="list-group-item visible-xs">
    <a href="backend/chat.php?uname=<? echo $user['username']; ?>&dp=<? echo $user['dp']; ?>&uid=<? echo $user['id']; ?>&last_active=<? echo $user['last_active']; ?>">
<? echo $user['username'];  ?><br >
<img style="width:30px;height:30px" src="<? echo $user['dp']; ?>" /> 
    </a>
     <img class="" src="https://lipis.github.io/flag-icon-css/flags/4x3/<? echo  strtolower($user['country']); ?>.svg" style="vertical-align: middle;width:15px;height:15px" title="<? echo  $user['country']; ?> " alt="<? echo $user['country']; ?>">

</li>
<? endforeach; ?>
    </ul>
<? endif; ?>
</div>
                    </div>
 



 <script  src="client/static/js/jquery-3.5.1.min.js"></script>
     

	<script src="https://use.fontawesome.com/aa95071b26.js"></script>
     <script src="/client/static/js/moment-with-locales.min.js"> </script>
 <script src="/client/static/js/moment-timezone-with-data.min.js"> </script>

   <script  src="client/static/js/xchat-index.js"></script>
     
        </body>
</html>