<?php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user']['customer_id'];
?>

<div class="change-password-container">
    <h2>Đổi Mật Khẩu</h2>
    <form id="changePasswordForm" method="POST">
        <div class="mb-3">
            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
            <div class="input-group">
                <input type="password" class="form-control" id="current_password" name="current_password" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới</label>
            <div class="input-group">
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đổi mật khẩu</button>
    </form>
</div>

<!-- Modal thông báo -->
<div id="successModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalMessage"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            current_password: document.getElementById('current_password').value,
            new_password: document.getElementById('new_password').value,
            confirm_password: document.getElementById('confirm_password').value
        };

        fetch('src/client/change_pass.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            // Hiển thị thông báo trong modal
            document.getElementById('modalMessage').textContent = data.message;
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
            // Nếu thành công, chuyển hướng sau 2 giây
            if (data.success) {
                setTimeout(() => window.location.href = 'index.php?page=info', 2000);
            }
        })
        .catch(error => alert('Lỗi: ' + error.message));
    });
</script>

<style>
    .change-password-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .change-password-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #007bff;
    }
</style>
