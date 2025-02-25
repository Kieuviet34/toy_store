<?php
include('inc/pagination.php');

echo '<div class="total-products">' . number_format($totalProducts, 0, ',', '.') . ' sản phẩm</div>';

echo '<div class="product-grid">';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<a href="index.php?page=product&id=' . $row['prod_id'] . '" class="product-link">';
        echo '<div class="product-card">';
        echo '  <div class="product-image">';
        if ($row['prod_img']) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['prod_img']) . '" alt="' . htmlspecialchars($row['prod_name']) . '">';
        } else {
            echo '<img src="path/to/placeholder.jpg" alt="No Image">';
        }
        echo '  </div>';
        echo '  <div class="product-details">';
        echo '      <h3 class="product-title">' . htmlspecialchars($row['prod_name']) . '</h3>';
        echo '      <div class="brand">Hãng: ' . htmlspecialchars($row['brand_name']) . '</div>';
        echo '      <div class="category">Danh mục: ' . htmlspecialchars($row['cat_name']) . '</div>';
        echo '      <div class="price">' . number_format($row['list_price'], 0, ',', '.') . '₫</div>';
        echo '  </div>';
        echo '</div>';
        echo '</a>';
    }
} else {
    echo '<div class="no-results">Không tìm thấy sản phẩm nào</div>';
}
echo '</div>';
echo "<style>
    /* Grid container cho sản phẩm */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
    margin-top: 100px;
}

.product-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    background: #fff;
    display: flex;
    flex-direction: column;
    height: 350px; 
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-sizing: border-box;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-image {
    width: 100%;
    height: 200px; 
    overflow: hidden;
    border-radius: 4px;
    margin-bottom: 10px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-title {
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: #333;
}

.brand-category {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 8px;
}

.price {
    color: #e91e63;
    font-size: 1.2rem;
    font-weight: bold;
    margin-top: 10px;
}

/* Tổng số sản phẩm */
.total-products {
    font-size: 1.5rem;
    margin: 20px 0;
    color: #333;
    text-align: center;
}

/* Phân trang */
.pagination {
    margin: 40px 0;
    text-align: center;
}

.page-link {
    padding: 8px 12px;
    margin: 0 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #007bff;
    display: inline-block;
}

.current-page {
    padding: 8px 12px;
    margin: 0 5px;
    background: #007bff;
    color: white;
    border-radius: 4px;
    display: inline-block;
}

</style>";

echo "<div class='pagination'>$links</div>";
?>
