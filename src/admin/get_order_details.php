<?php
include '../../inc/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Order ID không hợp lệ.";
    exit;
}

$order_id = (int) $_GET['id'];

$queryOrder = "SELECT * FROM orders WHERE order_id = ? AND is_deleted = 0";
$stmtOrder = $conn->prepare($queryOrder);
$stmtOrder->bind_param("i", $order_id);
$stmtOrder->execute();
$resultOrder = $stmtOrder->get_result();
if ($resultOrder->num_rows == 0) {
    echo "Không tìm thấy đơn hàng.";
    exit;
}
$order = $resultOrder->fetch_assoc();
$stmtOrder->close();

$queryItems = "SELECT oi.*, p.prod_name FROM order_items oi
               JOIN products p ON oi.prod_id = p.prod_id
               WHERE oi.order_id = ?";
$stmtItems = $conn->prepare($queryItems);
$stmtItems->bind_param("i", $order_id);
$stmtItems->execute();
$resultItems = $stmtItems->get_result();
$stmtItems->close();

$queryTrans = "SELECT * FROM transactions WHERE order_id = ? LIMIT 1";
$stmtTrans = $conn->prepare($queryTrans);
$stmtTrans->bind_param("i", $order_id);
$stmtTrans->execute();
$resultTrans = $stmtTrans->get_result();
$transaction = $resultTrans->fetch_assoc();
$stmtTrans->close();
?>

<div>
    <h4>Chi tiết đơn hàng #<?php echo htmlspecialchars($order['order_id']); ?></h4>
    <p><strong>Ngày đặt hàng:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
    <p><strong>Trạng thái:</strong> <?php echo ($order['order_status'] == 1) ? 'Đang chờ xử lý' : 'Đã giao'; ?></p>
</div>
<hr>
<div>
    <h5>Sản phẩm trong đơn hàng:</h5>
    <?php if ($resultItems->num_rows > 0): ?>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $resultItems->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['prod_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo number_format($item['list_price'], 0, ',', '.'); ?>₫</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không có sản phẩm nào trong đơn hàng.</p>
    <?php endif; ?>
</div>
<hr>
<div>
    <h5>Thông tin giao dịch:</h5>
    <?php if ($transaction): ?>
        <p><strong>Mã giao dịch:</strong> <?php echo htmlspecialchars($transaction['payment_id']); ?></p>
        <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($transaction['payment_method']); ?></p>
        <p><strong>Thời gian thanh toán:</strong> <?php echo htmlspecialchars($transaction['created_at']); ?></p>
    <?php else: ?>
        <p>Chưa có thông tin giao dịch.</p>
    <?php endif; ?>
</div>
