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

        function nextImage() {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            document.getElementById('main-image').src = images[currentIndex];
        }

        function openFullscreen() {
            let modal = document.getElementById('fullscreen-modal');
            let fullscreenImage = document.getElementById('fullscreen-image');
            fullscreenImage.src = document.getElementById('main-image').src;
            modal.style.display = "block";
        }

        function closeFullscreen() {
            let modal = document.getElementById('fullscreen-modal');
            modal.style.display = "none";
        }
    </script>
    <div class="product-right">
        <h2>Mô hình action figure Silicone 1/6 King of Fighters Mai Shiranui hãng Ninja Cat</h2>
        <ul>
            <br>
            <li>Đây là sản phẩm chưa phát hành, được nhà sản xuất niêm yết với giá ưu đãi, khách hàng phải đặt cọc và chờ đợi.
            </li>
            <br>
            <li>Nên đặt cọc khi hãng cho đặt trước (pre-order) và chờ đợi, bởi sau khi phát hành thì giá sản phẩm thường tăng 15% -> 25%, mà hàng thực tế đẹp, hot, hit thì có thể tăng giá hơn 50% so với giá Pre do nhu cầu của thị trường.</li>
            <br>
            <li>Thời gian phát hành thực tế phụ thuộc vào tình hình thực tế từ phía nhà cung cấp, nếu thay đổi cũng sẽ không thông báo trước, delay vài tháng là chuyện bình thường.</li>
            <br>
        </ul>
        <h3>Giá: 3,500,000 VND</h3>
        <button type="button" style="background: rgba(6, 30, 187, 1);padding: 12px; padding-right: 15px; padding-left: 15px; border-width: 0px; color: white;">THÊM VÀO GIỎ HÀNG</button>
        <button type="button" style="background: rgba(78, 177, 66, 1); border-width: 0px;padding: 12px; padding-right: 15px; padding-left: 15px; color: white;">MUA NGAY</button>
        <ul>
            <br>
            <li>Tình trạng / Kho hàng: Còn hàng</li>
            <li>Thương Hiệu: Ninja Cat</li>
            <li>Tỉ lệ: 1/6</li>
            <li>Mã hàng: 001</li>
        </ul>
    </div>
</div>