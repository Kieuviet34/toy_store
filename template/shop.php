<?php
include ('inc/pagination.php');

echo '<div class="total-products">' . number_format($totalProducts, 0, ',', '.') . ' sản phẩm</div>';

echo '<div class="product-grid">';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product-card">';
        echo '<h3>' . htmlspecialchars($row['prod_name']) . '</h3>';
        echo '<div class="brand">Hãng: ' . htmlspecialchars($row['brand_name']) . '</div>';
        echo '<div class="category">Danh mục: ' . htmlspecialchars($row['cat_name']) . '</div>';
        echo '<div class="price">' . number_format($row['list_price'], 0, ',', '.') . '₫</div>';
        echo '</div>';
    }
} else {
    echo '<div class="no-results">Không tìm thấy sản phẩm nào</div>';
}
echo '</div>';

// Hiển thị phân trang
echo "<div class='pagination'>$links</div>";
?>
