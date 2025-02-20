<?php
// admin.php
include 'inc/database.php';

// Lấy số liệu thống kê cho Dashboard (chỉ lấy những dòng chưa bị xóa nếu có)
$queryTotalOrders = "SELECT COUNT(*) as total_orders FROM orders WHERE is_deleted = 0";
$resultOrders = $conn->query($queryTotalOrders);
$rowOrders = $resultOrders->fetch_assoc();
$totalOrders = $rowOrders['total_orders'];

$queryTotalCustomers = "SELECT COUNT(*) as total_customers FROM customers"; // Giả sử customers không dùng soft delete
$resultCustomers = $conn->query($queryTotalCustomers);
$rowCustomers = $resultCustomers->fetch_assoc();
$totalCustomers = $rowCustomers['total_customers'];

$queryTotalStaffs = "SELECT COUNT(*) as total_staffs FROM staffs WHERE is_deleted = 0";
$resultStaffs = $conn->query($queryTotalStaffs);
$rowStaffs = $resultStaffs->fetch_assoc();
$totalStaffs = $rowStaffs['total_staffs'];

$queryTotalProducts = "SELECT COUNT(*) as total_products FROM products WHERE is_deleted = 0";
$resultProducts = $conn->query($queryTotalProducts);
$rowProducts = $resultProducts->fetch_assoc();
$totalProducts = $rowProducts['total_products'];

$queryTotalCategories = "SELECT COUNT(*) as total_categories FROM categories";
$resultCategories = $conn->query($queryTotalCategories);
$rowCategories = $resultCategories->fetch_assoc();
$totalCategories = $rowCategories['total_categories'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="template/Admin/dashboard.css" rel="stylesheet">
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
      .badge { font-size: 0.9rem; }
      .btn { font-size: 0.8rem; }
    </style>
  </head>
  <body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">AllainStore Admin</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-nav">
        <div class="nav-item text-nowrap">
          <a class="nav-link px-3" href="#">Sign out</a>
        </div>
      </div>
    </header>

    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
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
              <li class="nav-item">
                <a class="nav-link" href="#products" data-bs-toggle="tab">
                  <span data-feather="box"></span>
                  Sản phẩm
                </a>
              </li>
              <!-- Thêm mục Categories -->
              <li class="nav-item">
                <a class="nav-link" href="#categories" data-bs-toggle="tab">
                  <span data-feather="list"></span>
                  Danh mục
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <div class="tab-content">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard">
              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Tổng quan</h1>
              </div>
              <div class="row">
                <!-- Thống kê đơn hàng -->
                <div class="col-md-3">
                  <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Đơn hàng</h5>
                      <p class="card-text"><?php echo number_format($totalOrders, 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
                <!-- Thống kê khách hàng -->
                <div class="col-md-3">
                  <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Khách hàng</h5>
                      <p class="card-text"><?php echo number_format($totalCustomers, 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
                <!-- Thống kê nhân viên -->
                <div class="col-md-3">
                  <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Nhân viên</h5>
                      <p class="card-text"><?php echo number_format($totalStaffs, 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
                <!-- Thống kê sản phẩm -->
                <div class="col-md-3">
                  <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Sản phẩm</h5>
                      <p class="card-text"><?php echo number_format($totalProducts, 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
                <!-- Thống kê danh mục -->
                <div class="col-md-3">
                  <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                      <h5 class="card-title">Danh mục</h5>
                      <p class="card-text"><?php echo number_format($totalCategories, 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
              </div>
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
                    <?php
                    $queryOrdersList = "SELECT * FROM orders WHERE is_deleted = 0 ORDER BY order_date ASC";
                    $resultOrdersList = $conn->query($queryOrdersList);
                    if ($resultOrdersList && $resultOrdersList->num_rows > 0) {
                      while ($order = $resultOrdersList->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>#" . htmlspecialchars($order['order_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['customer_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                        echo "<td>" . number_format($order['order_status'] ? $order['order_status'] : 0, 0, ',', '.') . "₫</td>";
                        echo "<td>";
                        if ($order['order_status'] == 1) {
                          echo '<span class="badge bg-success">Đã giao</span>';
                        } else {
                          echo '<span class="badge bg-warning">Chưa giao</span>';
                        }
                        echo "</td>";
                        echo "<td>
                                <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editOrderModal'>
                                  <span data-feather='edit'></span> Sửa
                                </button>
                                <button class='btn btn-sm btn-danger btn-delete' data-type='order' data-id='" . $order['order_id'] . "'>
                                  <span data-feather='trash-2'></span> Xóa
                                </button>
                              </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6'>Không có đơn hàng nào.</td></tr>";
                    }
                    ?>
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
                    <?php
                    $queryCustomersList = "SELECT * FROM customers ORDER BY customer_id ASC";
                    $resultCustomersList = $conn->query($queryCustomersList);
                    if ($resultCustomersList && $resultCustomersList->num_rows > 0) {
                      while ($customer = $resultCustomersList->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($customer['customer_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($customer['f_name'] . ' ' . $customer['l_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($customer['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($customer['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($customer['city']) . "</td>";
                        echo "<td>
                                <button class='btn btn-sm btn-warning'>
                                  <span data-feather='edit'></span> Sửa
                                </button>
                                <button class='btn btn-sm btn-danger btn-delete' data-type='customer' data-id='" . $customer['customer_id'] . "'>
                                  <span data-feather='trash-2'></span> Xóa
                                </button>
                              </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6'>Không có khách hàng nào.</td></tr>";
                    }
                    ?>
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
                    <?php
                    $queryStaffList = "SELECT * FROM staffs WHERE is_deleted = 0 ORDER BY staff_id ASC";
                    $resultStaffList = $conn->query($queryStaffList);
                    if ($resultStaffList && $resultStaffList->num_rows > 0) {
                      while ($staff = $resultStaffList->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($staff['staff_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($staff['staff_f_name'] . ' ' . $staff['staff_l_name']) . "</td>";
                        echo "<td>" . "Quản lý" . "</td>";  // Placeholder cho vai trò
                        echo "<td>" . htmlspecialchars($staff['email']) . "</td>";
                        echo "<td>";
                        if ($staff['is_active'] == 1) {
                          echo '<span class="badge bg-success">Hoạt động</span>';
                        } else {
                          echo '<span class="badge bg-secondary">Không hoạt động</span>';
                        }
                        echo "</td>";
                        echo "<td>
                                <button class='btn btn-sm btn-warning'>
                                  <span data-feather='edit'></span> Sửa
                                </button>
                                <button class='btn btn-sm btn-danger btn-delete' data-type='staff' data-id='" . $staff['staff_id'] . "'>
                                  <span data-feather='trash-2'></span> Xóa
                                </button>
                              </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6'>Không có nhân viên nào.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Products Tab -->
            <div class="tab-pane fade" id="products">
              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản lý sản phẩm</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <span data-feather="plus"></span> Thêm sản phẩm
                  </button>
                </div>
              </div>
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tên sản phẩm</th>
                      <th>Hãng</th>
                      <th>Năm sản xuất</th>
                      <th>Giá</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $queryProductsList = "SELECT * FROM products WHERE is_deleted = 0 ORDER BY prod_id ASC";
                    $resultProductsList = $conn->query($queryProductsList);
                    if ($resultProductsList && $resultProductsList->num_rows > 0) {
                      while ($product = $resultProductsList->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($product['prod_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['prod_name']) . "</td>";
                        echo "<td>" . "Placeholder" . "</td>"; // Nếu cần join bảng brands để lấy tên hãng
                        echo "<td>" . htmlspecialchars($product['model_year']) . "</td>";
                        echo "<td>" . number_format($product['list_price'], 0, ',', '.') . "₫</td>";
                        echo "<td>
                                <button class='btn btn-sm btn-warning'>
                                  <span data-feather='edit'></span> Sửa
                                </button>
                                <button class='btn btn-sm btn-danger btn-delete' data-type='product' data-id='" . $product['prod_id'] . "'>
                                  <span data-feather='trash-2'></span> Xóa
                                </button>
                              </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6'>Không có sản phẩm nào.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Categories Tab -->
            <div class="tab-pane fade" id="categories">
              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Quản lý danh mục</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <span data-feather="plus"></span> Thêm danh mục
                  </button>
                </div>
              </div>
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Tìm kiếm danh mục...">
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tên danh mục</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $queryCategoriesList = "SELECT * FROM categories ORDER BY cat_id ASC";
                    $resultCategoriesList = $conn->query($queryCategoriesList);
                    if ($resultCategoriesList && $resultCategoriesList->num_rows > 0) {
                      while ($category = $resultCategoriesList->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($category['cat_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($category['cat_name']) . "</td>";
                        echo "<td>
                                <button class='btn btn-sm btn-warning'>
                                  <span data-feather='edit'></span> Sửa
                                </button>
                                <button class='btn btn-sm btn-danger btn-delete' data-type='category' data-id='" . $category['cat_id'] . "'>
                                  <span data-feather='trash-2'></span> Xóa
                                </button>
                              </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='3'>Không có danh mục nào.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div><!-- end tab-content -->
        </main>
      </div>
    </div>

    <!-- Modal ví dụ: Thêm đơn hàng (các modal khác tương tự) -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Thêm đơn hàng mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="orderCustomer" class="form-label">Tên khách hàng</label>
                <input type="text" class="form-control" id="orderCustomer">
              </div>
              <!-- Các trường khác nếu cần -->
              <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Các modal khác (Thêm khách hàng, Thêm nhân viên, Thêm sản phẩm, Thêm danh mục) cũng có thể được thêm tương tự -->

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
      feather.replace();
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm("Bạn có chắc chắn muốn xóa?")) {
              var type = this.getAttribute('data-type');
              var id = this.getAttribute('data-id');
              fetch("template/Admin/admindelete.php?type=" + encodeURIComponent(type) + "&id=" + encodeURIComponent(id))
                .then(response => response.json())
                .then(data => {
                  if(data.status === "success") {
                    var row = this.closest("tr");
                    if(row) row.remove();
                  } else {
                    alert("Xóa không thành công: " + data.error);
                  }
                })
                .catch(err => {
                  alert("Có lỗi xảy ra: " + err);
                });
            }
          });
        });
      });
    </script>
  </body>
</html>
