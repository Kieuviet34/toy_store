<?php
include '../../inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$staff_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($staff_id <= 0) {
    echo '<div class="container mt-5"><h1 class="text-center text-danger">Nhân viên không hợp lệ.</h1></div>';
    exit;
}

$query = "SELECT s.*, GROUP_CONCAT(sr.role_id) as role_ids 
          FROM staffs s 
          LEFT JOIN staff_role sr ON s.staff_id = sr.staff_id 
          WHERE s.staff_id = ? AND s.is_deleted = 0 
          GROUP BY s.staff_id";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><h1 class="text-center text-danger">Nhân viên không tồn tại hoặc đã bị xóa.</h1></div>';
    exit;
}
$staff = $result->fetch_assoc();
$stmt->close();

$role_ids = explode(',', $staff['role_ids'] ?: '');

$roles_query = "SELECT role_id, role_name FROM roles";
$roles_result = $conn->query($roles_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_f_name = trim($_POST['staff_f_name']);
    $staff_l_name = trim($_POST['staff_l_name']);
    $email = trim($_POST['email']);
    $is_active = (int)$_POST['is_active'];
    $roles = isset($_POST['roles']) && is_array($_POST['roles']) ? $_POST['roles'] : [];

    if (empty($staff_f_name) || empty($staff_l_name) || empty($email)) {
        $error = "Vui lòng điền đầy đủ thông tin cơ bản.";
    } else {
        $update_query = "UPDATE staffs 
                         SET staff_f_name = ?, staff_l_name = ?, email = ?, is_active = ?
                         WHERE staff_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('sssii', $staff_f_name, $staff_l_name, $email, $is_active, $staff_id);

        if ($stmt->execute()) {
            $delete_query = "DELETE FROM staff_role WHERE staff_id = ?";
            $stmt_delete = $conn->prepare($delete_query);
            $stmt_delete->bind_param('i', $staff_id);
            $stmt_delete->execute();
            $stmt_delete->close();

            if (!empty($roles)) {
                $insert_query = "INSERT INTO staff_role (staff_id, role_id) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($insert_query);
                foreach ($roles as $role_id) {
                    $stmt_insert->bind_param('ii', $staff_id, $role_id);
                    $stmt_insert->execute();
                }
                $stmt_insert->close();
            }
            echo '<div class="alert alert-success" role="alert">Cập nhật nhân viên thành công!</div>';
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin#staff"; }, 2000);</script>';
            exit;
        } else {
            $error = "Không thể cập nhật nhân viên: " . $stmt->error;
        }
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Cập nhật nhân viên</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row mb-3">
                    <label for="staff_f_name" class="col-sm-3 col-form-label">Tên</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="staff_f_name" name="staff_f_name" value="<?php echo htmlspecialchars($staff['staff_f_name']); ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="staff_l_name" class="col-sm-3 col-form-label">Họ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="staff_l_name" name="staff_l_name" value="<?php echo htmlspecialchars($staff['staff_l_name']); ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="is_active" class="col-sm-3 col-form-label">Trạng thái</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1" <?php echo ($staff['is_active'] == 1) ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="0" <?php echo ($staff['is_active'] == 0) ? 'selected' : ''; ?>>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Vai trò (Phân quyền)</label>
                    <div class="col-sm-9">
                        <?php while ($role = $roles_result->fetch_assoc()): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="roles[]" value="<?php echo $role['role_id']; ?>" <?php echo in_array($role['role_id'], $role_ids) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="role_<?php echo $role['role_id']; ?>">
                                    <?php echo htmlspecialchars($role['role_name']); ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Cập nhật</button>
                    <a href="index.php?page=admin#staff" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
