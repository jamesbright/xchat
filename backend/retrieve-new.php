<?php

include_once('DB.class.php');
$con = new DB();
$con = $con->open();
include('default_timezone.php');

$sid = isset($_POST['sid']) ? $_POST['sid'] : null;
$rid = isset($_POST['rid']) ? $_POST['rid'] : null;
$query=mysqli_query($con,"select * from chats where (sender='$sid' and  reciever='$rid' ) and seen=0 order by id desc limit 1")or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
echo json_encode($data);

?>