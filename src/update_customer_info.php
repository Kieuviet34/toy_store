<?php
session_start();
include '../inc/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập để cập nhật thông tin.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$customer_id = isset($data['customer_id']) ? (int)$data['customer_id'] : 0;
$name = isset($data['name']) ? trim($data['name']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$address = isset($data['address']) ? trim($data['address']) : '';
$name_parts = explode(' ', $name);
$l_name = array_pop($name_parts);
$f_name = implode(' ', $name_parts);

if ($customer_id <= 0 || !$name || !$phone || !$email || !$address) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ.']);
    exit;
}

$query = "UPDATE customers SET f_name = ?, l_name = ?, email = ?, phone = ?, street = ? WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('sssssi', $f_name, $l_name, $email, $phone, $address, $customer_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION['user']['f_name'] = $f_name;
    $_SESSION['user']['l_name'] = $l_name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['street'] = $address;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không thể cập nhật thông tin: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>