<?php
session_start(); 
include '../../inc/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';

if (empty($email)) {
    echo json_encode(['success' => false, 'error' => 'Email không được để trống']);
    exit;
}

$stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['reset_email'] = $email;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Email không tồn tại']);
}
?>