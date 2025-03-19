<?php
require_once("src/client/config.php"); 
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
        $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

// Tạo checksum để kiểm tra tính hợp lệ
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Khởi tạo các biến thông báo
$message = "";
$subMessage = "";
$alertClass = "";

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
        $stmt->close();

        if ($order != NULL && $order['order_status'] == 1) {
            // Bắt đầu transaction
            $conn->begin_transaction();
            try {
                // INSERT giao dịch vào bảng transactions
                $insert_transaction = "INSERT INTO transactions (order_id, customer_id, amount, payment_method, payment_id, payment_status, transaction_status, created_at) 
                                       VALUES (?, ?, ?, 'vnpay', ?, '00', 'success', NOW())";
                $stmt = $conn->prepare($insert_transaction);
                if (!$stmt) {
                    throw new Exception("Prepare insert transaction error: " . $conn->error);
                }
                $stmt->bind_param("iids", $orderId, $order['customer_id'], $vnp_Amount, $vnpTranId);
                if (!$stmt->execute()) {
                    throw new Exception("Insert transaction error: " . $stmt->error);
                }
                $stmt->close();

                // UPDATE trạng thái đơn hàng thành 2 (thanh toán thành công)
                $update_order = "UPDATE orders SET order_status = 2 WHERE order_id = ?";
                $stmt = $conn->prepare($update_order);
                if (!$stmt) {
                    throw new Exception("Prepare update order error: " . $conn->error);
                }
                $stmt->bind_param('i', $orderId);
                if (!$stmt->execute()) {
                    throw new Exception("Update order error: " . $stmt->error);
                }
                $stmt->close();

                // Commit transaction
                $conn->commit();
                
                $message = "Cảm ơn bạn đã thanh toán thành công!";
                $subMessage = "Đơn hàng của bạn đã được xử lý thành công. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.";
                $alertClass = "alert-success";
            } catch (Exception $e) {
                $conn->rollback();
                $message = "Lỗi khi xử lý giao dịch";
                $subMessage = $e->getMessage();
                $alertClass = "alert-danger";
            }
        } else {
            // Đơn hàng không hợp lệ hoặc đã được xử lý trước đó
            $message = "Giao dịch không được xử lý";
            $subMessage = "Đơn hàng không hợp lệ hoặc đã được xử lý.";
            $alertClass = "alert-warning";
        }
    } else {
        // Thanh toán thất bại
        $message = "Thanh toán thất bại";
        $subMessage = "Giao dịch của bạn không thành công. Vui lòng thử lại hoặc liên hệ hỗ trợ.";
        $alertClass = "alert-danger";
    }
} else {
    // Chữ ký không hợp lệ
    $message = "Lỗi: Chữ ký không hợp lệ";
    $subMessage = "Dữ liệu giao dịch có thể đã bị thay đổi. Vui lòng liên hệ hỗ trợ.";
    $alertClass = "alert-warning";
}
?>
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
