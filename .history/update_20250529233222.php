<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

$updates = json_decode(file_get_contents("php://input"),true);
if ($updates === null) {
    echo " JSON data not received.";
    exit;
}

include('connect.php');

if (!isset($_SESSION["userId"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$quantity = $updates['deleteQuantity'];
$product_id = $updates['deleteId'];
// $user_id = $_SESSION["userId"];

if ($quantity > 1) {
    $sql = "UPDATE shoping_cart SET quantity=? WHERE product_id=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $quantity, $product_id, $user_id);
} else {
    $sql = "DELETE FROM shoping_cart WHERE product_id=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $product_id, $user_id);
}

mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) { 
    echo json_encode(["status" => "success", "message" => "you've successfully updated the cart."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update the cart."]);
}
 mysqli_close($conn);
?>