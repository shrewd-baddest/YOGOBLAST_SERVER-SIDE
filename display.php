<?php


header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$p_id = json_decode(file_get_contents("php://input"),true);
if ($p_id === null) {
       echo " JSON data not received.";
       exit; 
}
 
include('connect.php');

 
$pro_id=$p_id['product_id'];
$sql="
select image_url,price,products_name,weight_ml,  description 
 from products
 where products_id=?";

 $outcome=mysqli_prepare($conn,$sql);

 if (!$outcome) {
       die("Prepare failed: " . mysqli_error($conn));
   }

 mysqli_stmt_bind_param($outcome,'i',$pro_id);
 mysqli_stmt_execute($outcome);
 // Get the result
 $rezults = mysqli_stmt_get_result($outcome);

 if (!$rezults) {
       die("Get result failed: " . mysqli_error($conn));
   }

$store=[];
 if(mysqli_num_rows($rezults)>0){
while($rows=mysqli_fetch_assoc($rezults))
$store[]=$rows;
 }
echo json_encode($store);
mysqli_close($conn);
?>