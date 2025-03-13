<?php
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: index.php?page=info");
    exit;
}

include 'inc/database.php';

$user_id = $_SESSION['user']['customer_id'];

$query = "SELECT customer_id, customer_username, f_name, l_name, email, phone, street 
          FROM customers 
          WHERE customer_id = ? AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy thông tin người dùng.";
    exit;
}

$user = $result->fetch_assoc();
?>

<div class="update-info-container">
    <h2>Cập Nhật Thông Tin</h2>
    <form id="updateInfoForm">
        <div class="mb-3">
            <label for="customer_username" class="form-label">Username</label>
            <input type="text" class="form-control" id="customer_username" name="customer_username" value="<?php echo htmlspecialchars($user['customer_username'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="f_name" class="form-label">Họ</label>
            <input type="text" class="form-control" id="f_name" name="f_name" value="<?php echo htmlspecialchars($user['f_name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="l_name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="l_name" name="l_name" value="<?php echo htmlspecialchars($user['l_name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="street" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" id="street" name="street" value="<?php echo htmlspecialchars($user['street'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
        <button type="button" class="btn btn-secondary w-100 mt-2" onclick="window.location.href='index.php?page=change_password'">Thay đổi mật khẩu</button>
    </form>
</div>

<script>
    document.getElementById('updateInfoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            customer_username: document.getElementById('customer_username').value,
            f_name: document.getElementById('f_name').value,
            l_name: document.getElementById('l_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            street: document.getElementById('street').value
        };

        fetch('src/client/update_user_info.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cập nhật thông tin thành công!');
                window.location.href = 'index.php?page=info';
            } else {
                alert('Cập nhật thất bại: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        });
    });
</script>

<style>
    .update-info-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .update-info-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #007bff;
    }
</style>
