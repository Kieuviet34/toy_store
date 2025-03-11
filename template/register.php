<div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4 ">
        <div class="card my-5">
          <div class="card-body px-lg-8">
            <h2 class="text-center mb-4">Tạo tài khoản</h2>

            <!-- Profile Image -->
            <div class="text-center mb-4">
              <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png" 
                   class="img-fluid profile-image-pic img-thumbnail rounded-circle"
                   width="200px" 
                   alt="profile">
            </div>

            <!-- Form Start -->
            <form id="registerForm">
              <!-- Name Row -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Tên</label>
                  <input type="text" name="first_name" class="form-control" id="fname" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Họ</label>
                  <input type="text" name="last_name" class="form-control" id="lname" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
              </div>
              <!-- Contact Info Row -->
              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group mb-3">
                  <input type="text" name="email" class="form-control" id="email" required>
                  <span class="input-group-text">@</span>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="tel" name="phone" class="form-control" id="phone" pattern="[0-9]{10}" required>
              </div>

              <!-- Address Row -->
              <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" id="address" required>
              </div>

              <!-- City/Zip Row -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">Thành phố</label>
                  <input type="text" name="city" class="form-control" id="city" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mã Zip</label>
                  <input type="text" name="zip" class="form-control" id="zip" pattern="[0-9]{5}" required>
                </div>
              </div>

              <!-- Password Section -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Mật khẩu</label>
                  <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Xác nhận mật khẩu</label>
                  <input type="password" name="confirm_password" id="confirm_pass" class="form-control" required>
                </div>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Hiện mật khẩu
                </label>
              </div>
              <!-- Submit Button -->
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Tạo tài khoản</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- Modal thông báo đăng ký thành công -->
<div class="modal fade" id="registerSuccessModal" tabindex="-1" aria-labelledby="registerSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
        <h4 class="mt-3">Tạo tài khoản thành công!</h4>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById("registerForm").addEventListener("submit", e => {
    e.preventDefault();
    const first_name = document.getElementById("fname").value;
    const last_name  = document.getElementById("lname").value;
    const username   = document.getElementById("username").value;
    const email      = document.getElementById("email").value;
    const phone      = document.getElementById("phone").value;
    const address    = document.getElementById("address").value;
    const city       = document.getElementById("city").value;
    const zip        = document.getElementById("zip").value;
    const password   = document.getElementById("password").value;
    const confirm_password = document.getElementById("confirm_pass").value;
      
    if(password !== confirm_password) {
      alert("Mật khẩu không khớp!");
      return;
    }
      
    const data = {
      first_name,
      last_name,
      username,
      email,
      phone,
      address,
      city,
      zip,
      password,
      confirm_password
    };

    fetch("src/create_account.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
      if(data.success){
        var modal = new bootstrap.Modal(document.getElementById('registerSuccessModal'));
        modal.show();
        setTimeout(() => {
            window.location.href = "index.php?page=login";
        }, 2000);
      } else {
        alert(data.error);
      }
    })
    .catch(err => {
      console.log(err);
      alert(err);
    });
  });

  document.getElementById("flexCheckDefault").addEventListener("change", function(){
    const passwordFields = document.querySelectorAll("input[name='password'], input[name='confirm_password']");
    passwordFields.forEach(field => {
      field.type = this.checked ? "text" : "password";
    });
  });
</script>
