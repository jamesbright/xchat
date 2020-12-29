<?php
if(!isset($_SESSION['id']))
 session_start();

if(isset($_SESSION['time'])){
$timezone = $_SESSION['time'];
date_default_timezone_set($timezone);
}

?>