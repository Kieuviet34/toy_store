<?php
session_start();
include 'inc/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo '<div class="container mt-5"><h1 class="display-4 text-center">Thanh Toán</h1><p class="text-center">Vui lòng đăng nhập để thanh toán.</p></div>';
    exit;
}

$customer_id = $_SESSION['user']['customer_id'];
$user_email = $_SESSION['user']['email'];
$user_phone = isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : '';
$user_address = isset($_SESSION['user']['street']) ? $_SESSION['user']['street'] : '';

// Lấy đơn hàng đang chờ xử lý (order_status = 1)
$query = "SELECT order_id FROM orders WHERE customer_id = ? AND order_status = 1 AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><h1 class="display-4 text-center">Thanh Toán</h1><p class="text-center">Không có đơn hàng nào để thanh toán.</p></div>';
    exit;
}

$order = $result->fetch_assoc();
$order_id = $order['order_id'];
?>

<style>
    .checkout-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 400px;
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

<div class="checkout-container">
    <h2>Thanh toán</h2>
    <form id="checkoutForm">
        <label for="name">Tên người nhận:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['f_name'] . ' ' . $_SESSION['user']['l_name']); ?>">

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>">

        <label for="email">Gmail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>">

        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_address); ?>">
        
        <div class="button-container">
            <button type="button" class="cash-btn" onclick="confirmPayment(<?php echo $order_id; ?>)">Thanh toán tiền mặt</button>
            <button type="button" class="qr-btn" onclick="showQR(<?php echo $order_id; ?>)">Thanh toán mã QR</button>
        </div>
    </form>
</div>

<div id="qrPopup" class="qr-popup">
    <img src="template/img/qr.jpg" alt="Mã QR thanh toán">
    <p>Vui lòng ghi nội dung chuyển khoản là họ tên + số điện thoại người nhận</p>
    <button onclick="closeQR()">Đóng</button>
</div>

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

function confirmPayment(orderId) {
    if (validateForm()) {
        // So sánh thông tin nhập vào với thông tin mặc định từ session
        let defaultName = "<?php echo addslashes($_SESSION['user']['f_name'] . ' ' . $_SESSION['user']['l_name']); ?>";
        let defaultPhone = "<?php echo addslashes($user_phone); ?>";
        let defaultEmail = "<?php echo addslashes($user_email); ?>";
        let defaultAddress = "<?php echo addslashes($user_address); ?>";

        let name = document.getElementById("name").value;
        let phone = document.getElementById("phone").value;
        let email = document.getElementById("email").value;
        let address = document.getElementById("address").value;

        if (name === defaultName && phone === defaultPhone && email === defaultEmail && address === defaultAddress) {

            if (confirm("Xác nhận thanh toán với thông tin hiện tại?")) {
                completePayment(orderId);
            }
        } else {
            if (confirm("Thông tin đã thay đổi. Bạn có muốn cập nhật thông tin này không?")) {

                completePayment(orderId);
            } else {
                if (confirm("Xác nhận thanh toán với thông tin vừa nhập?")) {
                    completePayment(orderId);
                }
            }
        }
    }
}

function completePayment(orderId) {
    fetch('src/update_order_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ order_id: orderId, status: 2 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Bạn đã xác nhận thanh toán thành công!");
            window.location.href = 'index.php?page=home';
        } else {
            alert("Có lỗi xảy ra: " + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Đã xảy ra lỗi khi thanh toán.");
    });
}

function showQR(orderId) {
    if (validateForm()) {
        document.getElementById("qrPopup").style.display = "block";
        document.querySelector('.qr-popup button').onclick = function() {
            fetch('src/update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId, status: 2 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Bạn đã xác nhận thanh toán thành công!");
                    window.location.href = 'index.php?page=home';
                } else {
                    alert("Có lỗi xảy ra: " + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Đã xảy ra lỗi khi thanh toán.");
            });
            closeQR();
        };
    }
}

function closeQR() {
    document.getElementById("qrPopup").style.display = "none";
}
</script>