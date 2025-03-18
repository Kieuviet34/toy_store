<?php
session_start();
include '../../inc/database.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    $sql = "
        SELECT p.*, b.brand_name 
        FROM products p 
        JOIN brands b ON p.brand_id = b.brand_id 
        WHERE p.is_deleted = 0 
        ORDER BY p.prod_id ASC
    ";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "
        SELECT p.*, b.brand_name 
        FROM products p 
        JOIN brands b ON p.brand_id = b.brand_id 
        WHERE p.is_deleted = 0 
          AND (p.prod_name LIKE ? OR b.brand_name LIKE ?)
        ORDER BY p.prod_id ASC
    ";
    $stmt = $conn->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->bind_param('ss', $like, $like);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($product['prod_id']) . "</td>";
        echo "<td>" . htmlspecialchars($product['prod_name']) . "</td>";
        echo "<td>" . htmlspecialchars($product['brand_name']) . "</td>";
        echo "<td>" . htmlspecialchars($product['model_year']) . "</td>";
        echo "<td>" . number_format($product['list_price'], 0, ',', '.') . "₫</td>";
        echo "<td>
                <div style='display:flex; gap:5px;'>\n
                    <a href='index.php?page=admin&action=update_product&prod_id=" . $product['prod_id'] . "' class='btn btn-sm btn-warning'>\n
                        <span data-feather='edit'></span> Sửa\n
                    </a>\n
                    <button class='btn btn-sm btn-danger btn-delete' data-type='product' data-id='" . $product['prod_id'] . "' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?')\">\n
                        <span data-feather='trash-2'></span> Xóa\n
                    </button>\n
                </div>\n
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>Không tìm thấy sản phẩm nào.</td></tr>";
}
$stmt->close();
?>
