<?php
session_start();
include '../../inc/database.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Truy vấn cơ bản
$sql = "
    SELECT 
        o.order_id, 
        o.order_date, 
        o.order_status,
        CONCAT(c.f_name, ' ', c.l_name) AS customer_name,
        COALESCE(SUM(oi.list_price * oi.quantity * (1 - oi.discount)), 0) AS total_amount
    FROM orders o
    LEFT JOIN customers c ON o.customer_id = c.customer_id
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.is_deleted = 0
";

if ($q !== '') {
    $sql .= " AND (o.order_id LIKE ? OR CONCAT(c.f_name, ' ', c.l_name) LIKE ?)";
}

$sql .= " GROUP BY o.order_id, o.order_date, o.order_status, c.f_name, c.l_name";
$sql .= " ORDER BY o.order_date ASC";

$stmt = $conn->prepare($sql);

if ($q !== '') {
    $searchTerm = '%' . $q . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>#" . htmlspecialchars($order['order_id']) . "</td>";
        echo "<td>" . htmlspecialchars($order['customer_name']) . "</td>"; 
        echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
        echo "<td>" . number_format($order['total_amount'], 0, ',', '.') . "₫</td>";
        echo "<td>";
        if ($order['order_status'] == 1) {
            echo '<span class="badge bg-warning">Đang chờ xử lý</span>';
        } else {
            echo '<span class="badge bg-success">Đã giao</span>';
        }
        echo "</td>";
        echo "<td>
                <button class='btn btn-sm btn-info btn-view' data-id='" . $order['order_id'] . "' data-bs-toggle='modal' data-bs-target='#viewOrderModal'>
                    <span data-feather='eye'></span> Xem đơn hàng
                </button>
                <button class='btn btn-sm btn-danger btn-delete' data-type='order' data-id='" . $order['order_id'] . "'>
                    <span data-feather='trash-2'></span> Xóa
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Không tìm thấy đơn hàng nào.</td></tr>";
}

$stmt->close();
?>