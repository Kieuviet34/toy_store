<?php
include '../inc/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$new_password = $data['new_password'] ?? '';

if (empty($email) || empty($new_password)) {
    echo json_encode(['success' => false, 'error' => 'Thiếu thông tin']);
    exit;
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE customers SET customer_password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed_password, $email);
if ($stmt->execute()) {
    unset($_SESSION['reset_email']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Cập nhật mật khẩu thất bại']);
}
?>