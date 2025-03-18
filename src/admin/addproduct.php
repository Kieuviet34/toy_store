<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$brands_query = "SELECT brand_id, brand_name FROM brands";
$brands_result = $conn->query($brands_query);

$categories_query = "SELECT cat_id, cat_name FROM categories";
$categories_result = $conn->query($categories_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_name = trim($_POST['prod_name']);
    $brand_id = (int) $_POST['brand_id'];
    $cat_id = (int) $_POST['cat_id'];
    $model_year = (int) $_POST['model_year'];
    $list_price = (float) $_POST['list_price'];

    $prod_img = null;
    if (isset($_FILES['prod_img']) && $_FILES['prod_img']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['prod_img']['tmp_name'];
        $fileSize = $_FILES['prod_img']['size'];
        $fileType = $_FILES['prod_img']['type'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes) && $fileSize <= 5 * 1024 * 1024) {
            $prod_img = file_get_contents($fileTmpPath);
        } else {
            $error = "Ảnh không hợp lệ (chỉ chấp nhận JPG, PNG, GIF, tối đa 5MB).";
        }
    }

    if (empty($prod_name) || $brand_id <= 0 || $cat_id <= 0 || $model_year <= 0 || $list_price <= 0) {
        $error = "Vui lòng điền đầy đủ và hợp lệ tất cả các trường.";
    } elseif (!isset($error)) {
        $insert_query = "
            INSERT INTO products (prod_name, prod_img, brand_id, cat_id, model_year, list_price, is_deleted) 
            VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            $error = "Prepare error: " . $conn->error;
        } else {
            $stmt->bind_param('ssiiid', $prod_name, $prod_img, $brand_id, $cat_id, $model_year, $list_price);
            if ($stmt->execute()) {
                echo '<div class="alert alert-success" role="alert">Thêm sản phẩm thành công!</div>';
                echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin&action=products#products"; }, 2000);</script>';
                exit;
            } else {
                $error = "Không thể thêm sản phẩm: " . $stmt->error;
            }
        }
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm sản phẩm</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="prod_name" class="col-sm-3 col-form-label">Tên sản phẩm</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="prod_name" name="prod_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="brand_id" class="col-sm-3 col-form-label">Hãng</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="brand_id" name="brand_id" required>
                            <?php while ($brand = $brands_result->fetch_assoc()): ?>
                                <option value="<?php echo $brand['brand_id']; ?>">
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
                                <option value="<?php echo $category['cat_id']; ?>">
                                    <?php echo htmlspecialchars($category['cat_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="model_year" class="col-sm-3 col-form-label">Năm sản xuất</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="model_year" name="model_year" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="list_price" class="col-sm-3 col-form-label">Giá (VNĐ)</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control" id="list_price" name="list_price" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="prod_img" class="col-sm-3 col-form-label">Ảnh sản phẩm</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="prod_img" name="prod_img" accept="image/jpeg,image/png,image/gif" required>
                        <small class="form-text text-muted">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB.</small>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                    <a href="index.php?page=admin&action=products#products" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
