<?php
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '0');
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 

$cart = json_decode(file_get_contents("php://input"), true);


//Step 1: Fetch user from session table
// $userQuery = "SELECT user_id FROM sessions WHERE id=14  LIMIT 1";
// $userResult = mysqli_query($conn, $userQuery);

// if ($userResult && mysqli_num_rows($userResult) > 0) {
//     $userData = mysqli_fetch_assoc($userResult);
//    $_SESSION["userId"]= $userData["user_id"];

//     // Set cookie correctly: name = 'userId', value = $userId
//     // setcookie("userId", $userId, time() + (60 * 30), "/", "", false, true); // 30-minute expiry
// } else {
//     echo json_encode(["status" => "error", "message" => "User not found in session."]);
//     exit;
// }

// Step 2: Read userId from cookie
// echo $_COOKIE["userId"];
if(isset($_SESSION["userId"])) {
    $user_id =$_SESSION["userId"];
    echo  $user_id;
   
} else {
    echo json_encode(["status" => "error", "message" => "Please log in to continue with cart."]);
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