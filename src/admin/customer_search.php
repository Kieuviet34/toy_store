<?php
session_start();
include '../../inc/database.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    $sql = "
        SELECT * FROM customers
        WHERE is_deleted = 0 
        ORDER BY customer_id ASC
    ";
    $stmt = $conn->prepare($sql);
} else {
    // Tìm kiếm theo họ, tên, email hoặc số điện thoại
    $sql = "
        SELECT * FROM customers
        WHERE is_deleted = 0 
          AND (f_name LIKE ? OR l_name LIKE ? OR email LIKE ? OR phone LIKE ?)
        ORDER BY customer_id ASC
    ";
    $stmt = $conn->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->bind_param('ssss', $like, $like, $like, $like);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($customer = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($customer['customer_id']) . "</td>";
        echo "<td>" . htmlspecialchars($customer['f_name'] . ' ' . $customer['l_name']) . "</td>";
        echo "<td>" . htmlspecialchars($customer['phone']) . "</td>";
        echo "<td>" . htmlspecialchars($customer['email']) . "</td>";
        echo "<td>" . htmlspecialchars($customer['store_name'] ?? '') . "</td>";
        echo "<td>
                <div style='display:flex; gap:5px;'>\n
                    <a href='index.php?page=admin&action=update_customer&id=" . $customer['customer_id'] . "' class='btn btn-sm btn-warning'>\n
                        <span data-feather='edit'></span> Sửa\n
                    </a>\n
                    <button class='btn btn-sm btn-danger btn-delete' data-type='customer' data-id='" . $customer['customer_id'] . "' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?')\">\n
                        <span data-feather='trash-2'></span> Xóa\n
                    </button>\n
                </div>\n
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>Không tìm thấy khách hàng nào.</td></tr>";
}
$stmt->close();
?>
