<?php

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$id = $_GET['user_id'];

include('default_timezone.php');

$query = mysqli_query($con, "UPDATE user SET last_active=NOW() WHERE id='$id'");