<?php
include '../../inc/database.php';

header('Content-Type: application/json');

$query = "
    SELECT 
        o.order_id, 
        o.customer_id, 
        o.order_date, 
        o.order_status, 
        COALESCE(SUM(oi.list_price * oi.quantity * (1 - oi.discount)), 0) AS total_amount,
        t.payment_method,
        t.payment_id,
        t.payment_status,
        t.created_at AS transaction_time
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN transactions t ON o.order_id = t.order_id
    WHERE o.is_deleted = 0
    GROUP BY o.order_id, o.customer_id, o.order_date, o.order_status, t.payment_method, t.payment_id, t.payment_status, t.created_at
    ORDER BY o.order_date ASC
";

$result = $conn->query($query);

$orders = [];
while ($row = $result->fetch_assoc()) {
    $row['order_status'] = ($row['order_status'] == 1) ? 'Đang chờ xử lý' : 'Đã giao';
    $orders[] = $row;
}

echo json_encode($orders, JSON_UNESCAPED_UNICODE);
exit;
?>
