<?php
include '../../inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['cat_name']);

    if (empty($cat_name)) {
        $error = "Vui lòng điền tên danh mục.";
    } else {
        $insert_query = "INSERT INTO categories (cat_name) VALUES (?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('s', $cat_name);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success" role="alert">Thêm danh mục thành công!</div>';
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin#categories"; }, 2000);</script>';
            exit;
        } else {
            $error = "Không thể thêm danh mục: " . $stmt->error;
        }
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm danh mục</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row mb-3">
                    <label for="cat_name" class="col-sm-3 col-form-label">Tên danh mục</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="cat_name" name="cat_name" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                    <a href="index.php?page=admin#categories" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
