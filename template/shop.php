<?php
include('inc/pagination.php');

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'prod_name';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
$filter_brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_price_min = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? (float)$_GET['price_min'] : '';
$filter_price_max = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? (float)$_GET['price_max'] : '';

?>

<div class="filter-container">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Bộ lọc
        </button>
        <div class="dropdown-menu p-3" aria-labelledby="filterDropdown" style="min-width: 300px;">
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="shop">
                <input type="hidden" name="p" value="<?php echo $currentPage; ?>">

                <div class="mb-3">
                    <label for="filterBrand" class="form-label">Hãng</label>
                    <select class="form-select" id="filterBrand" name="brand">
                        <option value="">Tất cả</option>
                        <?php
                        $query = "SELECT brand_name FROM brands";
                        $result_brands = $conn->query($query);
                        while ($brand = $result_brands->fetch_assoc()) {
                            $selected = $filter_brand === $brand['brand_name'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($brand['brand_name']) . '" ' . $selected . '>' . htmlspecialchars($brand['brand_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="filterCategory" class="form-label">Danh mục</label>
                    <select class="form-select" id="filterCategory" name="category">
                        <option value="">Tất cả</option>
                        <?php
                        $query = "SELECT cat_name FROM categories";
                        $result_categories = $conn->query($query);
                        while ($cat = $result_categories->fetch_assoc()) {
                            $selected = $filter_category === $cat['cat_name'] ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($cat['cat_name']) . '" ' . $selected . '>' . htmlspecialchars($cat['cat_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Khoảng giá</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="price_min" placeholder="Tối thiểu" value="<?php echo ($filter_price_min !== '' ? $filter_price_min : ''); ?>" min="0">
                        <span class="input-group-text">-</span>
                        <input type="number" class="form-control" name="price_max" placeholder="Tối đa" value="<?php echo ($filter_price_max !== '' ? $filter_price_max : ''); ?>" min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="sortBy" class="form-label">Sắp xếp theo</label>
                    <select class="form-select" id="sortBy" name="sort_by">
                        <?php
                        $sort_options = [
                            'prod_name' => 'Tên sản phẩm',
                            'list_price' => 'Giá',
                            'brand_name' => 'Hãng',
                            'cat_name' => 'Danh mục'
                        ];
                        foreach ($sort_options as $value => $label) {
                            $selected = $sort_by === $value ? 'selected' : '';
                            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sortOrder" class="form-label">Thứ tự</label>
                    <select class="form-select" id="sortOrder" name="sort_order">
                        <option value="ASC" <?php echo ($sort_order === 'ASC' ? 'selected' : ''); ?>>Tăng dần</option>
                        <option value="DESC" <?php echo ($sort_order === 'DESC' ? 'selected' : ''); ?>>Giảm dần</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
            </form>
        </div>
    </div>
</div>

<div class="product-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <a href="index.php?page=product&id=<?php echo $row['prod_id']; ?>" class="product-link">
                <div class="product-card">
                    <div class="product-image">
                        <?php if ($row['prod_img']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['prod_img']); ?>" alt="<?php echo htmlspecialchars($row['prod_name']); ?>">
                        <?php else: ?>
                            <img src="path/to/placeholder.jpg" alt="No Image">
                        <?php endif; ?>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['prod_name']); ?></h3>
                        <div class="brand">Hãng: <?php echo htmlspecialchars($row['brand_name']); ?></div>
                        <div class="category">Danh mục: <?php echo htmlspecialchars($row['cat_name']); ?></div>
                        <div class="price"><?php echo number_format($row['list_price'], 0, ',', '.'); ?>₫</div>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-results">Không tìm thấy sản phẩm nào</div>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php echo $links; ?>
</div>
