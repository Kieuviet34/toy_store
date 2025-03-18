<?php
session_start();
include '../../inc/database.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    $sql = "
        SELECT * FROM categories
        ORDER BY cat_id ASC
    ";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "
        SELECT * FROM categories
        WHERE cat_name LIKE ?
        ORDER BY cat_id ASC
    ";
    $stmt = $conn->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->bind_param('s', $like);
}

if (!$stmt->execute()) {
    echo "Lỗi truy vấn: " . $stmt->error;
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($cat = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($cat['cat_id']) . "</td>";
        echo "<td>" . htmlspecialchars($cat['cat_name']) . "</td>";
        echo "<td>
                <div style='display:flex; gap:5px;'>
                    <a href='index.php?page=admin&action=update_cat&id=" . htmlspecialchars($cat['cat_id']) . "' class='btn btn-sm btn-warning'>
                        <span data-feather='edit'></span> Sửa
                    </a>
                    <button class='btn btn-sm btn-danger btn-delete' data-type='cat' data-id='" . htmlspecialchars($cat['cat_id']) . "' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?')\">
                        <span data-feather='trash-2'></span> Xóa
                    </button>
                </div>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>Không tìm thấy danh mục nào.</td></tr>";
}

$stmt->close();
?>