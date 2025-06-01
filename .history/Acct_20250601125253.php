<?php
include('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 


if(isset($_SESSION["userId"])) {
    $user_id =$_SESSION["userId"];   
} else {
    echo json_encode(["status" => "error", "message" => "Please log in to continue with cart."]);
    exit;
}

$updates= json_decode(file_get_contents("php://input"), true);
if($updates["msg"]=='logOut'){
    echo json_encode(["status"=>"success"]);
     session_unset();     
    session_destroy(); 
}

$sql="SELECT * from person
WHERE ID=?";
 $stmt=mysqli_prepare($conn,$sql);
 mysqli_stmt_bind_param($stmt,'i',$user_id);
 mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
echo json_encode($user);
mysqli_close($conn)
?>