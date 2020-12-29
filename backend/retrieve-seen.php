<?

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$cid = isset($_POST['cid']) ? $_POST['cid'] : null;
$query=mysqli_query($con,"select * from chats where id = '$cid'")or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
echo json_encode($data);

?>