<?php
session_start();
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$cart= json_decode(file_get_contents("php://input"),true);
 
 
include('connect.php');

$user= $_SESSION['user_Id'];
if($user && empty($cart)){
$query="SELECT SUM(quantity) AS total-quantity
from shoping_cart
WHERE user_id=?";
$quant=mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($quant,'s',$user);
mysqli_stmt_execute($quant);
$amount=mysqli_stmt_get_result($quant);
 
 $quantity=mysqli_fetch_assoc($amount) ;
 
echo json_encode([$quantity]);

exit;
}


$product_id=$cart['productId'];
$quantity=$cart['Quantity'];
 $sql = "INSERT INTO shoping_cart 
(product_id, user_id, quantity)
 VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt,"isi", $product_id, $user, $quantity);

try {
    mysqli_stmt_execute($stmt);
    echo json_encode(["Item added to cart"]);
}
catch(mysqli_sql_exception){
echo"you can not reach the page";
}
 
 


mysqli_close($conn);



?>