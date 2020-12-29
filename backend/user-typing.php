<?

include_once('DB.class.php');
$con = new DB();
$con = $con->open(); 
$sid = isset($_POST['sid']) ? $_POST['sid'] : null;
$rid = isset($_POST['rid']) ? $_POST['rid'] : null;

$query=mysqli_query($con,"select * from typing_indicator where (sender='$sid' and  reciever='$rid') or (sender='$rid' and reciever='$sid')")or die(mysqli_error($con));
if(mysqli_num_rows($query)> 0){
$row = mysqli_fetch_assoc($query);
$row_id = $row['id'];
$query=mysqli_query($con,"update typing_indicator set sender='$sid', reciever='$rid', typing = 1 where id='$row_id'")or die(mysqli_error($con));

}else{
$query=mysqli_query($con,"insert into typing_indicator(sender,reciever,typing) values('$sid','$rid',1)")or die(mysqli_error($con));
$con->close();
}

?>