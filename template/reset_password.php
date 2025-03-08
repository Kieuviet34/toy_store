<?php
include 'inc/database.php';

$email = $_SESSION['reset_email'] ?? (isset($_GET['email']) ? trim($_GET['email']) : '');

if (empty($email)) {
    echo "Không tìm thấy email để reset mật khẩu. Vui lòng quay lại trang forgot password.";
    exit;
}

$success = false;
$error = '';

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $success = true;
    } elseif ($_GET['status'] === 'error') {
        $error = "Có lỗi xảy ra khi reset mật khẩu. Vui lòng thử lại.";
    }
}
?>

<div class="rp-container" style="padding: 20px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mt-5" style="margin-top: 3rem !important;">Reset Password</h2>
            <form id="resetPasswordForm" class="w-50 mx-auto mt-4" style="margin-top: 1.5rem; width: 50% !important; margin-left: auto !important; margin-right: auto !important;">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="new_password" style="display: block; margin-bottom: 0.5rem;">New Password:</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required style="border-radius: 0.375rem; padding: 0.375rem 0.75rem; border: 1px solid #ced4da;">
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="confirm_password" style="display: block; margin-bottom: 0.5rem;">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required style="border-radius: 0.375rem; padding: 0.375rem 0.75rem; border: 1px solid #ced4da;">
                </div>
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                        Mật khẩu đã được reset thành công. Vui lòng quay lại trang đăng nhập.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                    </div>
                    <script>
                        setTimeout(() => window.location.href = 'index.php?page=login', 3000);
                    </script>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary btn-block" style="background-color: #0d6efd; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 0.375rem; width: 100%; margin-top: 1rem;">Reset Password</button>
                <div id="message" style="margin-top: 1rem;"></div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const messageDiv = document.getElementById('message');
        const email = '<?php echo htmlspecialchars($email); ?>'; 
        if (newPassword !== confirmPassword) {
            messageDiv.innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #fff3cd; border-color: #ffeeba; color: #856404;">
                    Mật khẩu mới và xác nhận mật khẩu không khớp.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                </div>
            `;
            return;
        }

        if (newPassword.length < 6) {
            messageDiv.innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #fff3cd; border-color: #ffeeba; color: #856404;">
                    Mật khẩu phải có ít nhất 6 ký tự.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                </div>
            `;
            return;
        }

        fetch('src/reset_pass.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, new_password: newPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
                        Mật khẩu đã được reset thành công. Chuyển hướng đến trang đăng nhập...
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                    </div>
                `;
                setTimeout(() => window.location.href = 'index.php?page=login', 3000);
            } else {
                messageDiv.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                        ${data.error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                    Có lỗi xảy ra. Vui lòng thử lại.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                </div>
            `;
        });
    });
</script>