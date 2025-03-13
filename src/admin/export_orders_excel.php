<?php
include '../../inc/database.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=orders_report_' . date('YmdHis') . '.csv');
echo "\xEF\xBB\xBF";
$output = fopen('php://output', 'w');

fputcsv($output, array(
    'Order ID', 
    'Customer ID', 
    'Order Date', 
    'Order Status', 
    'Total Amount', 
    'Payment Method', 
    'Payment ID', 
    'Payment Status', 
    'Transaction Time'
));

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
    ORDER BY o.order_date ASC";
$result = $conn->query($query);

while($row = $result->fetch_assoc()){
    $order_status_text = ($row['order_status'] == 1) ? 'Đang chờ xử lý' : 'Đã giao';
    
    fputcsv($output, array(
        $row['order_id'],
        $row['customer_id'],
        $row['order_date'],
        $order_status_text,
        $row['total_amount'],
        $row['payment_method'],
        $row['payment_id'],
        $row['payment_status'],
        $row['transaction_time']
    ));
}

fclose($output);
exit;
?>
