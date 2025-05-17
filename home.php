
 <?php


header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
$name='fresha';
include('connect.php');
$sql= "
SELECT 
products. products_id,
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
   
    echo json_encode($store);
 }

mysqli_close($conn);
?>
