<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$addSuccess = false; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra xem tất cả các trường có được điền đầy đủ không
    if (empty($f_name) || empty($l_name) || empty($email) || empty($phone) || empty($city) || empty($street) || empty($username) || empty($password)) {
        $error = "Vui lòng điền đầy đủ tất cả các trường.";
    } else {
        // Mã hóa password bằng hàm password_hash
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Câu lệnh INSERT với các trường mới
        $insert_query = "INSERT INTO customers (f_name, l_name, email, phone, city, street, customer_username, customer_password, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ssssssss', $f_name, $l_name, $email, $phone, $city, $street, $username, $hashed_password);

        if ($stmt->execute()) {
            $addSuccess = true;
        } else {
            $error = "Không thể thêm khách hàng: " . $stmt->error;
        }
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm khách hàng</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row mb-3">
                    <label for="f_name" class="col-sm-3 col-form-label">Tên</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="f_name" name="f_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="l_name" class="col-sm-3 col-form-label">Họ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="l_name" name="l_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="phone" class="col-sm-3 col-form-label">Số điện thoại</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="city" class="col-sm-3 col-form-label">Thành phố</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="street" class="col-sm-3 col-form-label">Địa chỉ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="street" name="street" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="username" class="col-sm-3 col-form-label">Tên đăng nhập</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password" class="col-sm-3 col-form-label">Mật khẩu</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                    <a href="index.php?page=admin#customers" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
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
    .alert {
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

<?php if ($addSuccess): ?>
<div class="modal fade" id="addSuccessModal" tabindex="-1" aria-labelledby="addSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
        <h4 class="mt-3">Thêm khách hàng thành công!</h4>
      </div>
    </div>
  </div>
</div>
<script>
  var addModal = new bootstrap.Modal(document.getElementById('addSuccessModal'));
  addModal.show();
  setTimeout(function() {
      window.location.href = "index.php?page=admin#customers";
  }, 2000);
</script>
<?php endif; ?>