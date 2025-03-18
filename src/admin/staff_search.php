<?php
session_start();
include '../../inc/database.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    $sql = "
        SELECT s.*, st.store_name 
        FROM staffs s 
        LEFT JOIN stores st ON s.store_id = st.store_id 
        WHERE s.is_deleted = 0 
        ORDER BY s.staff_id ASC
    ";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "
        SELECT s.*, st.store_name 
        FROM staffs s 
        LEFT JOIN stores st ON s.store_id = st.store_id 
        WHERE s.is_deleted = 0 
          AND (s.staff_f_name LIKE ? OR s.staff_l_name LIKE ? OR s.email LIKE ? OR s.phone LIKE ?)
        ORDER BY s.staff_id ASC
    ";
    $stmt = $conn->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->bind_param('ssss', $like, $like, $like, $like);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($staff = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($staff['staff_id']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['staff_f_name'] . ' ' . $staff['staff_l_name']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['phone']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['email']) . "</td>";
        echo "<td>" . htmlspecialchars($staff['store_name'] ?? '') . "</td>";
        echo "<td>
                <div style='display:flex; gap:5px;'>\n
                    <a href='index.php?page=admin&action=update_staff&id=" . $staff['staff_id'] . "' class='btn btn-sm btn-warning'>\n
                        <span data-feather='edit'></span> Sửa\n
                    </a>\n
                    <button class='btn btn-sm btn-danger btn-delete' data-type='staff' data-id='" . $staff['staff_id'] . "' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?')\">\n
                        <span data-feather='trash-2'></span> Xóa\n
                    </button>\n
                </div>\n
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>Không tìm thấy nhân viên nào.</td></tr>";
}
$stmt->close();
?>
