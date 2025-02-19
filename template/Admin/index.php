<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Dashboard Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    

    <!-- Bootstrap core CSS -->
<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>
  <body>    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">AllainStore Admin</a>
  <!-- Phần header giữ nguyên -->
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#orders" data-bs-toggle="tab">
              <span data-feather="package"></span>
              Đơn hàng
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#customers" data-bs-toggle="tab">
              <span data-feather="users"></span>
              Khách hàng
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#staff" data-bs-toggle="tab">
              <span data-feather="user-check"></span>
              Nhân viên
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="tab-content">
        
        <!-- Dashboard Tab -->
        <div class="tab-pane fade show active" id="dashboard">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tổng quan</h1>
          </div>
          <!-- Thêm các thống kê tổng quan ở đây -->
        </div>

        <!-- Orders Tab -->
        <div class="tab-pane fade" id="orders">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Quản lý đơn hàng</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                <span data-feather="plus"></span> Thêm đơn hàng
              </button>
            </div>
          </div>

          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Tìm kiếm đơn hàng...">
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Mã đơn</th>
                  <th>Khách hàng</th>
                  <th>Ngày đặt</th>
                  <th>Tổng tiền</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <!-- Dummy Data -->
                <tr>
                  <td>#1001</td>
                  <td>Nguyễn Văn A</td>
                  <td>2024-03-20</td>
                  <td>1,250,000₫</td>
                  <td><span class="badge bg-success">Đã giao</span></td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                      <span data-feather="edit"></span>
                    </button>
                    <button class="btn btn-sm btn-danger">
                      <span data-feather="trash-2"></span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Customers Tab -->
        <div class="tab-pane fade" id="customers">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Quản lý khách hàng</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <span data-feather="plus"></span> Thêm khách hàng
              </button>
            </div>
          </div>

          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Tìm kiếm khách hàng...">
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tên</th>
                  <th>Email</th>
                  <th>SĐT</th>
                  <th>Địa chỉ</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <!-- Dummy Data -->
                <tr>
                  <td>1</td>
                  <td>Trần Thị B</td>
                  <td>customer@example.com</td>
                  <td>0912345678</td>
                  <td>Hà Nội</td>
                  <td>
                    <button class="btn btn-sm btn-warning">
                      <span data-feather="edit"></span>
                    </button>
                    <button class="btn btn-sm btn-danger">
                      <span data-feather="trash-2"></span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Staff Tab -->
        <div class="tab-pane fade" id="staff">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Quản lý nhân viên</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                <span data-feather="plus"></span> Thêm nhân viên
              </button>
            </div>
          </div>

          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Tìm kiếm nhân viên...">
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Họ tên</th>
                  <th>Vai trò</th>
                  <th>Email</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <!-- Dummy Data -->
                <tr>
                  <td>NV001</td>
                  <td>Lê Văn C</td>
                  <td>Quản lý</td>
                  <td>staff@example.com</td>
                  <td><span class="badge bg-success">Hoạt động</span></td>
                  <td>
                    <button class="btn btn-sm btn-warning">
                      <span data-feather="edit"></span>
                    </button>
                    <button class="btn btn-sm btn-danger">
                      <span data-feather="trash-2"></span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<!-- Modals -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm đơn hàng mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Form thêm đơn hàng -->
      </div>
    </div>
  </div>
</div>

<!-- Các modal khác tương tự -->

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Khởi tạo Feather Icons
  feather.replace();
</script>
</body>
</html>