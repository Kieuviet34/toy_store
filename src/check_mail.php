<?php
header('Content-Type: application/json');

include '../inc/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? trim($data['email']) : '';

if (empty($email)) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng nhập email.']);
    exit;
}

$query = "SELECT customer_id FROM customers WHERE email = ? AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Email chưa được đăng ký.']);
}

$stmt->close();
$conn->close();
?>