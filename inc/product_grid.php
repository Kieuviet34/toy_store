<?php
include 'inc/database.php';

// --- PHẦN FEATURED PRODUCTS (Sản phẩm nổi bật) ---
$limit = 5;
$currentPage = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
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

$totalProducts = $conn->query("SELECT COUNT(*) FROM products WHERE is_deleted = 0")->fetch_row()[0];
$totalPages = ceil($totalProducts / $limit);

// --- PHẦN BEST-SELLING PRODUCTS (Sản phẩm bán chạy) ---
$bestSellingLimit = 5;
$bestSellingCurrentPage = isset($_GET['bestSellingPg']) ? (int)$_GET['bestSellingPg'] : 1;
$bestSellingCurrentPage = max(1, $bestSellingCurrentPage);
$bestSellingStartAt = $bestSellingLimit * ($bestSellingCurrentPage - 1);

$bestSellingQuery = "
    SELECT p.prod_id, p.prod_img, p.prod_name, p.list_price, 
           b.brand_name, c.cat_name, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.order_id AND o.order_status = 2
    JOIN products p ON oi.prod_id = p.prod_id
    JOIN brands b ON p.brand_id = b.brand_id
    JOIN categories c ON p.cat_id = c.cat_id
    WHERE p.is_deleted = 0
    GROUP BY p.prod_id
    ORDER BY total_sold DESC
    LIMIT ? OFFSET ?";
$stmt_bs = $conn->prepare($bestSellingQuery);
$stmt_bs->bind_param("ii", $bestSellingLimit, $bestSellingStartAt);
$stmt_bs->execute();
$resultBestSelling = $stmt_bs->get_result();

$bestSellingTotalProducts = $conn->query("
    SELECT COUNT(DISTINCT p.prod_id)
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.order_id AND o.order_status = 2
    JOIN products p ON oi.prod_id = p.prod_id
    WHERE p.is_deleted = 0
")->fetch_row()[0];
$bestSellingTotalPages = ceil($bestSellingTotalProducts / $bestSellingLimit);
?>
<!-- Featured Products Slider -->
<div class="product-slider-container">
    <div class="slider-nav prev" onclick="loadProducts(<?php echo $currentPage - 1; ?>, 'prev')"><i class="bi bi-chevron-left"></i></div>
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
    <div class="slider-nav next" onclick="loadProducts(<?php echo $currentPage + 1; ?>, 'next')"><i class="bi bi-chevron-right"></i></div>
</div>

<!-- Best-Selling Products Slider -->
<div class="best-selling-container" style="margin: 2rem 0;">
    <h2 class="section-title mb-4">Sản phẩm bán chạy</h2>
    <div class="best-selling-slider-container">
        <div class="slider-nav prev" onclick="loadBestSellingProducts(<?php echo $bestSellingCurrentPage - 1; ?>, 'prev')"><i class="bi bi-chevron-left"></i></div>
        <div class="best-selling-slider-wrapper">
            <div class="best-selling-slider" data-current-page="<?php echo $bestSellingCurrentPage; ?>">
                <?php if ($resultBestSelling && $resultBestSelling->num_rows > 0): ?>
                    <?php while ($row = $resultBestSelling->fetch_assoc()): ?>
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
                    <div class="no-results">Không có sản phẩm bán chạy</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="slider-nav next" onclick="loadBestSellingProducts(<?php echo $bestSellingCurrentPage + 1; ?>, 'next')"><i class="bi bi-chevron-right"></i></div>
    </div>
</div>

<!-- Brands Slider -->
<?php
$brandsQuery = "SELECT * FROM brands";
$resultBrands = $conn->query($brandsQuery);
?>
<div class="brands-container" style="margin: 2rem 0;">
    <h2 class="section-title mb-4">Hãng sản xuất</h2>
    <div class="brand-slider-wrapper" style="display: flex; gap: 10px; overflow-x: auto; padding: 10px;">
        <?php if ($resultBrands && $resultBrands->num_rows > 0): ?>
            <?php while ($brand = $resultBrands->fetch_assoc()): ?>
                <div class="brand-card" data-brand-id="<?php echo $brand['brand_id']; ?>" style="flex: 0 0 auto; padding: 10px; border: 1px solid #ddd; border-radius: 8px; text-align: center; min-width: 100px; cursor: pointer; transition: background-color 0.3s, transform 0.3s, color 0.3s;">
                    <span class="brand-name"><?php echo htmlspecialchars($brand['brand_name']); ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">Không có hãng sản xuất</div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.querySelectorAll('.brand-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var brandId = card.getAttribute('data-brand-id');
            window.location.href = "index.php?page=show_brand_prod&brand_id=" + brandId;
        });
    });
</script>


<style>
.product-slider-container {
    position: relative;
    margin: 2rem 0;
    padding: 0 29px;
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
    animation: spin .5s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
/* Best-selling slider styles: giống featured */
.best-selling-slider-container {
    position: relative;
    margin: 2rem 0;
    padding: 0 29px;
    display: flex;
    align-items: center;
}
.best-selling-slider-wrapper {
    overflow: hidden;
    flex-grow: 1;
}
.best-selling-slider {
    display: flex;
    gap: 10px;
    transition: transform 0.5s ease-in-out;
    padding-top: 10px;
}
.brand-slider-wrapper {
    display: flex;
    overflow-x: auto; /* Cho phép cuộn ngang */
    white-space: nowrap; /* Đảm bảo các brand nằm trên một hàng */
    padding: 10px;
    justify-content: flex-start; /* Bắt đầu từ bên trái thay vì căn giữa */
    scrollbar-width: thin; /* Thanh cuộn mỏng hơn cho Firefox */
    scrollbar-color: #ddd #fff; /* Màu thanh cuộn cho Firefox */
}

/* Tùy chỉnh thanh cuộn cho WebKit (Chrome, Safari) */
.brand-slider-wrapper::-webkit-scrollbar {
    height: 8px;
}
.brand-slider-wrapper::-webkit-scrollbar-thumb {
    background-color: #ddd;
    border-radius: 4px;
}
.brand-slider-wrapper::-webkit-scrollbar-track {
    background-color: #fff;
}

.brand-card {
    display: inline-block; 
    flex: 0 0 auto; 
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: center;
    min-width: 100px; 
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s, color 0.3s;
    margin-right: 10px; 
}

.brand-card:last-child {
    margin-right: 0;
}

.brand-card:hover {
    background-color: #007bff;
    color: #fff;
    transform: translateY(-5px);
    border-color: #007bff;
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
// Featured products slider
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

function loadNext() {
    if (currentPage < totalPages) loadProducts(currentPage + 1, "next");
}

function loadPrevious() {
    if (currentPage > 1) loadProducts(currentPage - 1, "prev");
}

let bestSellingCurrentPage = <?php echo $bestSellingCurrentPage; ?>;
const bestSellingTotalProducts = <?php echo $bestSellingTotalProducts; ?>;
const bestSellingTotalPages = Math.ceil(bestSellingTotalProducts / <?php echo $bestSellingLimit; ?>);
let bestSellingIsLoading = false;

async function loadBestSellingProducts(page, direction) {
    if (bestSellingIsLoading) return;
    
    bestSellingIsLoading = true;
    const slider = document.querySelector(".best-selling-slider");
    const loader = document.createElement("div");
    loader.className = "loading-overlay";
    slider.parentElement.appendChild(loader);

    try {
        const response = await fetch(`index.php?page=home&bestSellingPg=${page}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.text();
        const parser = new DOMParser();
        const newContent = parser.parseFromString(data, "text/html");
        const newSlider = newContent.querySelector(".best-selling-slider");

        if (!newSlider) {
            console.error("Không tìm thấy phần tử .best-selling-slider trong nội dung trả về.");
            showError("Không tìm thấy sản phẩm bán chạy");
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
        bestSellingCurrentPage = page;
        updateBSNavButtons();
    } catch (error) {
        console.error("Best-selling fetch error:", error);
        showError("Có lỗi xảy ra khi tải sản phẩm bán chạy");
    } finally {
        bestSellingIsLoading = false;
        loader.remove();
    }
}
function updateBSNavButtons() {
    document.querySelector(".prev").style.opacity = bestSellingCurrentPage > 1 ? "1" : "0.5";
    document.querySelector(".next").style.opacity = bestSellingCurrentPage < bestSellingTotalPages ? "1" : "0.5";
}
function loadBestSellingNext() {
    if (bestSellingCurrentPage < bestSellingTotalPages) loadBestSellingProducts(bestSellingCurrentPage + 1, "next");
}

function loadBestSellingPrevious() {
    if (bestSellingCurrentPage > 1) loadBestSellingProducts(bestSellingCurrentPage - 1, "prev");
}

</script>
