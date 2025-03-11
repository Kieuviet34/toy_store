<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán VNPay</title>
    <link href="../template/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="../template/css/jumbotron-narrow.css" rel="stylesheet">  
    <script src="../template/js/jquery-1.11.3.min.js"></script>
</head>
<body>
    <?php 
    require_once("config.php"); 
    include '../inc/database.php';

    // Lấy order_id từ URL
    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
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
    $order_data = $result->fetch_assoc();
    $total_amount = $order_data['total_amount'] ?? 0; 
    ?>
    <div class="container">
        <h3>Thanh toán đơn hàng</h3>
        <form action="vnpay_create_payment.php" method="post">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <div class="form-group">
                <label for="amount">Số tiền</label>
                <input class="form-control" id="amount" name="amount" type="number" 
                       value="<?php echo htmlspecialchars($total_amount); ?>" readonly />
            </div>
            <h4>Chọn phương thức thanh toán</h4>
            <div class="form-group">
                <input type="radio" checked id="default" name="bankCode" value="">
                <label for="default">Cổng thanh toán VNPAYQR</label><br>
                <input type="radio" id="vnpayqr" name="bankCode" value="VNPAYQR">
                <label for="vnpayqr">Thanh toán bằng VNPAYQR</label><br>
                <input type="radio" id="vnbank" name="bankCode" value="VNBANK">
                <label for="vnbank">Thẻ ATM/Tài khoản nội địa</label><br>
                <input type="radio" id="intcard" name="bankCode" value="INTCARD">
                <label for="intcard">Thẻ quốc tế</label><br>
            </div>
            <button type="submit" class="btn btn-primary">Thanh toán</button>
        </form>
    </div>
</body>
</html>