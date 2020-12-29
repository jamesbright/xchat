<?php  
if(isset($_GET['login_check'])){
    include("User.class.php");
    $user = new User();
    $loginStatus = $user->isLoggedIn();
    if($loginStatus == true)
    echo 'logged';
    else
    echo 'not';
}

if(isset($_POST['signup'])){
    include("User.class.php");
    $user = new User();
    $status = $user->create();
    if($status == 'success'){
        $location = "/client/login.html";
          echo "<script type='text/javascript'>
          alert('successfully registered, you can now log in');
          document.location='$location'</script>";
  
        exit();
    }else{
        echo $status;
    }
}


if(isset($_POST['login'])){
    include("User.class.php");
    $user = new User();
    $data = $user->login();
    if($data == 'success'){
        $location = "/index.php";
          echo "<script type='text/javascript'>
          document.location='$location'</script>";
    }else{
       echo $data; 
    }
}


if(isset($_GET['logout'])){
    include("User.class.php");
    $user = new User();
    $user->logout();
    $location = "/index.php";
          echo "<script type='text/javascript'>
          document.location='$location'</script>";

}

if(isset($_POST['send'])){
    include_once('Chat.class.php');
    $chat =  new Chats();
    $data = $chat->send();
    echo json_encode($data);
}
?>