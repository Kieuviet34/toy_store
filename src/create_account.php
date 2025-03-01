<?php
    include("../inc/database.php");
    header("Content-Type: application/json");

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data['first_name'], $data['last_name'],$data['username'] ,$data['email'], $data['phone'], 
            $data['address'], $data['city'], $data['zip'], $data['password'], $data['confirm_password'])
    ) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit;
    }

    $first_name = trim($data['first_name']);
    $last_name  = trim($data['last_name']);
    $username   = trim($data['username']);
    $email      = trim($data['email']);
    $phone      = trim($data['phone']);
    $address    = trim($data['address']); 
    $city       = trim($data['city']);
    $zip        = trim($data['zip']);     
    $password   = $data['password'];
    $confirm_password = $data['confirm_password'];

    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "error" => "Mật khẩu không khớp."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "error" => "Email đã được đăng ký."]);
        exit;
    }
    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE customer_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "error" => "Tên người dùng đã tồn tại."]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO customers (f_name, l_name, email, phone, street, city, zip_code, customer_password, customer_username) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("sssssssss", $first_name, $last_name, $email, $phone, $address, $city, $zip, $hashedPassword, $username);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Tạo tài khoản thành công."]);
    } else {
        echo json_encode(["success" => false, "error" => "Tạo tài khoản thất bại: " . $stmt->error]);
    }
?>
