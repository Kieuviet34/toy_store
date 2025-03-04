<div class="rp-container" style="padding: 20px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mt-5" style="margin-top: 3rem !important;">Forgot Password</h2>
            <form id="forgotPasswordForm" class="mt-4" style="margin-top: 1.5rem;">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem;">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required style="border-radius: 0.375rem; padding: 0.375rem 0.75rem; border: 1px solid #ced4da;">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #fff3cd; border-color: #ffeeba; color: #856404;">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; right: 1rem; top: 0.75rem;"></button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="background-color: #0d6efd; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 0.375rem; width: 100%;">Reset Password</button>
                <div id="message" style="margin-top: 1rem;"></div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const messageDiv = document.getElementById('message');

        fetch("src/check_mail.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (confirm("Bạn có muốn tiếp tục đến trang reset mật khẩu?")) {
                    window.location.href = 'index.php?page=reset_password';
                }
            } else {
                messageDiv.innerHTML = `
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem; background-color: #fff3cd; border-color: #ffeeba; color: #856404;">
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