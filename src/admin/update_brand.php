<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID hãng không hợp lệ.");
}
$brand_id = (int)$_GET['id'];

$query = "SELECT * FROM brands WHERE brand_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Không tìm thấy hãng sản xuất.");
}
$brand = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_brand_name = trim($_POST['brand_name']);
    if (empty($new_brand_name)) {
        $error = "Tên hãng không được để trống.";
    } else {
        $updateQuery = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $new_brand_name, $brand_id);
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
    <h2>Cập nhật hãng sản xuất</h2> 
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal">Sửa hãng</button>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Chỉnh sửa hãng sản xuất</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="redirectBack()"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" id="updateForm">
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Tên hãng</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($brand['brand_name']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($success) && $success): ?>
<div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: green;"></i>
                <h4 class="mt-3">Cập nhật thành công!</h4>
                <button type="button" class="btn btn-success" onclick="redirectBack()">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(function() {
        redirectBack();
    }, 2000);
    function redirectBack() {
        window.location.href = 'index.php?page=admin&action=brands#brands';
    }
</script>
<?php else: ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
        updateModal.show();
    });
    function redirectBack() {
        window.location.href = 'index.php?page=admin&action=brands#brands';
    }
</script>
<?php endif; ?>

<style>
    .container.mt-5 { text-align: center; }
    .modal-body .alert { margin-bottom: 15px; }
</style>
