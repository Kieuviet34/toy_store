<?php

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script>
        function validateForm() {
            let name = document.getElementById("name").value;
            let phone = document.getElementById("phone").value;
            let email = document.getElementById("email").value;
            let address = document.getElementById("address").value;
            
            if (!name || !phone || !email || !address) {
                alert("Vui lòng điền đầy đủ thông tin!");
                return false;
            }
            return true;
        }

        function confirmPayment() {
            if (validateForm()) {
                alert("Bạn đã xác nhận thanh toán thành công!");
            }
        }
    </script>
</head>
<body>
    <h2>Thanh toán</h2>
    <form onsubmit="return validateForm()">
        <label for="name">Tên người nhận:</label>
        <input type="text" id="name" name="name"><br>
        
        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone"><br>
        
        <label for="email">Gmail:</label>
        <input type="email" id="email" name="email"><br>
        
        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address"><br>
        
        <button type="button" onclick="confirmPayment()">Thanh toán tiền mặt</button>
        <button type="submit">Thanh toán mã QR</button>
    </form>
</body>
</html>
