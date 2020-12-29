 <?
 
include_once('DB.class.php');
$con = new DB();
$con = $con->open();

 $sid = isset($_GET['sid']) ? $_GET['sid'] : null;
$rid = isset($_GET['rid']) ? $_GET['rid'] : null;
$query = mysqli_query($con, "select * from chats where reciever='$rid' and sender='$sid' and seen=0 ") or die(mysqli_error($con));
$con->close();
$count = mysqli_num_rows($query);
    echo json_encode($count);

    ?>