<?php
include 'inc/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID danh mục không hợp lệ.");
}
$cat_id = (int)$_GET['id'];

$query = "SELECT * FROM categories WHERE cat_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cat_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Không tìm thấy danh mục.");
}
$category = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_cat_name = trim($_POST['cat_name']);
    if (empty($new_cat_name)) {
        $error = "Tên danh mục không được để trống.";
    } else {
        $updateQuery = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $new_cat_name, $cat_id);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Cập nhật thất bại.";
        }
        $stmt->close();
    }
}
?>
<div class="container mt-5">
    <h2>Cập nhật danh mục</h2>
</div>

<!-- Modal cập nhật -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Chỉnh sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="redirectBack()"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" id="updateForm">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="cat_name" name="cat_name" value="<?php echo htmlspecialchars($category['cat_name']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal thông báo thành công -->
<?php if (isset($success) && $success): ?>
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
                <h4 class="mt-3">Cập nhật thành công!</h4>
            </div>
        </div>
    </div>
</div>
<script>
    var updateModalEl = document.getElementById('updateModal');
    var updateModal = bootstrap.Modal.getInstance(updateModalEl);
    updateModal.hide();
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    setTimeout(function() {
        window.location.href = "index.php?page=admin&action=categories#categories";
    }, 2000);
</script>
<?php else: ?>
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        var updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
        updateModal.show();
    });
    </script>
<?php endif; ?>

<style>
    .container.mt-5 { text-align: center; }
    .modal-body .alert { margin-bottom: 15px; }
</style>
