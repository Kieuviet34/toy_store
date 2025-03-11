<?php
include '../../inc/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$item_id = $data['item_id'];
$change = $data['change'];

$query = "UPDATE order_items SET quantity = quantity + ? WHERE item_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $change, $item_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không thể cập nhật số lượng.']);
}
?>