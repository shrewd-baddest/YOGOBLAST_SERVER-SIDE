<?php
include('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 

$updates= json_decode(file_get_contents("php://input"), true);
 
if(isset($_SESSION["userId"])) {
    $user_id =$_SESSION["userId"];
    // echo  $user_id;
   
} else {
    echo json_encode(["status" => "error", "message" => "Please log in to continue with cart."]);
    exit;
}
$sql="SELECT * from person
WHERE ID=?"
 mysqli_prepa

?>