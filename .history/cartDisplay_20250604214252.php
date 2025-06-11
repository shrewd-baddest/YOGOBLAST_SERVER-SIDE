<?php
include('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 

// $userQuery = "SELECT user_id FROM sessions WHERE id=14  LIMIT 1";
// $userResult = mysqli_query($conn, $userQuery);

// if ($userResult && mysqli_num_rows($userResult) > 0) {
//     $userInfo = mysqli_fetch_assoc($userResult);
//     $userData=$userInfo["user_id"];
     
//   } else {
//     echo json_encode(["status" => "error", "message" => "User not found in session."]);
//     exit;
// } 
if(isset($_SESSION["userId"])) {
   $userData=$_SESSION["userId"];
   // echo $userData;
} else {
    echo json_encode(["status" => "error", "message" => "Please log in to continue with cart."]);
    exit;
}


$sql="SELECT products.products_id,products.image_url,products.weight_ml,products.price,shoping_cart.quantity
FROM shoping_cart INNER JOIN products ON shoping_cart.product_id=products.products_id
WHERE shoping_cart.user_id=?";

$stmt=mysqli_prepare($conn,$sql);
 mysqli_stmt_bind_param($stmt,'i',$userData);
 mysqli_stmt_execute($stmt);
 // Get the result
 $rezults = mysqli_stmt_get_result($stmt);
$store=[];
 if(mysqli_num_rows($rezults) > 0){
   while($rows=mysqli_fetch_assoc($rezults)){
      $store[]=$rows;
   }
   
    echo json_encode($store);
 }
 else{
echo 'no search results';
 }

mysqli_close($conn);

?>