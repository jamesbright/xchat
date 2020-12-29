<?php 
class DB{

function open(){
    $con = mysqli_connect("localhost","root","","x_chat");

// Check connection
if (mysqli_connect_errno())
  {
  echo "database connection failed: " . mysqli_connect_error();
  }

  return $con;
}


function close(){
    if($con){
        mysqli_close($con);
    }
}
}

?>