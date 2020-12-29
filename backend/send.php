<?php
session_start();
include_once('DB.class.php');
$con = new DB();
$con = $con->open();
$sid = isset($_POST['sid']) ? mysqli_escape_string($con,$_POST['sid']) : null;
$rid = isset($_POST['rid']) ? mysqli_escape_string($con,$_POST['rid']) : null;
$msg = isset($_POST['msg']) ? mysqli_escape_string($con,$_POST['msg']) : null;
$msg = trim($msg);

include('default_timezone.php');
		
$now = new DateTime();
$date =  $now->format('Y-m-d H:i:s');

$query=mysqli_query($con,"insert into chats(sender,reciever,msg,type,time) values('$sid','$rid','$msg','text','$date')")or die(mysqli_error($con));

$last_id = mysqli_insert_id($con);
$query = mysqli_query($con,"select * from chats where id='$last_id'") or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
echo  json_encode($data);

?>