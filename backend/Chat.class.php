<?php
class Chats{
    
function getChats($uid){
//get user chats
include_once('DB.class.php');
$con = new DB();
$con = $con->open();
    $query = mysqli_query($con,"
select * from `chats` where reciever='$uid' or sender='$uid' order by id desc
") or die(mysqli_error($con));
$con->close();
return $query;
}

function countNewChats($rid){
//count number of  new chats
    include_once('DB.class.php');
$con = new DB();
$con = $con->open();

    $query = mysqli_query($con, "select * from chats where reciever='$rid' and seen=0 ") or die(mysqli_error($con));
    $row = mysqli_fetch_assoc($query);
    $con->close();
    return mysqli_num_rows($query);
}

//count new chats
function countNewUserChats($rid,$sid){
      include_once('DB.class.php');
$con = new DB();
$con = $con->open();

    $query = mysqli_query($con, "select * from chats where reciever='$rid' and sender='$sid' and seen=0 ") or die(mysqli_error($con));
    $row = mysqli_fetch_assoc($query);
    $con->close();
    return mysqli_num_rows($query);
}



function setSeen($sid,$rid){
//set a message as seen
include_once('DB.class.php');
$con = new DB();
$con = $con->open();
    $query = mysqli_query($con, "update chats set seen=1 where reciever='$rid' and sender='$sid' and seen=0") or die(mysqli_error($con));
$con->close();

}

function time_elapsed_string($datetime, $full = false) {
// formats time in human form
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


function limited_word($text, $limit){
// limit number of words
if(strlen(implode(' ', array_slice(explode(' ', $text), 0, strlen($text))))<=$limit)
  {
    $newWord = $text;
  }
  else
  {
      
$split = implode(' ', array_slice(explode(' ', $text), 0, $limit));
   $newWord  = $split.'...';
  }

 return $newWord;
}

function send(){ 
//send message to user

include_once('DB.class.php');
$con = new DB();
$con = $con->open();

$sid = isset($_POST['sid']) ? (int)mysqli_escape_string($con,$_POST['sid']) : null;
$rid = isset($_POST['rid']) ? (int)mysqli_escape_string($con,$_POST['rid']) : null;
$msg = isset($_POST['msg']) ? mysqli_escape_string($con,$_POST['msg']) : null;
$msg = trim($msg);
$query=mysqli_query($con,"insert into chats(sender,reciever,msg,type) values($sid,$rid,'$msg','text')")or die(mysqli_error($con));

$last_id = mysqli_insert_id($con);
$query = mysqli_query($con,"select * from chats where id='$last_id'") or die(mysqli_error($con));
$con->close();
$data = array();
foreach($query as $result){
$data[] = $result;
}
return $data;
}

}

?>