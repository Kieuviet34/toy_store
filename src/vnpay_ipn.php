<?php
require_once("config.php");
include '../inc/database.php';

file_put_contents('vnpay_ipn_log.txt', "Received data: " . print_r($_GET, true) . "\n", FILE_APPEND);

$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

$vnp_SecureHash = $inputData['vnp_SecureHash'];
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$hashData = http_build_query($inputData);
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

$vnpTranId = $inputData['vnp_TransactionNo'];
$vnp_Amount = $inputData['vnp_Amount'] / 100;
$orderId = (int)$inputData['vnp_TxnRef']; // Giả sử vnp_TxnRef là order_id, nếu khác thì điều chỉnh

$returnData = array();

if ($secureHash == $vnp_SecureHash) {
    $query = "SELECT o.order_id, o.customer_id, o.order_status, 
              SUM(oi.list_price * oi.quantity * (1 - oi.discount)) AS total_amount
              FROM orders o
              JOIN order_items oi ON o.order_id = oi.order_id
              WHERE o.order_id = ?
              GROUP BY o.order_id";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        file_put_contents('vnpay_ipn_log.txt', "Order found - Total: {$order['total_amount']}, VNPay Amount: {$vnp_Amount}, Status: {$order['order_status']}\n", FILE_APPEND);

        if ($order['total_amount'] == $vnp_Amount) {
            if ($order['order_status'] == 1) {
                if ($inputData['vnp_ResponseCode'] == '00' && $inputData['vnp_TransactionStatus'] == '00') {
                    $newStatus = 2; // Paid
                    $paymentStatus = 'success';

                    // Cập nhật đơn hàng
                    $updateQuery = "UPDATE orders SET order_status = ? WHERE order_id = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param('ii', $newStatus, $orderId);
                    if (!$stmt->execute()) {
                        file_put_contents('vnpay_ipn_log.txt', "Update failed: " . $stmt->error . "\n", FILE_APPEND);
                        $returnData['RspCode'] = '99';
                        $returnData['Message'] = 'Update error';
                    } else {
                        // Lưu giao dịch
                        $insertQuery = "INSERT INTO transaction (order_id, customer_id, amount, payment_method, payment_id, payment_status, payment_details, status) 
                                        VALUES (?, ?, ?, 'vnpay', ?, ?, ?, ?)";
                        $stmt = $conn->prepare($insertQuery);
                        $customerId = $order['customer_id'];
                        $paymentDetails = json_encode($inputData);
                        $status = $paymentStatus;
                        $stmt->bind_param('iidsiss', $orderId, $customerId, $vnp_Amount, $vnpTranId, $paymentStatus, $paymentDetails, $status);
                        if (!$stmt->execute()) {
                            file_put_contents('vnpay_ipn_log.txt', "Insert failed: " . $stmt->error . "\n", FILE_APPEND);
                            $returnData['RspCode'] = '99';
                            $returnData['Message'] = 'Insert error';
                        } else {
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        }
                    }
                } else {
                    $newStatus = 3; // Failed
                    $paymentStatus = 'failed';
                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Transaction failed';
                }
            } else {
                $returnData['RspCode'] = '02';
                $returnData['Message'] = 'Order already confirmed';
            }
        } else {
            $returnData['RspCode'] = '04';
            $returnData['Message'] = 'Invalid amount';
        }
    } else {
        $returnData['RspCode'] = '01';
        $returnData['Message'] = 'Order not found';
    }
} else {
    $returnData['RspCode'] = '97';
    $returnData['Message'] = 'Invalid signature';
}

echo json_encode($returnData);
?>