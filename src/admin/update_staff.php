<?php
include 'inc/database.php';

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
$current_roles = $conn->query("SELECT role_name FROM roles WHERE role_id IN (".implode(',', $role_ids).")");
$role_names = [];
while($row = $current_roles->fetch_assoc()) {
    $role_names[] = strtolower($row['role_name']);
}

$roles_query = "SELECT role_id, role_name FROM roles";
$roles_result = $conn->query($roles_query);

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_f_name = trim($_POST['staff_f_name']);
    $staff_l_name = trim($_POST['staff_l_name']);
    $email = trim($_POST['email']);
    $is_active = (int)$_POST['is_active'];
    $roles = isset($_POST['roles']) ? [$_POST['roles'][0]] : [];
    $admin_username = trim($_POST['staff_username'] ?? '');
    $admin_password = trim($_POST['staff_password'] ?? '');

    $is_admin = false;
    if (!empty($roles)) {
        $role_check = $conn->prepare("SELECT role_name FROM roles WHERE role_id = ?");
        $role_check->bind_param('i', $roles[0]);
        $role_check->execute();
        $role_name = $role_check->get_result()->fetch_assoc()['role_name'];
        $is_admin = (strtolower($role_name) === 'admin');
    }

    if (empty($staff_f_name) || empty($staff_l_name) || empty($email)) {
        $error = "Vui lòng điền đầy đủ thông tin cơ bản.";
    }
    if ($is_admin) {
        if (empty($admin_username) || empty($admin_password)) {
            $error = "Vui lòng nhập đầy đủ thông tin quản trị viên.";
        } elseif (strlen($admin_password) < 8) {
            $error = "Mật khẩu phải có ít nhất 8 ký tự.";
        } else {
            $username_check = $conn->prepare("SELECT staff_id FROM staffs WHERE staff_username = ? AND staff_id != ?");
            $username_check->bind_param('si', $admin_username, $staff_id);
            $username_check->execute();
            if ($username_check->get_result()->num_rows > 0) {
                $error = "Tên đăng nhập đã được sử dụng bởi nhân viên khác.";
            }
        }
    }

    if (!isset($error)) {
        try {
            $conn->begin_transaction();

            $update_fields = [
                'staff_f_name' => $staff_f_name,
                'staff_l_name' => $staff_l_name,
                'email' => $email,
                'is_active' => $is_active
            ];

            if ($is_admin) {
                $update_fields['staff_username'] = $admin_username;
                $update_fields['staff_password'] = password_hash($admin_password, PASSWORD_DEFAULT);
            }

            $set_clause = implode(', ', array_map(function($field) {
                return "$field = ?";
            }, array_keys($update_fields)));

            $update_query = "UPDATE staffs SET $set_clause WHERE staff_id = ?";
            $stmt = $conn->prepare($update_query);
            
            $types = str_repeat('s', count($update_fields)) . 'i';
            $params = array_values($update_fields);
            $params[] = $staff_id;
            
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();

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

            $conn->commit();
            echo '<div class="alert alert-success" role="alert">Cập nhật nhân viên thành công!</div>';
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin&action=staff#staff"; }, 1500);</script>';
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>


    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Cập nhật nhân viên</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" id="staffForm">
                    <div class="row mb-3">
                        <label for="staff_f_name" class="col-sm-3 col-form-label">Tên</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="staff_f_name" name="staff_f_name" 
                                   value="<?php echo htmlspecialchars($staff['staff_f_name']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="staff_l_name" class="col-sm-3 col-form-label">Họ</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="staff_l_name" name="staff_l_name" 
                                   value="<?php echo htmlspecialchars($staff['staff_l_name']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($staff['email']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="is_active" class="col-sm-3 col-form-label">Trạng thái</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1" <?= $staff['is_active'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="0" <?= $staff['is_active'] == 0 ? 'selected' : '' ?>>Không hoạt động</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Vai trò</label>
                        <div class="col-sm-9">
                            <?php 
                            $roles_result->data_seek(0);
                            while ($role = $roles_result->fetch_assoc()): 
                                $is_checked = in_array($role['role_id'], $role_ids);
                            ?>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" 
                                        name="roles[]" 
                                        value="<?= $role['role_id'] ?>" 
                                        <?= $is_checked ? 'checked' : '' ?>
                                        data-role-name="<?= htmlspecialchars($role['role_name']) ?>">
                                    <label class="form-check-label">
                                        <?= htmlspecialchars($role['role_name']) ?>
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-save me-2"></i>Cập nhật
                        </button>
                        <a href="index.php?page=admin&action=staff#staff" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="adminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thiết lập quản trị viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modalUsername" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="modalPassword" required minlength="8">
                    </div>
                    <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="confirmAdmin">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adminModal = new bootstrap.Modal('#adminModal');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('staffForm');
        let isAdmin = <?= in_array('admin', $role_names) ? 'true' : 'false' ?>;

        // Xử lý nút submit
        submitBtn.addEventListener('click', function() {
            const selectedRole = document.querySelector('input[name="roles[]"]:checked');
            
            if (selectedRole && selectedRole.dataset.roleName.toLowerCase() === 'admin') {
                adminModal.show();
            } else {
                form.submit();
            }
        });

        // Xác nhận thông tin admin
        document.getElementById('confirmAdmin').addEventListener('click', function() {
            const username = document.getElementById('modalUsername').value;
            const password = document.getElementById('modalPassword').value;

            if (username && password && password.length >= 8) {
                // Thêm các trường ẩn vào form
                const usernameInput = document.createElement('input');
                usernameInput.type = 'hidden';
                usernameInput.name = 'staff_username';
                usernameInput.value = username;
                form.appendChild(usernameInput);

                const passwordInput = document.createElement('input');
                passwordInput.type = 'hidden';
                passwordInput.name = 'staff_password';
                passwordInput.value = password;
                form.appendChild(passwordInput);

                adminModal.hide();
                form.submit();
            } else {
                alert('Vui lòng điền đầy đủ thông tin và mật khẩu ít nhất 8 ký tự');
            }
        });
    });
</script>