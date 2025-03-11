<?php
include '../inc/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'];

$query = "DELETE FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không thể xóa giỏ hàng.']);
}
?>