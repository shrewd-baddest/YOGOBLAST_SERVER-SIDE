<?php
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

include('connect.php'); 
$data = file_get_contents('php://input');
file_put_contents('stk_callback_log.json', $data); 

$json = json_decode($data, true);

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
    echo json_encode([
        "status" => "success",
        "message" => "Payment successful",
        "amount" => $amount,
        "receipt" => $receipt,
        "date" => $date,
        "phone" => $phone
    ]);
 
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Payment failed: $resultDesc"
    ]);
 }
?>