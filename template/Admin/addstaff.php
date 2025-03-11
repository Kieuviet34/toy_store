<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

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
        $insert_query = "INSERT INTO staffs (staff_f_name, staff_l_name, email, is_active, is_deleted) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('sssi', $staff_f_name, $staff_l_name, $email, $is_active);

        if ($stmt->execute()) {
            $new_staff_id = $conn->insert_id;

            if (!empty($roles)) {
                $insert_role_query = "INSERT INTO staff_role (staff_id, role_id) VALUES (?, ?)";
                $stmt_role = $conn->prepare($insert_role_query);
                foreach ($roles as $role_id) {
                    $stmt_role->bind_param('ii', $new_staff_id, $role_id);
                    $stmt_role->execute();
                }
                $stmt_role->close();
            }

            echo '<div class="alert alert-success" role="alert">Thêm nhân viên thành công!</div>';
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin#staff"; }, 2000);</script>';
            exit;
        } else {
            $error = "Không thể thêm nhân viên: " . $stmt->error;
        }
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm nhân viên</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row mb-3">
                    <label for="staff_f_name" class="col-sm-3 col-form-label">Tên</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="staff_f_name" name="staff_f_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="staff_l_name" class="col-sm-3 col-form-label">Họ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="staff_l_name" name="staff_l_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="is_active" class="col-sm-3 col-form-label">Trạng thái</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Vai trò (Phân quyền)</label>
                    <div class="col-sm-9">
                        <?php while ($role = $roles_result->fetch_assoc()): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="role_<?php echo $role['role_id']; ?>" name="roles[]" value="<?php echo $role['role_id']; ?>">
                                <label class="form-check-label" for="role_<?php echo $role['role_id']; ?>"><?php echo htmlspecialchars($role['role_name']); ?></label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                    <a href="index.php?page=admin#staff" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
