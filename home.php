<?php
header("Content-Type: application/json");

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
$name='fresh';
include('connect.php');
$sql= "
SELECT 
products.products_id,
 products.image_url,
  products.products_name,
  products.price,
  products.weight_ml
FROM products
INNER JOIN category 
  ON products.category_id = category.category_id
WHERE category.category_name =?";

 $outcome=mysqli_prepare($conn,$sql);
 mysqli_stmt_bind_param($outcome,'s',$name);
 mysqli_stmt_execute($outcome);
 // Get the result
 $rezults = mysqli_stmt_get_result($outcome);
$store=[];
 if(mysqli_num_rows($rezults) > 0){
   while($rows=mysqli_fetch_assoc($rezults)){
      $store[]=$rows;
   }
   
  }

$choice="SELECT * FROM products
LIMIT 10";
$choices=mysqli_prepare($conn,$choice);
mysqli_stmt_execute($choices);
 $results = mysqli_stmt_get_result($choices);
$customersChoice=[];
 if(mysqli_num_rows($results) > 0){
   while($options=mysqli_fetch_assoc($results)){
      $customersChoice[]=$options;
   }
   
  }


  echo json_encode([$store,$customersChoice]);

mysqli_close($conn);
?>
