<?

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$sid = isset($_POST['sid']) ? $_POST['sid'] : null;
$rid = isset($_POST['rid']) ? $_POST['rid'] : null;
$query=mysqli_query($con,"update typing_indicator set typing = 0 where (sender='$sid' and reciever='$rid')")or die(mysqli_error($con));
$con->close();
?>