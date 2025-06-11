<?php
$data = file_get_contents('php://input');
// file_put_contents('stk_callback_log.json', $data); 

$json = json_decode($data, true);

$resultCode = $json['Body']['stkCallback']['ResultCode'];
$resultDesc = $json['Body']['stkCallback']['ResultDesc'];

if ($resultCode == 0) {
    $metadata = $json['Body']['stkCallback']['CallbackMetadata']['Item'];

    $amount = $metadata[0]['Value'];
    $receipt = $metadata[1]['Value'];
    $date = $metadata[2]['Value'];
    $phone = $metadata[3]['Value'];
echo 
    // TODO: Save to DB or update order status
    // file_put_contents('payment_success_log.txt', "Payment of $amount by $phone. Receipt: $receipt\n", FILE_APPEND);
} else {
    file_put_contents('payment_fail_log.txt', "Failed payment: $resultDesc\n", FILE_APPEND);
}
?>