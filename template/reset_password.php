<?php
include 'inc/database.php';

$email = $_SESSION['reset_email'] ?? (isset($_GET['email']) ? trim($_GET['email']) : '');

if (empty($email)) {
    echo "Không tìm thấy email để reset mật khẩu. Vui lòng quay lại trang forgot password.";
    exit;
}
?>

<div class="rp-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mt-5">Reset Password</h2>
            <form id="resetPasswordForm" class="w-50 mx-auto mt-4">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <div class="input-group">
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                        
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="showPasswordCheckbox">
                    <label class="form-check-label" for="showPasswordCheckbox">Hiện mật khẩu</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                <div id="message" class="mt-3"></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Success -->
<div class="modal fade" id="resetSuccessModal" tabindex="-1" aria-labelledby="resetSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Mật khẩu đã được reset thành công!</h4>
                <p>Chuyển hướng đến trang đăng nhập...</p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    document.getElementById('showPasswordCheckbox').addEventListener('change', function() {
        const newPass = document.getElementById('new_password');
        const confirmPass = document.getElementById('confirm_password');
        newPass.type = confirmPass.type = this.checked ? 'text' : 'password';
    });

    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const messageDiv = document.getElementById('message');
        const email = '<?php echo htmlspecialchars($email); ?>';

        if (newPassword !== confirmPassword) {
            messageDiv.innerHTML = `<div class="alert alert-warning">Mật khẩu mới và xác nhận mật khẩu không khớp.</div>`;
            return;
        }

        if (newPassword.length < 6) {
            messageDiv.innerHTML = `<div class="alert alert-warning">Mật khẩu phải có ít nhất 6 ký tự.</div>`;
            return;
        }

        fetch('src/client/reset_pass.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ email: email, new_password: newPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var modal = new bootstrap.Modal(document.getElementById('resetSuccessModal'));
                modal.show();
                setTimeout(() => { window.location.href = 'index.php?page=login'; }, 3000);
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = `<div class="alert alert-danger">Có lỗi xảy ra. Vui lòng thử lại.</div>`;
        });
    });
</script>

<style>
    .rp-container {
        padding: 20px;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .btn {
        width: 100%;
    }
</style>
