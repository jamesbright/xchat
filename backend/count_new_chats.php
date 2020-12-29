<?php
$rid = $_GET['user_id'];

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

    $query = mysqli_query($con, "select * from chats where reciever='$rid' and seen=0 ") or die(mysqli_error($con));
    $row = mysqli_fetch_assoc($query);
$con->close();
    echo mysqli_num_rows($query);

    ?>