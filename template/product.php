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
        padding: 2rem;
        gap: 2rem;
    }
    .product-left, .product-right {
        flex: 1;
    }
    .product-left img {
        width: 100%;
        max-width: 300px;
        height: auto;
        border: 1px solid #ccc;
    }
    .product-right h2 {
        margin-top: 0;
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
        <button type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product['prod_id']; ?>)">THÊM VÀO GIỎ HÀNG</button>
        <a href="index.php?page=cart"><button type="button" class="btn btn-success">MUA NGAY</button></a>
    </div>
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