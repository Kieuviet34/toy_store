<?php
include 'inc/database.php';

// Lấy brand_id từ URL (ví dụ: ?brand_id=3)
$brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
if ($brand_id <= 0) {
    echo "Hãng sản xuất không hợp lệ.";
    exit;
}

// PHÂN TRANG
$limit = 12;
$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$currentPage = max(1, $currentPage);
$startAt = $limit * ($currentPage - 1);

// Truy vấn tổng số sản phẩm của hãng
$totalQuery = "SELECT COUNT(*) as total FROM products WHERE is_deleted = 0 AND brand_id = ?";
$stmt = $conn->prepare($totalQuery);
$stmt->bind_param('i', $brand_id);
$stmt->execute();
$totalResult = $stmt->get_result()->fetch_assoc();
$totalProducts = $totalResult['total'];
$totalPages = ceil($totalProducts / $limit);
$stmt->close();

// Truy vấn sản phẩm của hãng với phân trang
$query = "SELECT p.prod_id, p.prod_img, p.prod_name, p.list_price, 
                 b.brand_name, c.cat_name 
          FROM products p
          JOIN brands b ON p.brand_id = b.brand_id
          JOIN categories c ON p.cat_id = c.cat_id
          WHERE p.is_deleted = 0 AND p.brand_id = ?
          ORDER BY p.prod_name ASC
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $brand_id, $limit, $startAt);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Lấy tên hãng để hiển thị tiêu đề
$brandNameQuery = "SELECT brand_name FROM brands WHERE brand_id = ?";
$stmt = $conn->prepare($brandNameQuery);
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$resultBrand = $stmt->get_result();
$brandData = $resultBrand->fetch_assoc();
$stmt->close();
?>
    <style>
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-link {
            text-decoration: none;
            color: inherit;
            flex: 0 0 200px;
        }
        .product-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            padding: 10px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            border: 2px solid #007bff;
        }
        .product-image {
            width: 100%;
            height: 150px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .product-image img {
            max-width: 100%;
            max-height: 100%;
        }
        .product-details {
            padding-top: 10px;
        }
        .product-title {
            font-size: 1rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .price {
            color: #e74c3c;
            font-weight: bold;
            margin-top: 5px;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination .current-page {
            font-weight: bold;
            margin: 0 5px;
        }
        .pagination a.page-link {
            margin: 0 5px;
            text-decoration: none;
            color: #007bff;
        }
    </style>

<div class="container my-5">
    <h2 class="mb-4">Sản phẩm của hãng: <?php echo htmlspecialchars($brandData['brand_name']); ?></h2>
    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php 
                    $img_src = $row['prod_img'] ? 'data:image/jpeg;base64,' . base64_encode($row['prod_img']) : 'path/to/placeholder.jpg';
                ?>
                <a href="index.php?page=product&id=<?php echo $row['prod_id']; ?>" class="product-link">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($row['prod_name']); ?>">
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
            <p class="text-center">Không tìm thấy sản phẩm nào.</p>
        <?php endif; ?>
    </div>
    <!-- Phân trang -->
    <div class="pagination">
        <?php 
            $links = "";
            for ($i = 1; $i <= $totalPages; $i++) {
                $url = "index.php?page=show_brand_prod&brand_id=$brand_id&p=$i";
                if ($i == $currentPage) {
                    $links .= "<span class='current-page'>$i</span> ";
                } else {
                    $links .= "<a href='$url' class='page-link'>$i</a> ";
                }
            }
            echo $links;
        ?>
    </div>
</div>

