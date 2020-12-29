
<?php
if ($_FILES['file']) {
$target_dir = "../uploads/chats/";
$target_file = $target_dir .rand(). basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image


 

  $check = getimagesize($_FILES["file"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    
$response[] = array("error" => "Only image files accepted.", "uploadOk" => 0);
    echo json_encode($response);
    $uploadOk = 0;
    exit();
  }


// Check if file already exists
if (file_exists($target_file)) {
  
$response[] = array("error" => "File already exist,please rename file.", "uploadOk" => 0);
    echo json_encode($response);
  $uploadOk = 0;
  exit();
}

// Check file size
if ($_FILES["file"]["size"] > 3000000) {
$response[] = array("error" => "Sorry, your file is too large.", "uploadOk" => 0);
    echo json_encode($response);
  $uploadOk = 0;
  exit();
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
) {
  
$response[] = array('error' => "Sorry, only images are allowed", "uploadOk" => 0);
    echo json_encode($response);
  $uploadOk = 0;
  exit();
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 1) {
  
//$response[] = array('error' => "Sorry, your file was not uploaded", "uploadOk" => 0);
    //echo json_encode($response);
     //header("HTTP/1.1 400 Invalid extension,Bad request");
     //exit();
// if everything is ok, try to upload file
 

  //check if upload limit is exceeded
  
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

    
    // *** Include the class
    include_once("resize-class.php");

    // *** 1) Initialise / load image
    $resizeObj = new resize($target_file);

    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage(240, 320, 'crop');

    // *** 3) Save image
    $resized_img = $target_dir .rand().'resized'.rand().'.jpg';
    $resizeObj -> saveImage($resized_img, 1000);

unlink($target_file);

$img = "<img src=".$resized_img.">";

		

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$sid = isset($_POST['sid']) ? mysqli_escape_string($con,$_POST['sid']) : null;
$rid = isset($_POST['rid']) ? mysqli_escape_string($con,$_POST['rid']) : null;

include('default_timezone.php');
		
$now = new DateTime();
$date =  $now->format('Y-m-d H:i:s');

$query=mysqli_query($con,"insert into chats(sender,reciever,msg,file_link,type,time) values('$sid','$rid','$img', '$resized_img','image','$date')")or die(mysqli_error($con));

$last_id = mysqli_insert_id($con);
$query = mysqli_query($con,"select * from chats where id='$last_id'") or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
$data['success'] = "uploaded successfully";
$data['uploadOk'] = 1;
echo json_encode($data); 
  } else {
    
$response[] = array('error' => "Sorry, there was an error uploading your file.", "uploadOk" => 0);
    echo json_encode($response);
  }

}  
} else{
  
$response[] = array('error' => "Please select a file.");
    echo json_encode($response);
}
 ?>