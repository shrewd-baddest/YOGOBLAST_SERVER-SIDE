<?php
include('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 
$order_details= json_decode(file_get_contents("php://input"), true);
if ($order_details === null) {
    echo json_encode(["status" => "error", "message" => "JSON data not received."]);
    exit;
}

 $sql= "SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
$user_id = $_SESSION['userId'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    die("Get result failed: " . mysqli_error($conn));
}
 $row = mysqli_fetch_assoc($result)

if ($row['statuz']=='success') {
    
} else {
    echo json_encode(["status" => "pending", "message" => "No payment found"]);
}
?>