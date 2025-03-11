<?php
include 'inc/database.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo '<div class="container mt-5"><h1 class="display-4 text-center">Thanh Toán</h1><p class="text-center">Vui lòng đăng nhập để thanh toán.</p></div>';
    exit;
}

$customer_id = $_SESSION['user']['customer_id'];
$user_email = $_SESSION['user']['email'];
$user_phone = isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : '';
$user_address = isset($_SESSION['user']['street']) ? $_SESSION['user']['street'] : '';

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
            <button type="button" class="btn-primary" onclick="payWithVNPay(<?php echo $order_id; ?>)">Thanh toán với VNPay</button>
         </div>
    </form>
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

function payWithVNPay(orderId) {
    if (validateForm()) {
        window.location.href = 'src/vnpay_pay.php?order_id=' + orderId;
    }
}


</script>