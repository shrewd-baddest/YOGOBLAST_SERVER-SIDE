<?php
$server_name="localhost";
$user_name='root';
$password='';
$db_name='login';

try{
    $conn=mysqli_connect($server_name,$user_name,$password,$db_name);
}
catch(mysqli_sql_exception){
    echo"not connected";
}
 

 
?>