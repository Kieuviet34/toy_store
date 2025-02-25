<?php
// product_grid.php
include 'inc/database.php';
// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

$query = "SELECT * FROM products WHERE 1=1";
if ($search) {
    $query .= " AND name LIKE '%$search%'";
}
if ($price_min) {
    $query .= " AND price >= $price_min";
}
if ($price_max) {
    $query .= " AND price <= $price_max";
}
if ($brand) {
    $query .= " AND brand = '$brand'";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <h2>Bộ lọc tìm kiếm</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Tìm theo tên" value="<?php echo $search; ?>">
        <input type="number" name="price_min" placeholder="Giá từ" value="<?php echo $price_min; ?>">
        <input type="number" name="price_max" placeholder="Giá đến" value="<?php echo $price_max; ?>">
        <select name="brand">
            <option value="">Chọn hãng</option>
            <option value="Brand A" <?php if($brand == 'Brand A') echo 'selected'; ?>>Brand A</option>
            <option value="Brand B" <?php if($brand == 'Brand B') echo 'selected'; ?>>Brand B</option>
        </select>
        <button type="submit">Tìm kiếm</button>
    </form>
    
    <h2>Danh sách sản phẩm</h2>
    <div class="product-grid">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product-item">
                <h3><?php echo $row['name']; ?></h3>
                <p>Giá: <?php echo number_format($row['price']); ?>đ</p>
                <p>Hãng: <?php echo $row['brand']; ?></p>
            </div>
        <?php } ?>
    </div>
</body>
</html>
<?php

// Xử lý phân trang
$limit = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, $currentPage);
$startAt = $limit * ($currentPage - 1);

// Truy vấn sản phẩm
$query = "SELECT p.prod_id, p.prod_img, p.prod_name, p.list_price, 
            b.brand_name, c.cat_name 
          FROM products p
          JOIN brands b ON p.brand_id = b.brand_id
          JOIN categories c ON p.cat_id = c.cat_id
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $startAt);
$stmt->execute();
$result = $stmt->get_result();

// Hiển thị sản phẩm
echo '<div class="product-slider-container">';
echo '<div class="slider-nav prev" onclick="loadPrevious()"><i class="bi bi-chevron-left"></i></div>';
echo '<div class="product-slider-wrapper">';
echo '<div class="product-slider" data-current-page="'.$currentPage.'">';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="product-card">
            <div class="product-image">
                <img src="'.$row['prod_img'].'" alt="'.$row['prod_name'].'">
            </div>
            <div class="product-details">
                <h3 class="product-title">'.htmlspecialchars($row['prod_name']).'</h3>
                <div class="brand-category">
                    <span class="brand">'.htmlspecialchars($row['brand_name']).'</span>
                    <span class="category">'.htmlspecialchars($row['cat_name']).'</span>
                </div>
                <div class="price-container">
                    <span class="original-price">'.number_format($row['list_price'] * 1.5, 0, ',', '.').'₫</span>
                    <span class="discount-price">'.number_format($row['list_price'], 0, ',', '.').'₫</span>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<div class="no-results">Không tìm thấy sản phẩm nào</div>';
}

echo '</div></div>';
echo '<div class="slider-nav next" onclick="loadNext()"><i class="bi bi-chevron-right"></i></div>';
echo '</div>';

// CSS (giữ nguyên như trước)
// ...
echo "<style>
.product-slider-container {
    position: relative;
    margin: 2rem 0;
    padding: 0 40px;
    display: flex;
}

.product-slider-wrapper {
    overflow: hidden;
}

.product-slider {
    display: flex;
    gap: 20px;
    transition: transform 0.5s ease-in-out;
}
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.product-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-details {
    padding: 1.5rem;
}

.product-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.brand-category {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
}

.price-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.original-price {
    text-decoration: line-through;
    color: #999;
}

.discount-price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 1.2rem;
}

.load-more-container {
    text-align: center;
    margin: 3rem 0;
}

.load-more-btn {
    padding: 1rem 3rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.load-more-btn:hover {
    background: #2980b9;
}

.no-results {
    text-align: center;
    padding: 2rem;
    color: #666;
}

</style>";
// JavaScript cập nhật
echo '<script>
let currentPage = '.$currentPage.';
const totalProducts = '.$conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0].';
const totalPages = Math.ceil(totalProducts / '.$limit.');
let isLoading = false;

async function loadProducts(page, direction) {
    if(isLoading) return;
    
    isLoading = true;
    const slider = document.querySelector(".product-slider");
    const loader = document.createElement("div");
    loader.className = "loading-overlay";
    slider.parentElement.appendChild(loader);

    try {
        const response = await fetch(`product_grid.php?page=${page}`);
        if(!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.text();
        const parser = new DOMParser();
        const newContent = parser.parseFromString(data, "text/html");
        const newSlider = newContent.querySelector(".product-slider");

        // Animation transition
        slider.style.transform = `translateX(${direction === "next" ? "-100%" : "100%"})`;
        slider.style.opacity = "0";

        await new Promise(r => setTimeout(r, 500));
        
        slider.parentNode.replaceChild(newSlider, slider);
        newSlider.style.transform = `translateX(${direction === "next" ? "100%" : "-100%"})`;
        newSlider.style.opacity = "0";
        
        await new Promise(r => setTimeout(r, 50));
        newSlider.style.transform = "translateX(0)";
        newSlider.style.opacity = "1";
        
        currentPage = page;
        updateNavButtons();

    } catch (error) {
        console.error("Fetch error:", error);
        showError("Có lỗi xảy ra khi tải dữ liệu");
    } finally {
        isLoading = false;
        loader.remove();
    }
}

function updateNavButtons() {
    document.querySelector(".prev").style.opacity = currentPage > 1 ? "1" : "0.5";
    document.querySelector(".next").style.opacity = currentPage < totalPages ? "1" : "0.5";
}

function showError(message) {
    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message";
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);
    setTimeout(() => errorDiv.remove(), 3000);
}

function loadNext() {
    if(currentPage < totalPages) loadProducts(currentPage + 1, "next");
}

function loadPrevious() {
    if(currentPage > 1) loadProducts(currentPage - 1, "prev");
}

// Initial button state
updateNavButtons();
</script>

<style>
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: grid;
    place-items: center;
    z-index: 100;
}

.loading-overlay::after {
    content: "";
    width: 40px;
    height: 40px;
    border: 3px solid #ddd;
    border-top-color: #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.error-message {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #e74c3c;
    color: white;
    padding: 1rem 2rem;
    border-radius: 5px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}
</style>';
?>