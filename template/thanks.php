<?php
require_once("src/config.php"); 
include 'inc/database.php'; 

// Lấy dữ liệu từ URL (GET)
$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

// Loại bỏ vnp_SecureHash để tính toán checksum
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

// Tạo checksum để kiểm tra tính hợp lệ
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Xử lý logic khi thanh toán thành công
if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        // Thanh toán thành công
        $orderId = $_GET['vnp_TxnRef']; // Mã đơn hàng
        $vnp_Amount = $_GET['vnp_Amount'] / 100; // Số tiền (VND)
        $vnpTranId = $_GET['vnp_TransactionNo']; // Mã giao dịch VNPAY

        // Kiểm tra đơn hàng trong database
        $query = "SELECT order_id, order_status, customer_id FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $res = $stmt->get_result();
        $order = $res->fetch_assoc();

        if ($order != NULL) {
            if ($order['order_status'] == 1) {
                $insert_transaction = "INSERT INTO transactions (order_id, customer_id, amount, payment_method, payment_id, payment_status, transaction_status, created_at) 
                                       VALUES (?, ?, ?, 'vnpay', ?, '00', 'success', NOW())";
                $stmt = $conn->prepare($insert_transaction);
                $stmt->bind_param('iids', $orderId, $order['customer_id'], $vnp_Amount, $vnpTranId);
                $stmt->execute();

                $update_order = "UPDATE orders SET order_status = 2 WHERE order_id = ?";
                $stmt = $conn->prepare($update_order);
                $stmt->bind_param('i', $orderId);
                $stmt->execute();
            }
        }

        $message = "Cảm ơn bạn đã thanh toán thành công!";
        $subMessage = "Đơn hàng của bạn đã được xử lý thành công. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.";
        $alertClass = "alert-success";
    } else {
        // Thanh toán thất bại
        $message = "Thanh toán thất bại";
        $subMessage = "Giao dịch của bạn không thành công. Vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.";
        $alertClass = "alert-danger";
    }
} else {
    // Chữ ký không hợp lệ
    $message = "Lỗi: Chữ ký không hợp lệ";
    $subMessage = "Dữ liệu giao dịch có thể đã bị thay đổi. Vui lòng liên hệ hỗ trợ để được giải quyết.";
    $alertClass = "alert-warning";
}
?>
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }
    .container {
        margin-top: 100px;
    }
    .alert {
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
    }
    .alert-heading {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .alert p {
        font-size: 1.1rem;
        margin-bottom: 20px;
    }
    .btn {
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
        transform: translateY(-2px);
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert <?php echo $alertClass; ?> text-center" role="alert">
                <h1 class="alert-heading"><?php echo $message; ?></h1>
                <p><?php echo $subMessage; ?></p>
                <a href="index.php?page=home" class="btn <?php echo ($alertClass == 'alert-success') ? 'btn-primary' : 'btn-secondary'; ?> mt-3">Quay về trang chủ</a>
            </div>
        </div>
    </div>
</div>
