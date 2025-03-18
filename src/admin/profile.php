<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$adminInfo = $_SESSION['staff'];

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $errors = [];
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = "Mật khẩu phải có ít nhất 8 ký tự";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            $errors[] = "Mật khẩu phải có ít nhất 1 chữ hoa";
        }
        if (!preg_match("/[0-9]/", $password)) {
            $errors[] = "Mật khẩu phải có ít nhất 1 số";
        }
        if (!preg_match("/[\\W]/", $password)) {
            $errors[] = "Mật khẩu phải có ít nhất 1 ký tự đặc biệt";
        }
    }

    if (!empty($errors)) {
        $error = implode('\n', $errors);
    } else {
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $adminInfo['staff_password'];

        $stmt = $conn->prepare("UPDATE staffs SET 
            staff_f_name = ?,
            staff_l_name = ?,
            email = ?,
            phone = ?,
            staff_password = ?
            WHERE staff_id = ?");

        $stmt->bind_param("sssssi", 
            $f_name,
            $l_name,
            $email,
            $phone,
            $hashed_password,
            $adminInfo['staff_id']
        );

        if ($stmt->execute()) {
            $_SESSION['staff'] = array_merge($_SESSION['staff'], [
                'f_name' => $f_name,
                'l_name' => $l_name,
                'email' => $email,
                'phone' => $phone
            ]);
            $success = true;
        } else {
            $error = "Cập nhật thất bại, vui lòng thử lại!";
        }
    }
}
?>

<style>
.modal-success, .modal-error {
    background: rgba(0,0,0,0.4);
}
.modal-success .bi-check-circle, .modal-error .bi-x-circle {
    font-size: 4rem;
}
.modal-success .bi-check-circle {
    color: #28a745;
}
.modal-error .bi-x-circle {
    color: #dc3545;
}
</style>

<div class="container mt-4">
    <h2 class="mb-4">Cập nhật thông tin</h2>
    <form method="POST" id="profileForm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tên</label>
                <input type="text" class="form-control" name="f_name" value="<?= htmlspecialchars($adminInfo['f_name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Họ</label>
                <input type="text" class="form-control" name="l_name" value="<?= htmlspecialchars($adminInfo['l_name']) ?>" required>
            </div>

            <div class="col-12">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($adminInfo['email']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Điện thoại</label>
                <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($adminInfo['phone']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                <input type="password" class="form-control" name="password">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Cập nhật
                </button>
            </div>
        </div>
    </form>
</div>

<?php if ($success): ?>
  <div class="modal fade show modal-success" id="successModal" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-body text-center py-5">
          <i class="bi bi-check-circle"></i>
          <h4 class="mt-3">Cập nhật thành công!</h4>
        </div>
      </div>
    </div>
  </div>
  <script>
    setTimeout(() => {
      let successModal = document.getElementById('successModal');
      if(successModal){
        successModal.style.display = 'none';
      }
      window.location.href = "index.php?page=admin&action=dashboard#dashboard";
    }, 2000);
  </script>
<?php elseif ($error): ?>
  <div class="modal fade show modal-error" id="errorModal" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-body text-center py-5">
          <i class="bi bi-x-circle"></i>
          <h4 class="mt-3"><?= htmlspecialchars($error) ?></h4>
        </div>
      </div>
    </div>
  </div>
  <script>
    setTimeout(() => {
      let errorModal = document.getElementById('errorModal');
      if(errorModal){
        errorModal.style.display = 'none';
      }
    }, 2000);
  </script>
<?php endif; ?>
