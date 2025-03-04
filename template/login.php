<div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <h2 class="text-center text-dark mt-5"></h2>
        <div class="text-center mb-5 text-dark">Chào mừng quay lại!</div>
        <div class="card my-5">

          <form class="card-body cardbody-color p-lg-5" id="loginForm">

            <div class="text-center">
              <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                width="200px" alt="profile">
            </div>

            <div class="mb-3">
              <input type="text" class="form-control" id="Username" aria-describedby="emailHelp"
                placeholder="Tài khoản (Username)">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" id="password" placeholder="Mặt khẩu">
            </div>
            <div class="text-center"><button type="submit" class="btn btn-color px-5 mb-5 w-100">Đăng nhập</button></div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">Bạn không có tài khoản? <a href="index.php?page=register" class="text-dark fw-bold"> Đăng ký tài khoản ngay!</a>
            <br>
            <a href="index.php?page=forgot_password" class="text-dark fw-bold"> Quên mật khẩu?</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  <script>
    document.getElementById("loginForm").addEventListener("submit",function(e){
          e.preventDefault();

          const username = document.getElementById("Username").value;
          const password = document.getElementById("password").value;

          const loginData = {username, password};

          fetch("src/login_handle.php",{
            method:"POST",
            headers:{
              "Content-Type":"application/json"
            },
            body: JSON.stringify(loginData)
          })
          .then(response => response.json())
          .then(data=>{
              if(data.success){
                window.location.href="index.php?page=home";
                alert("Đăng nhập thành công");
              }else if (data.admin){
                window.location.href="index.php?page=admin";
              }else{
                alert(data.error);
              }
          })
          .catch(err=>{
            alert(err);
          })
    })
  </script>