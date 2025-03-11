<?php
session_start();
include '../inc/database.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$item_id = isset($data['item_id']) ? (int)$data['item_id'] : 0;

if ($item_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID mục không hợp lệ.']);
    exit;
}

$customer_id = $_SESSION['user']['customer_id'];
$query = "UPDATE order_items oi
          JOIN orders o ON oi.order_id = o.order_id
          SET oi.quantity = oi.quantity - 1
          WHERE oi.item_id = ? AND o.customer_id = ? AND oi.quantity > 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $item_id, $customer_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không tìm thấy mục để cập nhật hoặc số lượng đã bằng 0.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
