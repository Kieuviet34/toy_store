<?php
include 'inc/database.php';

$customer_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($customer_id <= 0) {
    echo "Khách hàng không hợp lệ.";
    exit;
}

$query = "SELECT * FROM customers WHERE customer_id = ? AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Khách hàng không tồn tại hoặc đã bị xóa.";
    exit;
}

$customer = $result->fetch_assoc();

$updateSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);
    $username = trim($_POST['customer_username']);

    if (empty($f_name) || empty($l_name) || empty($email) || empty($phone) || empty($city) || empty($street)) {
        $error = "Vui lòng điền đầy đủ tất cả các trường.";
    } else {
        $update_query = "
            UPDATE customers 
            SET f_name = ?, l_name = ?, email = ?, phone = ?, city = ?, street = ?, customer_username = ? 
            WHERE customer_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('sssssssi', $f_name, $l_name, $email, $phone, $city, $street, $username, $customer_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $updateSuccess = true;
        } else {
            $error = "Không thể cập nhật khách hàng: " . $stmt->error;
        }
    }
}
?>

<div class="form-container">
    <h2 class="mb-4">Cập nhật khách hàng</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="f_name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="f_name" name="f_name" value="<?php echo htmlspecialchars($customer['f_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="l_name" class="form-label">Họ</label>
            <input type="text" class="form-control" id="l_name" name="l_name" value="<?php echo htmlspecialchars($customer['l_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Thành phố</label>
            <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($customer['city']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="street" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" id="street" name="street" value="<?php echo htmlspecialchars($customer['street']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <!-- Sửa name thành customer_username -->
            <input type="text" class="form-control" id="username" name="customer_username" value="<?php echo htmlspecialchars($customer['customer_username']); ?>" required>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Cập nhật</button>
            <a href="index.php?page=admin&action=customers#customers" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
        </div>
    </form>
</div>

<style>
    .form-container {
        max-width: 700px;
        margin: 50px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .error {
        color: #dc3545;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 15px;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        color: white;
    }
</style>

<!-- Modal Cập nhật thành công -->
<?php if ($updateSuccess): ?>
<div class="modal fade" id="updateSuccessModal" tabindex="-1" aria-labelledby="updateSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
        <h4 class="mt-3">Cập nhật thành công!</h4>
      </div>
    </div>
  </div>
</div>
<script>
  var successModal = new bootstrap.Modal(document.getElementById('updateSuccessModal'));
  successModal.show();
  setTimeout(function() {
    window.location.href = "index.php?page=admin&action=customers#customers";
  }, 2000);
</script>
<?php endif; ?>
