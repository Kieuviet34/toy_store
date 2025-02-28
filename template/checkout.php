<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        .checkout-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            margin: 50px auto;
        }
        .checkout-container h2 {
            color: #333;
        }
        .checkout-container label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }
        .checkout-container input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .button-container {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .checkout-container button {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .checkout-container .cash-btn {
            background-color: #28a745;
            color: white;
        }
        .checkout-container .qr-btn {
            background-color: #007bff;
            color: white;
        }
        .checkout-container button:hover {
            opacity: 0.8;
        }
        .qr-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 250px;
        }
        .qr-popup img {
            max-width: 200px;
            border-radius: 10px;
        }
        .qr-popup p {
            margin-top: 10px;
            font-weight: bold;
            color: #d9534f;
            font-size: 14px;
        }
        .qr-popup button {
            margin-top: 10px;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background-color: #d9534f;
            color: white;
            cursor: pointer;
        }
        .qr-popup button:hover {
            opacity: 0.8;
        }
    </style>
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
        
        function showQR() {
            if (validateForm()) {
                document.getElementById("qrPopup").style.display = "block";
            }
        }
        
        function closeQR() {
            document.getElementById("qrPopup").style.display = "none";
        }
    </script>
</head>
<body>
    <div class="checkout-container">
        <h2>Thanh toán</h2>
        <form onsubmit="return validateForm()">
            <label for="name">Tên người nhận:</label>
            <input type="text" id="name" name="name">

            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone">

            <label for="email">Gmail:</label>
            <input type="email" id="email" name="email">

            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address">
            
            <div class="button-container">
                <button type="button" class="cash-btn" onclick="confirmPayment()">Thanh toán tiền mặt</button>
                <button type="button" class="qr-btn" onclick="showQR()">Thanh toán mã QR</button>
            </div>
        </form>
    </div>
    
    <div id="qrPopup" class="qr-popup">
        <img src="template/img/qr.jpg" alt="Mã QR thanh toán">
        <p>Vui lòng ghi nội dung chuyển khoản là họ tên + số điện thoại người nhận</p>
        <button onclick="closeQR()">Đóng</button>
    </div>
</body>
</html>
