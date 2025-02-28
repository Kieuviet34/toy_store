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
                  <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Họ</label>
                  <input type="text" name="last_name" class="form-control" required>
                </div>
              </div>

              <!-- Contact Info Row -->
              <div class="mb-3">
                
                  <label class="form-label">Email</label>
                  <div class="input-group mb-3">
                    <input type="text" name="email" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2">@example.com</span>
                    </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Số điện thoại</label>
                  <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}" required>
                </div>
              

              <!-- Address Row -->
              <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" required>
              </div>

              <!-- City/Zip Row -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">Thành Phố</label>
                  <input type="text" name="city" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mã Zip</label>
                  <input type="text" name="zip" class="form-control" pattern="[0-9]{5}" required>
                </div>
              </div>

              <!-- Password Section -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">Mật khẩu</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Xác nhận lại mật khẩu</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Hiện mật khẩu
                </label>
                </div>
            <br>
              <!-- Submit Button -->
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"C>Tạo tài khoản</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

<script>
  async function hashPassword(password){
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b=>b.toString(16).padStart(2,'0')).join('');
    return hashHex;
  }
  document.getElementById("registerForm").addEventListener("submit", e=>{
    e.preventDefault();
    const firstName = document.querySelector("input[name='first_name']").value;
      const lastName = document.querySelector("input[name='last_name']").value;
      const email = document.querySelector("input[name='email']").value;
      const phone = document.querySelector("input[name='phone']").value;
      const address = document.querySelector("input[name='address']").value;
      const city = document.querySelector("input[name='city']").value;
      const zip = document.querySelector("input[name='zip']").value;
      const password = document.querySelector("input[name='password']").value;
      const confirmPassword = document.querySelector("input[name='confirm_password']").value;
      
  })
</script>