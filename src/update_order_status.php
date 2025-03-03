<?php
session_start();
include '../inc/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập để thanh toán.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = isset($data['order_id']) ? (int)$data['order_id'] : 0;
$status = isset($data['status']) ? (int)$data['status'] : 0;

if ($order_id <= 0 || $status <= 0) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ.']);
    exit;
}

// Cập nhật trạng thái đơn hàng
$query = "UPDATE orders SET order_status = ? WHERE order_id = ? AND customer_id = ? AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('iii', $status, $order_id, $_SESSION['user']['customer_id']);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không thể cập nhật đơn hàng: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>