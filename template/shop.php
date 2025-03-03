<?php
include('inc/pagination.php');

// Lấy các giá trị lọc và sắp xếp từ GET (nếu có)
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'prod_name'; 
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; 
$filter_brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_price_min = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? (float)$_GET['price_min'] : '';
$filter_price_max = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? (float)$_GET['price_max'] : '';

echo '<div class="filter-container">';
echo '  <div class="dropdown">';
echo '      <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
echo '          Bộ lọc';
echo '      </button>';
echo '      <div class="dropdown-menu p-3" aria-labelledby="filterDropdown" style="min-width: 300px;">';
echo '          <form method="GET" action="index.php">';
echo '              <input type="hidden" name="page" value="shop">';
echo '              <input type="hidden" name="p" value="' . $currentPage . '">'; // Giữ trang hiện tại

// Lọc theo hãng
echo '              <div class="mb-3">';
echo '                  <label for="filterBrand" class="form-label">Hãng</label>';
echo '                  <select class="form-select" id="filterBrand" name="brand">';
echo '                      <option value="">Tất cả</option>';
$query = "SELECT brand_name FROM brands";
$result_brands = $conn->query($query);
while ($brand = $result_brands->fetch_assoc()) {
    $selected = $filter_brand === $brand['brand_name'] ? 'selected' : '';
    echo '                      <option value="' . htmlspecialchars($brand['brand_name']) . '" ' . $selected . '>' . htmlspecialchars($brand['brand_name']) . '</option>';
}
echo '                  </select>';
echo '              </div>';

// Lọc theo danh mục
echo '              <div class="mb-3">';
echo '                  <label for="filterCategory" class="form-label">Danh mục</label>';
echo '                  <select class="form-select" id="filterCategory" name="category">';
echo '                      <option value="">Tất cả</option>';
$query = "SELECT cat_name FROM categories";
$result_categories = $conn->query($query);
while ($cat = $result_categories->fetch_assoc()) {
    $selected = $filter_category === $cat['cat_name'] ? 'selected' : '';
    echo '                      <option value="' . htmlspecialchars($cat['cat_name']) . '" ' . $selected . '>' . htmlspecialchars($cat['cat_name']) . '</option>';
}
echo '                  </select>';
echo '              </div>';

// Lọc theo giá
echo '              <div class="mb-3">';
echo '                  <label class="form-label">Khoảng giá</label>';
echo '                  <div class="input-group">';
echo '                      <input type="number" class="form-control" name="price_min" placeholder="Tối thiểu" value="' . ($filter_price_min !== '' ? $filter_price_min : '') . '" min="0">';
echo '                      <span class="input-group-text">-</span>';
echo '                      <input type="number" class="form-control" name="price_max" placeholder="Tối đa" value="' . ($filter_price_max !== '' ? $filter_price_max : '') . '" min="0">';
echo '                  </div>';
echo '              </div>';

// Sắp xếp
echo '              <div class="mb-3">';
echo '                  <label for="sortBy" class="form-label">Sắp xếp theo</label>';
echo '                  <select class="form-select" id="sortBy" name="sort_by">';
$sort_options = [
    'prod_name' => 'Tên sản phẩm',
    'list_price' => 'Giá',
    'brand_name' => 'Hãng',
    'cat_name' => 'Danh mục'
];
foreach ($sort_options as $value => $label) {
    $selected = $sort_by === $value ? 'selected' : '';
    echo '                      <option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
}
echo '                  </select>';
echo '              </div>';
echo '              <div class="mb-3">';
echo '                  <label for="sortOrder" class="form-label">Thứ tự</label>';
echo '                  <select class="form-select" id="sortOrder" name="sort_order">';
echo '                      <option value="ASC" ' . ($sort_order === 'ASC' ? 'selected' : '') . '>Tăng dần</option>';
echo '                      <option value="DESC" ' . ($sort_order === 'DESC' ? 'selected' : '') . '>Giảm dần</option>';
echo '                  </select>';
echo '              </div>';

// Nút áp dụng
echo '              <button type="submit" class="btn btn-primary w-100">Áp dụng</button>';
echo '          </form>';
echo '      </div>';
echo '  </div>';
echo '</div>';

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
    margin-top: 20px;
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
    object-fit: contain;
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

.brand, .category {
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

/* Bộ lọc */
.filter-container {
    margin: 20px 0;
    text-align: left;
    padding: 30px;
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