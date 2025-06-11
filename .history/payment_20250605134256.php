<?php
// include('session_config.php');
// header("Access-Control-Allow-Origin: http://localhost:5173");  
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// header("Content-Type: application/json");

include('connect.php'); 
// $payment_details = json_decode(file_get_contents("php://input"),true);
//    if ($payment_detail === null) {
//      echo json_encode(["status" => "error", "message" => "JSON data not received."]);
//      exit;
//   }
// $phone=$payment_details['phoneNUmber'];
// $quantity=$payment_details['quantity'];
// $Id=$payment_details['id'];
// $amount=payment_details['price'];
 $phone=''; // Replace with actual phone number
 $amount=100;

$consumerKey = '6MKBjpYtfsMJwvaU4Ll5aG5NXHNQ3LPAAzqoFwGZ91y58IXm';
$consumerSecret = 'GHObzsGQsSSpeQRALX1myrf3LoMzfTb3Tlt8C99NGTqiQNXgq7mLFMawLxwJ5Bsd';

// Get Access Token
$credentials = base64_encode("$consumerKey:$consumerSecret");

$ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$access_token = json_decode($response)->access_token;

// STK Push
$BusinessShortCode = '174379'; // Sandbox paybill
$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$Timestamp = date('YmdHis');
$Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

$stk_data = [
    "BusinessShortCode" => $BusinessShortCode,
    "Password" => $Password,
    "Timestamp" => $Timestamp,
    "TransactionType" => "CustomerPayBillOnline",
    "Amount" => $amount,
    "PartyA" => $phone,
    "PartyB" => $BusinessShortCode,
    "PhoneNumber" => $phone,
    "CallBackURL" => "https://f671-102-0-18-196.ngrok-free.app/callback.php", 
    "AccountReference" => "Test123",
    "TransactionDesc" => "Test payment"
];

$ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stk_data));

$response = curl_exec($ch);
curl_close($ch);

echo "Payment request sent. Check your phone!";
?>