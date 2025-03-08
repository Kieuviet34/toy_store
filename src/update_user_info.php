<?php
session_start();
include '../inc/database.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Bạn chưa đăng nhập']);
    exit;
}

$user_id = $_SESSION['user']['customer_id'];

$data = json_decode(file_get_contents('php://input'), true);
$f_name = $data['f_name'] ?? '';
$l_name = $data['l_name'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$street = $data['street'] ?? '';

if (empty($f_name) || empty($l_name) || empty($email)) {
    echo json_encode(['success' => false, 'error' => 'Thiếu thông tin bắt buộc']);
    exit;
}

$update_query = "UPDATE customers SET f_name = ?, l_name = ?, email = ?, phone = ?, street = ? WHERE customer_id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("sssssi", $f_name, $l_name, $email, $phone, $street, $user_id);

if ($stmt->execute()) {
    $_SESSION['user'] = [
        'customer_id' => $user_id,
        'username'    => $_SESSION['user']['username'], 
        'f_name'      => $f_name,
        'l_name'      => $l_name,
        'email'       => $email,
        'phone'       => $phone,
        'street'      => $street
    ];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Cập nhật thất bại']);
}
?>