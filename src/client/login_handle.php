<?php
include("../../inc/database.php");
session_start(); 

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

// Kiểm tra admin cứng
if ($username === "admin" && $password === "12345") {
    $_SESSION['admin'] = true; 
    echo json_encode([
        "admin"   => true,
        "message" => "Admin đăng nhập thành công"
    ]);
    exit;
}

// Kiểm tra staff
$stmt = $conn->prepare("SELECT staff_id, staff_f_name, staff_l_name, email, phone, store_id, staff_password 
                       FROM staffs 
                       WHERE staff_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();
    if (password_verify($password, $staff['staff_password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['staff'] = [
            'staff_id' => $staff['staff_id'],
            'username' => $username,
            'f_name' => $staff['staff_f_name'],
            'l_name' => $staff['staff_l_name'],
            'email' => $staff['email'],
            'phone' => $staff['phone'],
            'store_id' => $staff['store_id']
        ];
        echo json_encode([
            "admin"   => true,
            "message" => "Staff đăng nhập thành công"
        ]);
        exit;
    } else {
        echo json_encode([
            "success" => false,
            "error"   => "Mật khẩu không đúng"
        ]);
        exit;
    }
}

// Kiểm tra customer nếu không tìm thấy staff
$stmt = $conn->prepare("SELECT customer_id, f_name, l_name, email, phone, street, customer_password 
                       FROM customers 
                       WHERE customer_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    if (password_verify($password, $customer['customer_password'])) {
        $_SESSION['user'] = [
            'customer_id' => $customer['customer_id'],
            'username'    => $username,
            'f_name'      => $customer['f_name'],
            'l_name'      => $customer['l_name'],
            'email'       => $customer['email'],
            'phone'       => $customer['phone'], 
            'street'      => $customer['street']
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
} else {
    echo json_encode([
        "success" => false,
        "error"   => "Tài khoản không tồn tại"
    ]);
}
?>