<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <h2 class="text-center text-dark mt-5"></h2>
            <div class="text-center mb-5 text-dark">Chào mừng quay lại!</div>
            <div class="card my-5">
                <form class="card-body cardbody-color p-lg-5" id="loginForm">
                    <div id="alert-container"></div>
                    <div class="text-center">
                        <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px" alt="profile">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="Username" placeholder="Tài khoản (Username)">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Hiện mật khẩu
                        </label>
                    </div><br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-dark px-5 mb-5 w-100">Đăng nhập</button>
                    </div>
                    <div id="emailHelp" class="form-text text-center mb-5 text-dark">
                        Bạn không có tài khoản? <a href="index.php?page=register" class="text-dark fw-bold">Đăng ký tài khoản ngay!</a>
                        <br>
                        <a href="index.php?page=forgot_password" class="text-dark fw-bold">Quên mật khẩu?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal đăng nhập thành công -->
<div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
        <h4 class="mt-3">Đăng nhập thành công!</h4>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const username = document.getElementById("Username").value;
    const password = document.getElementById("password").value;
    const loginData = { username, password };

    fetch("src/login_handle.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(loginData)
    })
    .then(response => response.json())
    .then(data => {
        const alertContainer = document.getElementById("alert-container");
        alertContainer.innerHTML = "";

        if (data.success) {
            // Nếu đăng nhập thành công, hiển thị modal với icon tick
            var modal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
            modal.show();
            setTimeout(() => {
                window.location.href = "index.php?page=home";
            }, 2000);
        } else if (data.admin) {
            var modal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
            modal.show();
            setTimeout(() => {
                window.location.href = "index.php?page=admin";
            }, 2000);
        } else {
            const errorAlert = document.createElement("div");
            errorAlert.className = "alert alert-warning alert-dismissible fade show";
            errorAlert.role = "alert";
            errorAlert.innerHTML = data.error;
            alertContainer.appendChild(errorAlert);

            setTimeout(() => {
                errorAlert.classList.remove("show");
                errorAlert.classList.add("hide");
                setTimeout(() => {
                    alertContainer.removeChild(errorAlert);
                }, 500);
            }, 2000);
        }
    })
    .catch(err => {
        const alertContainer = document.getElementById("alert-container");
        const errorAlert = document.createElement("div");
        errorAlert.className = "alert alert-warning alert-dismissible fade show";
        errorAlert.role = "alert";
        errorAlert.innerHTML = "Đã xảy ra lỗi khi kết nối đến server";
        alertContainer.appendChild(errorAlert);
        setTimeout(() => {
            errorAlert.classList.remove("show");
            errorAlert.classList.add("hide");
            setTimeout(() => {
                alertContainer.removeChild(errorAlert);
            }, 500);
        }, 2000);
    });
});

document.getElementById("flexCheckDefault").addEventListener("change", function(){
    const passwordField = document.getElementById("password");
    passwordField.type = this.checked ? "text" : "password";
});
</script>
