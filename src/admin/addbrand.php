<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = trim($_POST['brand_name']);
    if (empty($brand_name)) {
        $error = "Vui lòng điền tên hãng sản xuất.";
    } else {
        $insert_query = "INSERT INTO brands (brand_name) VALUES (?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('s', $brand_name);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Không thể thêm hãng: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm hãng sản xuất</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="brand_name" class="form-label">Tên hãng</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                    <a href="index.php?page=admin#brands" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($success): ?>
    <div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
                    <h4 class="mt-3">Thêm hãng thành công!</h4>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = "index.php?page=admin#brands";
        }, 2000);
    </script>
<?php endif; ?>

<style>
    .form-container {
        max-width: 700px;
        margin: 50px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .alert { margin-bottom: 15px; }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        color: white;
    }
</style>
