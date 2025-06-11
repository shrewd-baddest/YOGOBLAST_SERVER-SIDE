<?php
include('session_config.php');

header("Content-Type: application/json");
$userData=$_SESSION["userId"];
include('connect.php'); 
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
$sql="INSERT INTO orders (user_id,phoneNumber,receipt,total_price,order_date,statuz) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn,$sql);
 
 mysqli_stmt_bind_param($stmt, 'issdss', $user_id, $phone, $receipt, $amount, $date, $resultCode);
 mysqli_stmt_execute($stmt);
}


  
?>