<?php
$server_name="localhost";
$user_name='root';
$password='';
$db_name=" yogo_blast ";

try{
    $conn=mysqli_connect($server_name,$user_name,$password,$db_name);
     echo "connected successfullly";
}
catch(mysqli_sql_exception){
    echo"not connected ";
}
 

 
?>