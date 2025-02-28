<?php
    include("../inc/database.php");
    
    header("Content-type: application/json");
    
    $data = json_decode(file_get_contents("php://input"), true);

    if(!isset($data['username'])||!isset($data['password'])){
        echo json_encode(["success"=>false,
            "error"=>"Thiếu thông tin đăng nhập"]);
            exit;
    }
    $username = $data['username'];
    $password = $data['password'];

    // Kiểm tra nếu đăng nhập admin với username "admin" và password "12345"
    if ($username === "admin" && $password === "12345") {
        echo json_encode([
            "admin"  => true,
            "message" => "Admin đăng nhập thành công"
        ]);
        exit;
    }

    // Nếu không phải admin, tiến hành đăng nhập khách hàng
    $stmt = $conn->prepare("SELECT customer_id, f_name, l_name, email, customer_password FROM customers WHERE email = ?");
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