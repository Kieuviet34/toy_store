<?php
$queryStaffList = "SELECT s.staff_id, s.staff_f_name, s.staff_l_name, s.email, s.is_active, 
                  GROUP_CONCAT(r.role_name SEPARATOR ', ') AS role_names
                  FROM staffs s
                  LEFT JOIN staff_role sr ON s.staff_id = sr.staff_id
                  LEFT JOIN roles r ON sr.role_id = r.role_id
                  WHERE s.is_deleted = 0
                  GROUP BY s.staff_id
                  ORDER BY s.staff_id ASC";
$resultStaffList = $conn->query($queryStaffList);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý nhân viên</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?page=admin&action=add_staff" class="btn btn-primary">
            <span data-feather="plus"></span> Thêm nhân viên
        </a>
    </div>
</div>
<div class="mb-3">
    <input type="text" class="form-control" placeholder="Tìm kiếm nhân viên...">
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Vai trò</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultStaffList && $resultStaffList->num_rows > 0) {
                while ($staff = $resultStaffList->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($staff['staff_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($staff['staff_f_name'] . ' ' . $staff['staff_l_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($staff['role_names']) . "</td>";
                    echo "<td>" . htmlspecialchars($staff['email']) . "</td>";
                    echo "<td>";
                    if ($staff['is_active'] == 1) {
                        echo '<span class="badge bg-success">Hoạt động</span>';
                    } else {
                        echo '<span class="badge bg-secondary">Không hoạt động</span>';
                    }
                    echo "</td>";
                    echo "<td>
                            <a href='index.php?page=admin&action=update_staff&id=" . $staff['staff_id'] . "' class='btn btn-sm btn-warning'>
                                <span data-feather='edit'></span> Sửa
                            </a>
                            <button class='btn btn-sm btn-danger btn-delete' data-type='staff' data-id='" . $staff['staff_id'] . "'>
                                <span data-feather='trash-2'></span> Xóa
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có nhân viên nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>