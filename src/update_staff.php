<?php
    include 'inc/database.php';
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header('Location: index.php?page=login');
        exit;
    }
    
    // Lấy staff_id từ URL
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

    if($result->num_rows == 0){
        echo '<div class="container mt-5"><h1 class="text-center text-danger">Nhân viên không tồn tại hoặc đã bị xóa.</h1></div>';
        exit;
    }
    $staff = $result->fetch_assoc();
$role_ids = explode(',', $staff['role_ids'] ?: '');

// Lấy danh sách vai trò từ bảng roles
$roles_query = "SELECT role_id, role_name FROM roles";
$roles_result = $conn->query($roles_query);

// Xử lý cập nhật khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_f_name = trim($_POST['staff_f_name']);
    $staff_l_name = trim($_POST['staff_l_name']);
    $email = trim($_POST['email']);
    $is_active = (int)$_POST['is_active'];
    $roles = isset($_POST['roles']) && is_array($_POST['roles']) ? $_POST['roles'] : [];

    if (empty($staff_f_name) || empty($staff_l_name) || empty($email)) {
        $error = "Vui lòng điền đầy đủ thông tin cơ bản.";
    } else {
        // Cập nhật thông tin nhân viên
        $update_query = "
            UPDATE staffs 
            SET staff_f_name = ?, staff_l_name = ?, email = ?, is_active = ? 
            WHERE staff_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('sssii', $staff_f_name, $staff_l_name, $email, $is_active, $staff_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Xóa các vai trò cũ
            $delete_roles_query = "DELETE FROM staff_role WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_roles_query);
            $stmt->bind_param('i', $staff_id);
            $stmt->execute();

            // Thêm các vai trò mới
            if (!empty($roles)) {
                $insert_role_query = "INSERT INTO staff_role (staff_id, role_id) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_role_query);
                foreach ($roles as $role_id) {
                    $stmt->bind_param('ii', $staff_id, $role_id);
                    $stmt->execute();
                }
            }

            header('Location: index.php?page=admin#staff');
            exit;
        } else {
            $error = "Không thể cập nhật nhân viên: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 50px auto;
        }
        .card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1rem;
        }
        .error {
            color: #dc3545;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .form-control, .form-select, .form-check-input, .form-check-label {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }
        .d-flex.justify-content-end.gap-2 {
            margin-top: 20px;
        }
        .form-check {
            margin-bottom: 0.5rem;
        }
        .form-check-label {
            margin-left: 0.5rem;
            color: #333;
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
                            <a class="nav-link active" href="index.php?page=admin#staff">
                                <i class="bi bi-person-check me-2"></i> Nhân viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#products">
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
                            <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Cập nhật nhân viên</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <p class="error"><?php echo htmlspecialchars($error); ?></p>
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
                                            <option value="1" <?php echo $staff['is_active'] == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                                            <option value="0" <?php echo $staff['is_active'] == 0 ? 'selected' : ''; ?>>Không hoạt động</option>
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>

