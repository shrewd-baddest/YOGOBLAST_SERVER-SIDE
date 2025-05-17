<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$search= json_decode(file_get_contents("php://input"),true);
if ($search === null) {
       echo " JSON data not received.";
       exit; 
}
 
include('connect.php');
 $name=$search['search'];
  
 $sql="select 
 products_id,
 products_name,
    weight_ml,
    price,
    image_url
    FROM products
    where products_name LIKE ? OR
    description LIKE ?
 
 ";
$like="%$name%";

 $stmt=mysqli_prepare($conn,$sql);

 mysqli_stmt_bind_param($stmt,"ss",$like,$like);
 mysqli_stmt_execute($stmt);
 $results=mysqli_stmt_get_result($stmt);
 if(!$results){
    die("Get result failed: " . mysqli_error($conn));
}
 $store=[];
 if(mysqli_num_rows($results)>0){
while($rows=mysqli_fetch_assoc($results)){
$store[]=$rows;
}

echo json_encode($store);
 }
 mysqli_stmt_close($stmt);
mysqli_close($conn);
?>