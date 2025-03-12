<?php
include 'inc/database.php';

// Kiểm tra quyền truy cập admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

// Xử lý tham số action, mặc định là 'dashboard'
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Các truy vấn tổng quan cho dashboard
$queryTotalOrders = "SELECT COUNT(*) as total_orders FROM orders WHERE is_deleted = 0";
$resultOrders = $conn->query($queryTotalOrders);
$rowOrders = $resultOrders->fetch_assoc();
$totalOrders = $rowOrders['total_orders'];

$queryTotalCustomers = "SELECT COUNT(*) as total_customers FROM customers WHERE is_deleted = 0";
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

$queryTotalBrands = "SELECT COUNT(*) as total_brands FROM brands";
$resultBrands = $conn->query($queryTotalBrands);
$rowBrands = $resultBrands->fetch_assoc();
$totalBrands = $rowBrands['total_brands'];

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom styles -->
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
        .form-container {
            max-width: 700px;
            margin: 50px auto;
        }
        .card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1rem;
        }
        .error {
            color: #dc3545;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .form-control, .form-select, .form-check-input, .form-check-label {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }
        .d-flex.justify-content-end.gap-2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">AllainStore Admin</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="index.php?page=logout">Sign out</a>
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
                            <a class="nav-link <?php echo ($action == 'dashboard') ? 'active' : ''; ?>" href="index.php?page=admin&action=dashboard#dashboard">
                                <span data-feather="home"></span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($action == 'orders') ? 'active' : ''; ?>" href="index.php?page=admin&action=orders#orders">
                                <span data-feather="package"></span>
                                Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($action == 'customers') ? 'active' : ''; ?>" href="index.php?page=admin&action=customers#customers">
                                <span data-feather="users"></span>
                                Khách hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($action == 'staff') ? 'active' : ''; ?>" href="index.php?page=admin&action=staff#staff">
                                <span data-feather="user-check"></span>
                                Nhân viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($action == 'products') ? 'active' : ''; ?>" href="index.php?page=admin&action=products#products">
                                <span data-feather="box"></span>
                                Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($action == 'brands') ? 'active' : ''; ?>" href="index.php?page=admin&action=brands#brands">
                                <span data-feather="box"></span>
                                Hãng sản xuất
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($action == 'categories') ? 'active' : ''; ?>" href="index.php?page=admin&action=categories#categories">
                                <span data-feather="list"></span>
                                Danh mục
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php
                // Danh sách các action đặc biệt
                $specialActions = [
                    'update_product',
                    'update_order',
                    'update_customer',
                    'update_staff',
                    'update_category',
                    'add_product',
                    'add_staff',
                    'add_customer',
                    'add_category',
                    'add_brand',
                    'update_brand',
                    'mass_receiving'
                ];

                if (in_array($action, $specialActions)) {
                    // Xử lý các action đặc biệt
                    if ($action == 'update_product') {
                        include 'src/admin/update_product.php';
                    } elseif ($action == 'update_order') {
                        include 'src/admin/update_order.php';
                    } elseif ($action == 'update_customer') {
                        include 'src/admin/update_customer.php';
                    } elseif ($action == 'update_staff') {
                        include 'src/admin/update_staff.php';
                    } elseif ($action == 'update_category') {
                        include 'src/admin/update_category.php';
                    } elseif ($action == 'add_product') {
                        include 'src/admin/addproduct.php';
                    } elseif ($action == 'add_staff') {
                        include 'src/admin/addstaff.php';
                    } elseif ($action == 'add_customer') {
                        include 'src/admin/addcustomer.php';
                    } elseif ($action == 'add_category') {
                        include 'src/admin/addcat.php';
                    }elseif ($action == 'mass_receiving'){
                        include 'src/admin/mass_receiving.php';
                    }elseif($action == 'add_brand'){
                        include 'src/admin/addbrand.php';
                    }elseif($action == 'update_brand'){
                        include 'src/admin/update_brand.php';
                    }
                } else {
                ?>
                    <div class="tab-content">
                        <!-- Dashboard Tab -->
                        <div class="tab-pane fade <?php echo ($action == 'dashboard' || $action == '') ? 'show active' : ''; ?>" id="dashboard">
                            <?php include 'template/Admin/dashboard.php'; ?>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab-pane fade <?php echo ($action == 'orders') ? 'show active' : ''; ?>" id="orders">
                            <?php include 'template/Admin/orders.php'; ?>
                        </div>

                        <!-- Customers Tab -->
                        <div class="tab-pane fade <?php echo ($action == 'customers') ? 'show active' : ''; ?>" id="customers">
                            <?php include 'template/Admin/customers.php'; ?>
                        </div>

                        <!-- Staff Tab -->
                        <div class="tab-pane fade <?php echo ($action == 'staff') ? 'show active' : ''; ?>" id="staff">
                            <?php include 'template/Admin/staff.php'; ?>
                        </div>

                        <!-- Products Tab -->
                        <div class="tab-pane fade <?php echo ($action == 'products') ? 'show active' : ''; ?>" id="products">
                            <?php include 'template/Admin/products.php'; ?>
                        </div>

                        <!-- Categories Tab -->
                        <div class="tab-pane fade <?php echo ($action == 'categories') ? 'show active' : ''; ?>" id="categories">
                            <?php include 'template/Admin/categories.php'; ?>
                        </div>
                        <!-- Brands tab -->
                        <div class="tab-pane fade <?php echo ($action == 'brands') ? 'show active' : ''; ?>" id="brands">
                            <?php include 'template/Admin/brands.php'; ?>
                        </div>
                    </div><!-- end tab-content -->
                <?php
                }
                ?>
            </main>
        </div>
    </div>

    <!-- Modal ví dụ: Thêm đơn hàng -->
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
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
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
                    if (confirm("Bạn có chắc chắn muốn xóa?")) {
                        var type = this.getAttribute('data-type');
                        var id = this.getAttribute('data-id');
                        fetch("src/admin/admindelete.php?type=" + encodeURIComponent(type) + "&id=" + encodeURIComponent(id))
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === "success") {
                                    var row = this.closest("tr");
                                    if (row) row.remove();
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