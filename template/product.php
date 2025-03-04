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

.product-related .product-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
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
    </div>
    <div class="product-right">
        <h2><?php echo htmlspecialchars($product['prod_name']); ?></h2>
        <p><strong>Thương hiệu:</strong> <?php echo htmlspecialchars($product['brand_name']); ?></p>
        <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product['cat_name']); ?></p>
        <p><strong>Giá:</strong> <?php echo number_format($product['list_price'], 0, ',', '.'); ?>₫</p>
        <p><strong>Năm sản xuất:</strong> <?php echo htmlspecialchars($product['model_year']); ?></p>
        <button type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product['prod_id']; ?>)">
        <i class="bi bi-cart d-inline-block mx-1" style="position: relative; top: -2px;"></i>
        THÊM VÀO GIỎ HÀNG</button>
        <a href="index.php?page=cart"><button type="button" class="btn btn-success">
        <i class="bi bi-wallet2" style="position: relative; top: -2px;"></i>
        MUA NGAY</button></a>
    </div>
</div>
<div class="product-related">
        <h2 class="section-title mb-4">Sản phẩm nổi bật</h2>
        <?php include 'product_grid.php';  ?>
    </div>

<script>
function addToCart(productId) {
    fetch('src/add_to_cart.php', {
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