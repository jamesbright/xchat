<?php

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$id = $_GET['user_id'];
include('default_timezone.php');

$query = mysqli_query($con,"SELECT * FROM user WHERE id ='$id' and last_active <= (NOW() - INTERVAL 20 SECOND)"); 

if(mysqli_num_rows($query) > 0){
$con->close();
$data = array();
$data['status'] ='offline';
echo json_encode($data);
}
else {
$data = array();
$data['status'] = 'online';
 echo json_encode($data);
}
?>
