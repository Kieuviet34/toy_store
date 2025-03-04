<?php
header('Content-Type: application/json');

include '../inc/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? trim($data['email']) : '';
$new_password = isset($data['new_password']) ? trim($data['new_password']) : '';

if (empty($email) || empty($new_password)) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng cung cấp email và mật khẩu mới.']);
    exit;
}

$query = "SELECT customer_id FROM customers WHERE email = ? AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_query = "UPDATE customers SET password = ? WHERE email = ? AND is_deleted = 0";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ss', $hashed_password, $email);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể cập nhật mật khẩu: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Email không tồn tại hoặc đã bị xóa.']);
}

$stmt->close();
$conn->close();
?>