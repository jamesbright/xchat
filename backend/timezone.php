<? 
if(!isset($_SESSION['id']))
session_start();

$_SESSION['time'] = $_GET['time'];
if(isset($_SESSION['time'])){
$timezone = $_SESSION['time'];
date_default_timezone_set($timezone);
}
?>