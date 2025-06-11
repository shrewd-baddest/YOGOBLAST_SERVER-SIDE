<?php

include('session_config.php');
$user_id=$_SESSION['user_id'];
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
    }}
$sql="INSERT INTO orders (id,user_id,phoneNumber,reciept,total_price,order_date) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);


 $stmt = $conn->prepare("INSERT INTO payments (amount, receipt, date, phone, status) VALUES (?, ?, ?, ?, 'success')");
    $stmt->bind_param("dsss", $amount, $receipt, $formattedDate, $phone);
    $stmt->execute();
?>