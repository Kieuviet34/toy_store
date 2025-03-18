<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    $file = fopen($fileTmpPath, 'r');
    fgetcsv($file); 

    while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
        $prod_name = trim($row[0]);        // Tên sản phẩm
        $prod_img_path = trim($row[1]);    // Đường dẫn hình ảnh
        $brand_name = trim($row[2]);       // Tên hãng
        $cat_name = trim($row[3]);         // Tên danh mục
        $model_year = (int)$row[4];        // Năm sản xuất
        $list_price = (float)$row[5];      // Giá niêm yết

        // Kiểm tra dữ liệu hợp lệ
        if (empty($prod_name) || empty($prod_img_path) || empty($brand_name) || empty($cat_name) || $model_year <= 0 || $list_price <= 0) {
            continue; 
        }

        $brand_query = "SELECT brand_id FROM brands WHERE brand_name = ?";
        $stmt_brand = $conn->prepare($brand_query);
        $stmt_brand->bind_param('s', $brand_name);
        $stmt_brand->execute();
        $brand_result = $stmt_brand->get_result();
        if ($brand_result->num_rows == 0) {
            continue; 
        }
        $brand_id = $brand_result->fetch_assoc()['brand_id'];

        $cat_query = "SELECT cat_id FROM categories WHERE cat_name = ?";
        $stmt_cat = $conn->prepare($cat_query);
        $stmt_cat->bind_param('s', $cat_name);
        $stmt_cat->execute();
        $cat_result = $stmt_cat->get_result();
        if ($cat_result->num_rows == 0) {
            continue; 
        }
        $cat_id = $cat_result->fetch_assoc()['cat_id'];

        // Đọc dữ liệu hình ảnh từ đường dẫn
        $prod_img = file_get_contents($prod_img_path);
        if ($prod_img === false) {
            continue; 
        }

        $insert_query = "INSERT INTO products (prod_name, prod_img, brand_id, cat_id, model_year, list_price, is_deleted) 
                         VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ssiiid', $prod_name, $prod_img, $brand_id, $cat_id, $model_year, $list_price);
        $stmt->execute();
    }
    fclose($file);
    echo '<div class="alert alert-success">Đã nhập sản phẩm thành công!</div>';
    echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin#products"; }, 2000);</script>';
    exit;
}
?>

<div class="card">
    <div class="card-header">
        <h3>Mass Receiving</h3>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Upload file CSV</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="index.php?page=admin#products" class="btn btn-secondary">Quay lại</a>
        </form>
        <div class="mt-3">
            <a href="public/template_product.csv" class="btn btn-info" download>Tải về tệp mẫu CSV</a>
        </div>
    </div>
</div>