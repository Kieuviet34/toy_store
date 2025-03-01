<?php
include("../inc/database.php");
session_start(); // Khởi động session

header("Content-type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode([
        "success" => false,
        "error"   => "Thiếu thông tin đăng nhập"
    ]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

// Kiểm tra nếu đăng nhập admin với username "admin" và password "12345"
if ($username === "admin" && $password === "12345") {
    $_SESSION['admin'] = true; // Lưu session cho admin
    echo json_encode([
        "admin"   => true,
        "message" => "Admin đăng nhập thành công"
    ]);
    exit;
}

// Nếu không phải admin, tiến hành đăng nhập khách hàng
$stmt = $conn->prepare("SELECT customer_id, f_name, l_name, email, customer_password FROM customers WHERE customer_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "error"   => "Tài khoản không tồn tại"
    ]);
    exit;
}

$customer = $result->fetch_assoc();

if (password_verify($password, $customer['customer_password'])) {
    // Lưu thông tin người dùng vào session
    $_SESSION['user'] = [
        'customer_id' => $customer['customer_id'],
        'username'    => $username,
        'f_name'      => $customer['f_name'],
        'l_name'      => $customer['l_name'],
        'email'       => $customer['email']
    ];
    echo json_encode([
        "success" => true,
        "message" => "Đăng nhập thành công."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error"   => "Mật khẩu không đúng"
    ]);
}
?>