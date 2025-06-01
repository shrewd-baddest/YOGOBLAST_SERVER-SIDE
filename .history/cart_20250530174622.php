<?php
include ('session_config.php');
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
print_r
if(isset($_SESSION["userId"])) {
    $user_id =$_SESSION["userId"];
    // echo  $user_id; 
} else {
    echo json_encode(["status" => "error", "message" => "Please log in to continue with cart."]);
    exit;
}

// Step 3: Get product details from frontend
$product_id = $cart['productId'] ?? null;
$quantity = $cart['Quantity'] ?? null;

// Step 4: If both values are empty, return total quantity
if(empty($product_id) && empty($quantity)) {
    $query = "SELECT SUM(quantity) as total_quantity FROM shoping_cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    echo json_encode($data);
    exit;
}

// Step 5: Add to cart
$sql = "INSERT INTO shoping_cart (product_id, user_id, quantity) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iii", $product_id, $user_id, $quantity);

try {
    mysqli_stmt_execute($stmt);
    echo json_encode(["status" => "success", "message" => "Item added to cart"]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(["status" => "error", "message" => "Could not add item to cart"]);
}

mysqli_close($conn);
?>
