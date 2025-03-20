<?php
include 'inc/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

$customer_id = $_SESSION['user']['customer_id'];

$query = "
    SELECT 
        o.order_id, 
        o.order_date,
        t.payment_id,
        t.payment_method,
        GROUP_CONCAT(CONCAT(p.prod_name, ' (', oi.quantity, ')') SEPARATOR ', ') AS products,
        COALESCE(SUM(oi.list_price * oi.quantity * (1 - oi.discount)), 0) AS total_amount,
        CASE 
            WHEN o.order_status = 1 THEN 'Đang chờ xử lý'
            WHEN o.order_status = 2 THEN 'Đã giao'
            ELSE 'Khác'
        END AS order_status
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN products p ON oi.prod_id = p.prod_id
    LEFT JOIN transactions t ON o.order_id = t.order_id
    WHERE o.is_deleted = 0 
      AND o.customer_id = ? 
      AND o.order_status = 2
    GROUP BY o.order_id, o.order_date, t.payment_id, t.payment_method, o.order_status
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<div class="container mt-5">
    <h2 class="mb-4">Lịch sử đặt hàng</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Mã giao dịch</th>
                    <th>Phương thức thanh toán</th>
                    <th>Sản phẩm (Số lượng)</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($row['order_id']) ?></td>
                            <td><?= htmlspecialchars($row['order_date']) ?></td>
                            <td><?= htmlspecialchars($row['payment_id']) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><?= htmlspecialchars($row['products']) ?></td>
                            <td><?= number_format($row['total_amount'], 0, ',', '.') ?>₫</td>
                            <td><?= htmlspecialchars($row['order_status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Không có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
