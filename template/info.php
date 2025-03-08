<?php
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'inc/database.php';

// Nếu là admin, hiển thị thông báo và không cần truy vấn database
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $is_admin = true;
    $user = [
        'username' => 'admin',
        'f_name' => 'Quản trị viên',
        'l_name' => '',
        'email' => 'admin@example.com',
        'phone' => 'N/A',
        'street' => 'N/A'
    ];
} else {
    $is_admin = false;
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
    $_SESSION['user'] = [
        'customer_id' => $user['customer_id'],
        'username'    => $user['customer_username'],
        'f_name'      => $user['f_name'],
        'l_name'      => $user['l_name'],
        'email'       => $user['email'],
        'phone'       => $user['phone'],
        'street'      => $user['street']
    ];
}
?>

<div class="user-info-container">
    <h2>Thông Tin Cá Nhân</h2>
    <div class="user-info-item">
        <label>Tên đăng nhập:</label>
        <span><?php echo htmlspecialchars($user['customer_username']); ?></span>
    </div>
    <div class="user-info-item">
        <label>Họ:</label>
        <span><?php echo htmlspecialchars($user['f_name'] ?? 'Chưa cập nhật'); ?></span>
    </div>
    <div class="user-info-item">
        <label>Tên:</label>
        <span><?php echo htmlspecialchars($user['l_name'] ?? 'Chưa cập nhật'); ?></span>
    </div>
    <div class="user-info-item">
        <label>Email:</label>
        <span><?php echo htmlspecialchars($user['email'] ?? 'Chưa cập nhật'); ?></span>
    </div>
    <div class="user-info-item">
        <label>Số điện thoại:</label>
        <span><?php echo htmlspecialchars($user['phone'] ?? 'Chưa cập nhật'); ?></span>
    </div>
    <div class="user-info-item">
        <label>Địa chỉ:</label>
        <span><?php echo htmlspecialchars($user['street'] ?? 'Chưa cập nhật'); ?></span>
    </div>
    <?php if (!$is_admin): ?>
        <a href="index.php?page=update_info" class="btn btn-primary update-btn">Cập nhật thông tin</a>
    <?php endif; ?>
</div>