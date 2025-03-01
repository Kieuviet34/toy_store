<?php
session_start();
include '../inc/database.php';

header('Content-Type: application/json');

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

$query = "DELETE FROM order_items WHERE item_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $item_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>