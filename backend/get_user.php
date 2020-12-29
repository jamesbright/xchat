 <?php
include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$user_id = isset($_GET['user_id']) ? mysqli_escape_string($con,$_GET['user_id']) : null;
$query = mysqli_query($con, "select * from user where id = '$user_id'") or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
echo json_encode($data);
    ?>