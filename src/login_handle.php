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
    //hash password
    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ? AND customer_password = ?")
?>