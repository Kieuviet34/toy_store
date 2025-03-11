<?php
session_start();
include '../../inc/database.php';

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

$customer_id = $_SESSION['user']['customer_id'];

$query = "SELECT oi.quantity 
          FROM order_items oi
          JOIN orders o ON oi.order_id = o.order_id
          WHERE oi.item_id = ? AND o.customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $item_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Không tìm thấy mục hoặc mục không thuộc về bạn.']);
    $stmt->close();
    $conn->close();
    exit;
}

$row = $result->fetch_assoc();
$current_quantity = $row['quantity'];
$stmt->close();

if ($current_quantity > 1) {
    $updateQuery = "UPDATE order_items 
                    SET quantity = quantity - 1 
                    WHERE item_id = ? AND quantity > 0";
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param('i', $item_id);
    $stmtUpdate->execute();
    if ($stmtUpdate->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể cập nhật số lượng.']);
    }
    $stmtUpdate->close();
} else {
    $deleteQuery = "DELETE FROM order_items WHERE item_id = ?";
    $stmtDelete = $conn->prepare($deleteQuery);
    $stmtDelete->bind_param('i', $item_id);
    $stmtDelete->execute();
    if ($stmtDelete->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể xóa mục.']);
    }
    $stmtDelete->close();
}

$conn->close();
?>
