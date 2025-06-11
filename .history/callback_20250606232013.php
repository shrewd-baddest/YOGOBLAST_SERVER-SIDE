<?php
include('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");
include('connect.php'); 
$user_id=$_SESSION["userId"];
$data = file_get_contents('php://input');
file_put_contents('stk_callback_log.json', $data); 

$json = json_decode($data, true);

if (!$json || !isset($json['Body']['stkCallback'])) {
    // handle invalid callback
    exit;
}

$resultCode = $json['Body']['stkCallback']['ResultCode'];
$resultDesc = $json['Body']['stkCallback']['ResultDesc'];

if ($resultCode == 0) {
    $metadata = $json['Body']['stkCallback']['CallbackMetadata']['Item'];
    $amount = $receipt = $date = $phone = null;
    foreach ($metadata as $item) {
        if ($item['Name'] == 'Amount') $amount = $item['Value'];
        if ($item['Name'] == 'MpesaReceiptNumber') $receipt = $item['Value'];
        if ($item['Name'] == 'TransactionDate') $date = $item['Value'];
        if ($item['Name'] == 'PhoneNumber') $phone = $item['Value'];
    }
$checkoutID=$json['Body']['stkCallback']['CheckoutRequestID'];
$transacts="SELECT * FROM mpesa_request WHERE user_id=?";
$stm4=mysqli_prepare($conn,$transacts);
mysqli_stmt_bond_params($stm4,'s',$checkoutID);
mysqli_stmt_execute($stm4);
$results=mysqli_stmt_get_result($stm4);
$rows=mysqli_

$sql="INSERT INTO orders (user_id,phoneNumber,receipt,total_price,order_date,statuz) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn,$sql);
 
 mysqli_stmt_bind_param($stmt, 'issdsi', $user_id, $phone, $receipt, $amount, $date, $resultCode);
 mysqli_stmt_execute($stmt);
}


  
?>