<?php
session_start();
require_once("config.php");
include '../inc/database.php';
//đkm vi en pây trôn tao (mất mẹ 2 ngày fix ipn)

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;
if (!$order_id) {
    echo "Không tìm thấy order_id.";
    exit;
}

$query = "SELECT SUM(list_price * quantity * (1 - discount)) AS total_amount 
          FROM order_items 
          WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "Đơn hàng không tồn tại hoặc không hợp lệ.";
    exit;
}
$order = $result->fetch_assoc();
$vnp_Amount = $order['total_amount']; 

$bankCode = isset($_POST['bankCode']) ? $_POST['bankCode'] : '';

$vnp_TxnRef = $order_id; 
$vnp_OrderInfo = "Thanh toan don hang #$order_id";
$vnp_Locale = 'vn';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode, 
    "vnp_Amount" => $vnp_Amount * 100, 
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => $vnp_Returnurl, 
    "vnp_TxnRef" => $vnp_TxnRef,
    "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes'))
);

if (!empty($bankCode)) {
    $inputData['vnp_BankCode'] = $bankCode;
}

ksort($inputData);
$query = http_build_query($inputData);
$vnpSecureHash = hash_hmac('sha512', $query, $vnp_HashSecret); 
$vnp_Url = $vnp_Url . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash; 

header('Location: ' . $vnp_Url);
exit;
?>