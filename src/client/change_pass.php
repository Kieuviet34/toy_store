<?php
session_start();
include '../../inc/database.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit;
}

$user_id = $_SESSION['user']['customer_id'];
$data = json_decode(file_get_contents("php://input"), true);

$current_password = $data['current_password'] ?? '';
$new_password = $data['new_password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

if (!$current_password || !$new_password || !$confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.']);
    exit;
}

$query = "SELECT customer_password FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($current_password, $user['customer_password'])) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng.']);
    exit;
}

$new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
$update_query = "UPDATE customers SET customer_password = ? WHERE customer_id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("si", $new_password_hashed, $user_id);

if ($update_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.']);
}
