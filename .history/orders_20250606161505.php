<?php
include('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 
$order_details= json_decode(file_get_contents("php://input"), true);
 

$stmt = $conn->prepare("SELECT * FROM payments WHERE phone = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

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
