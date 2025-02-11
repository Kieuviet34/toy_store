<?php
include 'database.php';
    // Phân trang
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Đảm bảo page >= 1
$startAt = $limit * ($page - 1);

// Đếm tổng số bản ghi (Sử dụng prepared statement)
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE prod_id = ?");
$stmt->bind_param("i", $_SESSION['prod_id']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$totalPage = ceil($result['total'] / $limit);

// Tạo links phân trang
$links = "";
for ($i = 1; $i <= $totalPage; $i++) {
    if($i == $page) {
        $links .= "<span class='current-page'>$i</span> ";
    } else {
        $links .= "<a href='index.php?page=$i' class='page-link'>Page $i</a> ";
    }
}
//hiển thị kết quả
$query = "SELECT p.prod_id, p.prod_name, p.list_price, b.brand_name, c.cat_name 
          FROM products p
          JOIN brands b ON p.brand_id = b.brand_id
          JOIN categories c ON p.cat_id = c.cat_id
          WHERE p.prod_id = ?
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $_SESSION['prod_id'], $limit, $startAt);
$stmt->execute();
$result = $stmt->get_result();

echo '<div class="product-grid">';
echo '<div class="total-products">Tổng sản phẩm: ' . number_format($result['total'], 0, ',', '.') . '</div>';

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="product-card">';
        echo '<h3>' . htmlspecialchars($row['prod_name']) . '</h3>';
        echo '<div class="brand">Hãng: ' . htmlspecialchars($row['brand_name']) . '</div>';
        echo '<div class="category">Danh mục: ' . htmlspecialchars($row['cat_name']) . '</div>';
        echo '<div class="price">' . number_format($row['list_price'], 0, ',', '.') . ' VND</div>';
        echo '</div>';
    }
} else {
    echo '<div class="no-results">Không tìm thấy sản phẩm nào</div>';
}

echo '</div>'; // Đóng product-grid



// Hiển thị phân trang
echo "<div class='pagination'>$links</div>";




?>