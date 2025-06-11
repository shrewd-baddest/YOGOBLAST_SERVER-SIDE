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

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => $row['status'],
        "amount" => $row['amount'],
        "receipt" => $row['receipt'],
        "date" => $row['date']
    ]);
} else {
    echo json_encode(["status" => "pending", "message" => "No payment found"]);
}
