<div class="rp-container" style="padding: 20px;">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="text-center mt-5" style="margin-top: 3rem !important;">Reset Password</h2>
      <form id="resetPasswordForm" class="w-50 mx-auto mt-4" style="margin-top: 1.5rem; width: 50% !important; margin-left: auto; margin-right: auto;">
        <div class="form-group" style="margin-bottom: 1rem;">
          <label for="new_password" style="display: block; margin-bottom: 0.5rem;">New Password:</label>
          <input type="password" id="new_password" name="new_password" class="form-control" required style="border-radius: 0.375rem; padding: 0.375rem 0.75rem; border: 1px solid #ced4da;">
        </div>
        <div class="form-group" style="margin-bottom: 1rem;">
          <label for="confirm_password" style="display: block; margin-bottom: 0.5rem;">Confirm Password:</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" required style="border-radius: 0.375rem; padding: 0.375rem 0.75rem; border: 1px solid #ced4da;">
        </div>
        <!-- Inline error alert container -->
        <div id="message" style="margin-top: 1rem;"></div>
        <button type="submit" class="btn btn-primary btn-block" style="background-color: #0d6efd; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 0.375rem; width: 100%; margin-top: 1rem;">Reset Password</button>
      </form>
    </div>
  </div>
</div>

<!-- Modal Reset Success -->
<div class="modal fade" id="resetSuccessModal" tabindex="-1" aria-labelledby="resetSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
        <h4 class="mt-3">Mật khẩu đã được reset thành công!</h4>
        <p>Chuyển hướng đến trang đăng nhập...</p>
      </div>
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
              <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem;">
                  Mật khẩu mới và xác nhận mật khẩu không khớp.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `;
          return;
      }
      
      if (newPassword.length < 6) {
          messageDiv.innerHTML = `
              <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem;">
                  Mật khẩu phải có ít nhất 6 ký tự.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `;
          return;
      }
      
      fetch('src/client/reset_pass.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ email: email, new_password: newPassword })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              var modal = new bootstrap.Modal(document.getElementById('resetSuccessModal'));
              modal.show();
              setTimeout(() => window.location.href = 'index.php?page=login', 3000);
          } else {
              messageDiv.innerHTML = `
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem;">
                      ${data.error}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
              `;
          }
      })
      .catch(error => {
          console.error('Error:', error);
          messageDiv.innerHTML = `
              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 1rem; border-radius: 0.375rem; padding: 0.75rem 1.25rem;">
                  Có lỗi xảy ra. Vui lòng thử lại.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          `;
      });
  });
</script>
