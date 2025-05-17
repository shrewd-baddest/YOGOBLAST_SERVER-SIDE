<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$category= json_decode(file_get_contents("php://input"),true);
if ($category=== null) {
       echo " JSON data not received.";
       exit; 
}
 
include('connect.php');
$name=$category['categ'];
$sql="select
products.products_id,
products.products_name,
products.weight_ml,
products.image_url,
products.price FROM
products
INNER JOIN category
ON products.category_id=category.category_id
WHERE
category_name=?";

$stmt=mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"s",$name);
mysqli_stmt_execute($stmt);
 $results=mysqli_stmt_get_result($stmt);
 
 if(!$results){
    die("failed to obatin to resuts". mysqli_error($conn));
}
$store=[];
 if(mysqli_num_rows($results) > 0){
   while($rows=mysqli_fetch_assoc($results)){
      $store[]=$rows;
   }
   
    echo json_encode($store);
 }

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>