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
 $row = mysqli_fetch_assoc($result);

if($row['statuz']=='success'){
    $sql2="INSERT INTO order-items(oder_id, product_id,price, quantity)";
    $stmt2 = mysqli_prepare($conn, $sql2);
    if (!$stmt2) {
        die("Prepare failed: " . mysqli_error($conn));
    }
    $product_id = $order_details['product_id'] ?? null;
    $quantity = $order_details['quantity'] ?? null;
    mysqli_stmt_bind_param($stmt2, 'iiii', $row['id'], $product_id,$row['total_price'], $quantity); 
    mysqli_stmt_execute($stmt2);
    if (mysqli_stmt_affected_rows($stmt2) > 0) {
        echo json_encode(["status" => "success", "message" => "Order items added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add order items"]);
    }
 } else {
    echo json_encode(["status" => "pending", "message" => "No payment found"]);
}
?>