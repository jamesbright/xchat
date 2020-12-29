 <?php
 
 
include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$sid = isset($_POST['sid']) ? mysqli_escape_string($con,$_POST['sid']) : null;
$rid = isset($_POST['rid']) ? mysqli_escape_string($con,$_POST['rid']) : null;
$cid = isset($_POST['cid']) ? mysqli_escape_string($con,$_POST['cid']) : null;
$query = mysqli_query($con, "update chats set seen=1 where reciever='$rid' and sender='$sid' and seen=0") or die(mysqli_error($con));
$query = mysqli_query($con,"select * from chats where id='$cid'") or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
echo json_encode($data);
    ?>