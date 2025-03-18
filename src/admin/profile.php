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
    $f_name   = $_POST['f_name'];
    $l_name   = $_POST['l_name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
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
        $error = implode("\n", $errors);
    } else {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE staffs SET 
                staff_f_name = ?,
                staff_l_name = ?,
                staff_username = ?,
                email = ?,
                phone = ?,
                staff_password = ?
                WHERE staff_id = ?");
            $stmt->bind_param("ssssssi", 
                $f_name,
                $l_name,
                $username,
                $email,
                $phone,
                $hashed_password,
                $adminInfo['staff_id']
            );
        } else {
            $stmt = $conn->prepare("UPDATE staffs SET 
                staff_f_name = ?,
                staff_l_name = ?,
                staff_username = ?,
                email = ?,
                phone = ?
                WHERE staff_id = ?");
            $stmt->bind_param("sssssi", 
                $f_name,
                $l_name,
                $username,
                $email,
                $phone,
                $adminInfo['staff_id']
            );
        }

        if ($stmt->execute()) {
            $_SESSION['staff'] = array_merge($_SESSION['staff'], [
                'f_name'   => $f_name,
                'l_name'   => $l_name,
                'username' => $username,
                'email'    => $email,
                'phone'    => $phone
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
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($adminInfo['email']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($adminInfo['username']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Điện thoại</label>
                <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($adminInfo['phone']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Mật khẩu mới</label>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePassModal">
                    Thay đổi mật khẩu
                </button>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Cập nhật
                </button>
            </div>
        </div>
        <input type="hidden" name="password" id="hiddenPassword">
    </form>
</div>

<!-- Modal thay đổi mật khẩu -->
<div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePassModalLabel">Thay đổi mật khẩu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="changePassForm">
          <div class="mb-3 input-group">
            <input type="password" class="form-control" id="new_password_modal" placeholder="Mật khẩu mới" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password_modal')">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <div class="mb-3 input-group">
            <input type="password" class="form-control" id="confirm_password_modal" placeholder="Xác nhận mật khẩu mới" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password_modal')">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <div id="modalPassMessage"></div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    document.getElementById('changePassForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const newPass = document.getElementById('new_password_modal').value;
        const confirmPass = document.getElementById('confirm_password_modal').value;
        const messageDiv = document.getElementById('modalPassMessage');
        messageDiv.innerHTML = '';
        
        if (newPass !== confirmPass) {
            messageDiv.innerHTML = '<div class="alert alert-warning">Mật khẩu không khớp!</div>';
            return;
        }
        document.getElementById('hiddenPassword').value = newPass;
        
        var modalEl = document.getElementById('changePassModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    });
    
    <?php if ($success): ?>
    setTimeout(() => {
        let successModal = document.getElementById('successModal');
        if(successModal){ successModal.style.display = 'none'; }
        window.location.href = "index.php?page=admin&action=dashboard#dashboard";
    }, 2000);
    <?php endif; ?>
    
    <?php if ($error): ?>
    setTimeout(() => {
        let errorModal = document.getElementById('errorModal');
        if(errorModal){ errorModal.style.display = 'none'; }
    }, 2000);
    <?php endif; ?>
</script>

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
<?php endif; ?>
