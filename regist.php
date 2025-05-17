<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);
if ($data ===null) {
    echo "Invalid JSON data received.";
    exit;
}
include('connect.php');

 $ID=$data['ID'];
$fname = $data['fName'];
 $pass  =$data['pCode'];
$passcode=password_hash($pass,PASSWORD_DEFAULT);
 $sName=$data['sName'];
$email = $data['email'];
$sql="INSERT INTO person(ID,firstName,secondName,passcode,email)
VALUES('$ID','$fname','$sName','$passcode','$email')";
try{
mysqli_query($conn,$sql);
}
catch(mysqli_sql_exception){
echo"you can not reach the page";
}
 
echo"Received: Name = {$fname}, Email ={$email} ";


mysqli_close($conn);

?>