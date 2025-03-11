<?php
include 'inc/database.php';

$limit = 5;
$currentPage = isset($_GET['pg']) ? (int)$_GET['pg'] : 1; // Lấy số trang từ 'pg'
$currentPage = max(1, $currentPage);
$startAt = $limit * ($currentPage - 1);

$query = "SELECT p.prod_id, p.prod_img, p.prod_name, p.list_price, 
            b.brand_name, c.cat_name 
          FROM products p
          JOIN brands b ON p.brand_id = b.brand_id
          JOIN categories c ON p.cat_id = c.cat_id
          WHERE p.is_deleted = 0
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $startAt);
$stmt->execute();
$result = $stmt->get_result();

$totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$totalPages = ceil($totalProducts / $limit);
?>


<div class="product-slider-container">
    <div class="slider-nav prev" onclick="loadPrevious()"><i class="bi bi-chevron-left"></i></div>
    <div class="product-slider-wrapper">
        <div class="product-slider" data-current-page="<?php echo $currentPage; ?>">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                    $img_src = $row['prod_img'] ? 'data:image/jpeg;base64,' . base64_encode($row['prod_img']) : null;
                    ?>
                    <a href="index.php?page=product&id=<?php echo $row['prod_id']; ?>" class="product-link" style="text-decoration: none; color: inherit;">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($row['prod_name']); ?>">
                            </div>
                            <div class="product-details">
                                <h3 class="product-title"><?php echo htmlspecialchars($row['prod_name']); ?></h3>
                                <div class="brand-category">
                                    <span class="brand"><?php echo htmlspecialchars($row['brand_name']); ?></span>
                                    <span class="category"><?php echo htmlspecialchars($row['cat_name']); ?></span>
                                </div>
                                <div class="price-container">
                                    <span class="original-price"><?php echo number_format($row['list_price'] * 1.5, 0, ',', '.'); ?>₫</span>
                                    <span class="discount-price"><?php echo number_format($row['list_price'], 0, ',', '.'); ?>₫</span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">Không tìm thấy sản phẩm nào</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="slider-nav next" onclick="loadNext()"><i class="bi bi-chevron-right"></i></div>
</div>

<style>
.product-slider-container {
    position: relative;
    margin: 2rem 0;
    padding: 0px 29px;
    display: flex;
    align-items: center;
}

.product-slider-wrapper {
    overflow: hidden;
    flex-grow: 1;
}

.product-slider {
    display: flex;
    gap: 10px;
    transition: transform 0.5s ease-in-out;
    height: 280px;
    padding-top: 10px;
}

.product-link {
    display: block;
    flex: 0 0 235px;
    box-sizing: border-box;
}

.product-card {
    width: 235px;
    height: 250px;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    box-sizing: border-box;
}

.product-card:hover {
    transform: translateY(-5px);
    border: 3px solid #007bff;
}

.product-image {
    width: 100%;
    height: 150px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    display: block;
}

.product-details {
    flex-grow: 1;
    padding: 0.75rem;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-title {
    font-size: 1rem;
    margin-bottom: 0.25rem;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.brand-category {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.price-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9rem;
}

.discount-price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 1.1rem;
}

.slider-nav {
    cursor: pointer;
    padding: 10px;
    font-size: 1.5rem;
    color: #333;
    transition: opacity 0.3s ease;
    user-select: none;
}

.slider-nav:hover {
    opacity: 0.7;
}

.no-results {
    text-align: center;
    padding: 2rem;
    color: #666;
}

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
    content: '';
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
</style>

<script>
let currentPage = <?php echo $currentPage; ?>;
const totalProducts = <?php echo $totalProducts; ?>;
const totalPages = Math.ceil(totalProducts / <?php echo $limit; ?>);
let isLoading = false;

async function loadProducts(page, direction) {
    if (isLoading) return;
    
    isLoading = true;
    const slider = document.querySelector(".product-slider");
    const loader = document.createElement("div");
    loader.className = "loading-overlay";
    slider.parentElement.appendChild(loader);

    try {
        // Gọi URL với định tuyến page=home và phân trang thông qua 'pg'
        const response = await fetch(`index.php?page=home&pg=${page}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.text();
        const parser = new DOMParser();
        const newContent = parser.parseFromString(data, "text/html");
        const newSlider = newContent.querySelector(".product-slider");

        if (!newSlider) {
            console.error("Không tìm thấy phần tử .product-slider trong nội dung trả về.");
            showError("Không tìm thấy nội dung sản phẩm");
            return;
        }

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
    if (currentPage < totalPages) loadProducts(currentPage + 1, "next");
}

function loadPrevious() {
    if (currentPage > 1) loadProducts(currentPage - 1, "prev");
}

updateNavButtons();

</script>