<?php

include_once('DB.class.php');
$con = new DB();
$con = $con->open();
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

include('default_timezone.php');

$query=mysqli_query($con,"select * from chats where reciever='$user_id' or sender = '$user_id' order by id desc")or die(mysqli_error($con));
$data = array();
foreach($query as $result){
$data[] = $result;
}
echo json_encode($data);

?>