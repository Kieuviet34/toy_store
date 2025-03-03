<?php
include 'inc/database.php';

// Kiểm tra quyền admin (giả định admin được lưu trong session)
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

// Lấy prod_id từ URL
$prod_id = isset($_GET['prod_id']) ? (int)$_GET['prod_id'] : 0;
if ($prod_id <= 0) {
    echo '<div class="container mt-5"><h1 class="text-center text-danger">Sản phẩm không hợp lệ.</h1></div>';
    exit;
}

// Lấy thông tin sản phẩm hiện tại
$query = "
    SELECT p.prod_id, p.prod_name, p.brand_id, p.cat_id, p.model_year, p.list_price, p.prod_img, b.brand_name, c.cat_name 
    FROM products p 
    JOIN brands b ON p.brand_id = b.brand_id 
    JOIN categories c ON p.cat_id = c.cat_id 
    WHERE p.prod_id = ? AND p.is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $prod_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><h1 class="text-center text-danger">Sản phẩm không tồn tại hoặc đã bị xóa.</h1></div>';
    exit;
}

$product = $result->fetch_assoc();

// Lấy danh sách hãng và danh mục cho dropdown
$brands_query = "SELECT brand_id, brand_name FROM brands";
$brands_result = $conn->query($brands_query);

$categories_query = "SELECT cat_id, cat_name FROM categories";
$categories_result = $conn->query($categories_query);

// Xử lý cập nhật khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_name = trim($_POST['prod_name']);
    $brand_id = (int)$_POST['brand_id'];
    $cat_id = (int)$_POST['cat_id'];
    $model_year = (int)$_POST['model_year'];
    $list_price = (float)$_POST['list_price'];

    // Xử lý upload ảnh
    $prod_img = $product['prod_img']; // Giữ ảnh cũ nếu không upload ảnh mới
    if (isset($_FILES['prod_img']) && $_FILES['prod_img']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['prod_img']['tmp_name'];
        $fileSize = $_FILES['prod_img']['size'];
        $fileType = $_FILES['prod_img']['type'];

        // Kiểm tra định dạng và kích thước file (giới hạn 5MB)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes) && $fileSize <= 5 * 1024 * 1024) {
            $prod_img = file_get_contents($fileTmpPath); // Đọc nội dung file ảnh vào BLOB
        } else {
            $error = "Ảnh không hợp lệ (chỉ chấp nhận JPG, PNG, GIF, tối đa 5MB).";
        }
    }

    if (empty($prod_name) || $brand_id <= 0 || $cat_id <= 0 || $model_year <= 0 || $list_price <= 0) {
        $error = "Vui lòng điền đầy đủ và hợp lệ tất cả các trường.";
    } elseif (!isset($error)) {
        $update_query = "
            UPDATE products 
            SET prod_name = ?, brand_id = ?, cat_id = ?, model_year = ?, list_price = ?, prod_img = ? 
            WHERE prod_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('siiidsi', $prod_name, $brand_id, $cat_id, $model_year, $list_price, $prod_img, $prod_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            header('Location: index.php?page=admin#products');
            exit;
        } else {
            $error = "Không thể cập nhật sản phẩm: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 50px auto;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .error {
            color: #dc3545;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 15px;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
            color: white;
        }
        .current-img {
            max-width: 200px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#dashboard">
                                <i class="bi bi-house me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#orders">
                                <i class="bi bi-package me-2"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#customers">
                                <i class="bi bi-people me-2"></i> Khách hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#staff">
                                <i class="bi bi-person-check me-2"></i> Nhân viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php?page=admin#products">
                                <i class="bi bi-box me-2"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#categories">
                                <i class="bi bi-list-ul me-2"></i> Danh mục
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="form-container">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Cập nhật sản phẩm</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <p class="error"><?php echo htmlspecialchars($error); ?></p>
                            <?php endif; ?>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <label for="prod_name" class="col-sm-3 col-form-label">Tên sản phẩm</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="prod_name" name="prod_name" value="<?php echo htmlspecialchars($product['prod_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="brand_id" class="col-sm-3 col-form-label">Hãng</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="brand_id" name="brand_id" required>
                                            <?php while ($brand = $brands_result->fetch_assoc()): ?>
                                                <option value="<?php echo $brand['brand_id']; ?>" <?php echo $brand['brand_id'] == $product['brand_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="cat_id" class="col-sm-3 col-form-label">Danh mục</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="cat_id" name="cat_id" required>
                                            <?php while ($category = $categories_result->fetch_assoc()): ?>
                                                <option value="<?php echo $category['cat_id']; ?>" <?php echo $category['cat_id'] == $product['cat_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['cat_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="model_year" class="col-sm-3 col-form-label">Năm sản xuất</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="model_year" name="model_year" value="<?php echo htmlspecialchars($product['model_year']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="list_price" class="col-sm-3 col-form-label">Giá (VNĐ)</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" id="list_price" name="list_price" value="<?php echo htmlspecialchars($product['list_price']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="prod_img" class="col-sm-3 col-form-label">Ảnh sản phẩm</label>
                                    <div class="col-sm-9">
                                        <?php if ($product['prod_img']): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['prod_img']); ?>" alt="Ảnh sản phẩm hiện tại" class="current-img mb-2">
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="prod_img" name="prod_img" accept="image/jpeg,image/png,image/gif">
                                        <small class="form-text text-muted">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB.</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Cập nhật</button>
                                    <a href="index.php?page=admin#products" class="btn btn-back"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>