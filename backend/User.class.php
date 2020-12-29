<?php 
 session_start();
class User{
   # ========================================================================#
   # Create and handle users
   # ========================================================================#


    
    function create(){
     
    include_once("DB.class.php");
    $con = new DB();
    $con = $con->open();

  
//upload user profile picture
				if (!empty($_FILES)) {
$target_dir = "../uploads/thumb/photo/";
$target_file = $target_dir .rand(). basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));



// Check if file already exists
if (file_exists($target_file)) {
  
$response[] = array("error" => "File already exist,please rename file.");
    return json_encode($response);
    exit();
}

// Check file size
if ($_FILES["file"]["size"] > 3000000) {
$response[] = array("error" => "Sorry, your file is too large.");
    return json_encode($response);
    exit();
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
) {
  
$response[] = array('error' => "Sorry, only JPG, JPEG, PNG  files are allowed.");
    return json_encode($response);
    exit();
}

  if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

    
    // *** Include_once the class for image resizing
    include_once("resize-class.php");

    // *** 1) Initialise / load image
    $resizeObj = new resize($target_file);

    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage(240, 320, 'crop');

    // *** 3) Save image
    $resized_img = $target_dir .rand().'resized'.rand().'.jpg';
    $resizeObj -> saveImage($resized_img, 1000);

// remove original file as we no longer need it
unlink($target_file);

  $username = mysqli_escape_string($con,$_POST['username']);   
   $country = mysqli_escape_string($con,$_POST['country']);  
    $password = mysqli_escape_string($con,$_POST['password1']);
    $password2 = mysqli_escape_string($con,$_POST['password2']);

// check if username is taken
$query=mysqli_query($con,"select * from user where username='$username'")or die(mysqli_error());
			$count=mysqli_num_rows($query);		
			if ($count>0)
			{	
        $response = array("error" => "username already taken.please choose another");
        return json_encode($response); 
                exit();  
            }

// check if password matches
    if($password != $password2){
$response[] = array("error" => "Passwords do not match");
    return json_encode($response);    
    exit();
}
//no errors, proceed to create user
$password = password_hash($password, PASSWORD_BCRYPT, array('cost'=>12));

mysqli_query($con,"INSERT INTO user(username,country,password) 
VALUES('$username','$country','$password')")or die(mysqli_error($con));

// set profile picture
 $query="update user set dp ='$resized_img' where username = '$username'";
mysqli_query($con, $query) or die(mysqli_error($con));
$con->close();
  } else {
    
$response[] = array('error' => "Sorry, there was an error uploading your file.");
    return json_encode($response);
    exit();
  }

}
 else{
  
$response[] = array('error' => "Please select a file.");
    return json_encode($response);
    exit();
}

// everything is good
$response = 'success';
    return $response;
    }
  
function getUsers($uid){
    //get all users
include_once('DB.class.php');
$con = new DB();
$con = $con->open();
    $query = mysqli_query($con,"select * from user where id <> '$uid' ") or die(mysqli_error($con));
          
         $con->close();
          return $query;
}

function getUserById($uid){
//get a user by their id
include_once('DB.class.php');
$con = new DB();
$con = $con->open();
    $query = mysqli_query($con,"select * from user where id= '$uid' ") or die(mysqli_error($con));
          $row = mysqli_fetch_assoc($query);
         $con->close();
          return $row;
}



    function login(){

    include_once("DB.class.php");
    $con = new DB();
    $con = $con->open();
    
   $username = mysqli_escape_string($con,$_POST['username']);     
    $password = mysqli_escape_string($con,$_POST['password']);

$query=mysqli_query($con,"select * from user where username='$username'")or die(mysqli_error($con));

    $con->close();
    $counter=mysqli_num_rows($query);
     

    //check if user exists
 	if ($counter == 0) 
		  {	
        
$response = array("error" => "invalid username");
    return json_encode($response); 
    exit();
          } 
          else {
              $row = mysqli_fetch_assoc($query);
    if(!password_verify($password,$row['password'])){
$response = array("error" => "wrong password");
    return json_encode($response); 
          exit();
    }
            
              // return details of the user
             
              $_SESSION['id'] = $row['id'];
              $_SESSION['dp'] = $row['dp'];
              $_SESSION['username'] = $row['username'];
              $_SESSION['last_active'] = $row['last_active'];
              $response = 'success';
              return $response;
    }
    }


function isLoggedIn(){
    
//check if user is logged in
if(isset($_SESSION['id']))
    return true;
    else
    return false;
}


function logout(){
//destroy all sessions
    session_start();
    session_destroy();
}

}
?>