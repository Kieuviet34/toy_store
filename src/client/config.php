<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Thông tin cấu hình VNPay
$vnp_TmnCode = "8SOB2BPU"; // Thay bằng mã TMN của bạn từ VNPay
$vnp_HashSecret = "VDY3DGIXQGDVEHNWEOWPWQ0OGU8RHYXD"; // Thay bằng khóa bí mật của bạn từ VNPay
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // URL sandbox, thay bằng URL production khi triển khai thực tế
$vnp_Returnurl = "http://localhost/project/toy_store/index.php?page=thanks"; // Thay bằng URL thực tế của bạn
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

// Cấu hình thời gian hết hạn (15 phút)
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
?>  