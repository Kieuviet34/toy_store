<?php
include 'inc/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Sản phẩm không tồn tại.";
    exit;
}

$prod_id = (int) $_GET['id'];

$query = "SELECT p.*, b.brand_name, c.cat_name 
          FROM products p 
          JOIN brands b ON p.brand_id = b.brand_id 
          JOIN categories c ON p.cat_id = c.cat_id 
          WHERE p.prod_id = ? AND p.is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $prod_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Sản phẩm không tồn tại hoặc đã bị xóa.";
    exit;
}

$product = $result->fetch_assoc();
$cat_id = $product['cat_id']; 
$limit = 4; 
$related_query = "SELECT p.prod_id, p.prod_img, p.prod_name, p.list_price, 
                  b.brand_name, c.cat_name 
                  FROM products p
                  JOIN brands b ON p.brand_id = b.brand_id
                  JOIN categories c ON p.cat_id = c.cat_id
                  WHERE p.is_deleted = 0 AND p.cat_id = ? AND p.prod_id != ?
                  LIMIT ?";
$stmt_related = $conn->prepare($related_query);
$stmt_related->bind_param("iii", $cat_id, $prod_id, $limit);
$stmt_related->execute();
$result_related = $stmt_related->get_result();
?>

<style>
    .product-container {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px 0;
    }

    .product-left img {
        width: 300px;
        height: auto;
        border: 2px solid #f85639;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .product-right {
        padding-left: 20px;
        text-align: left;
        margin-right: 100px;
    }

    .product-right h2 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: rgb(0, 116, 211);
    }

    .product-right button {
        flex-grow: 1;
        margin: 0 0 8px 8px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .product-right button:hover {
        background-color: rgba(248, 86, 57, 0.8);
        transform: scale(1.05);
    }

    .product-related {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 40px 0;
    }

    .product-related .section-title {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: rgb(0, 116, 211);
    }

    .product-grid {
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 0 29px;
        margin-top: 20px;
        
    }

    .product-link {
        display: block;
        text-decoration: none;
        color: inherit;
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
        transform: translateY(-2px);
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

    .no-results {
        text-align: center;
        padding: 2rem;
        color: #666;
    }
</style>

<div class="product-container">
    <div class="product-left">
        <?php 
        if ($product['prod_img']) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($product['prod_img']) . '" alt="' . htmlspecialchars($product['prod_name']) . '">';
        } else {
            echo '<img src="path/to/placeholder.jpg" alt="No Image">';
        }
        ?>
        <button onclick="openFullscreen()" style="background: none; border: none; cursor: pointer; position: relative; top: 200px; right: 0px;"><i class="bi bi-zoom-in"></i></button>
    </div>
    <div class="product-right">
        <h2><?php echo htmlspecialchars($product['prod_name']); ?></h2>
        <p><strong>Thương hiệu:</strong> <?php echo htmlspecialchars($product['brand_name']); ?></p>
        <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product['cat_name']); ?></p>
        <p><strong>Giá:</strong> <?php echo number_format($product['list_price'], 0, ',', '.'); ?>₫</p>
        <p><strong>Năm sản xuất:</strong> <?php echo htmlspecialchars($product['model_year']); ?></p>
        <button type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product['prod_id']; ?>)">
            <i class="bi bi-cart d-inline-block mx-1" style="position: relative; top: -2px;"></i>
            THÊM VÀO GIỎ HÀNG
        </button>
        <a href="index.php?page=cart"><button type="button" class="btn btn-success">
            <i class="bi bi-wallet2" style="position: relative; top: -2px;"></i>
            MUA NGAY
        </button></a>
    </div>
</div>
<div class="product-related">
    <h2 class="section-title mb-4">Sản phẩm cùng loại</h2>
    <div class="product-grid">
        <?php if ($result_related->num_rows > 0): ?>
            <?php while ($row = $result_related->fetch_assoc()): ?>
                <a href="index.php?page=product&id=<?php echo $row['prod_id']; ?>" class="product-link" style="text-decoration: none; color: inherit;">
                    <div class="product-card">
                        <div class="product-image">
                            <?php 
                            if ($row['prod_img']) {
                                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['prod_img']) . '" alt="' . htmlspecialchars($row['prod_name']) . '">';
                            } else {
                                echo '<img src="path/to/placeholder.jpg" alt="No Image">';
                            }
                            ?>
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
            <div class="no-results">Không tìm thấy sản phẩm nào cùng loại</div>
        <?php endif; ?>
    </div>
</div>

<script>
function openFullscreen() {
    var img = document.querySelector('.product-left img');
    var fullscreenDiv = document.createElement('div');
    fullscreenDiv.style.position = 'fixed';
    fullscreenDiv.style.top = '0';
    fullscreenDiv.style.left = '0';
    fullscreenDiv.style.width = '100%';
    fullscreenDiv.style.height = '100%';
    fullscreenDiv.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
    fullscreenDiv.style.display = 'flex';
    fullscreenDiv.style.justifyContent = 'center';
    fullscreenDiv.style.alignItems = 'center';
    fullscreenDiv.style.zIndex = '1000';
    fullscreenDiv.onclick = function() {
        document.body.removeChild(fullscreenDiv);
    };
    var fullscreenImg = document.createElement('img');
    fullscreenImg.src = img.src;
    fullscreenImg.style.maxWidth = '90%';
    fullscreenImg.style.maxHeight = '90%';
    fullscreenDiv.appendChild(fullscreenImg);
    document.body.appendChild(fullscreenDiv);
}

function addToCart(productId) {
    fetch('src/client/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ prod_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sản phẩm đã được thêm vào giỏ hàng!');
        } else {
            alert('Có lỗi xảy ra: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi thêm sản phẩm.');
    });
}
</script>