<div class="content">
    <div class="product-left">
        <button onclick="prevImage()" style="background: none; border: none; cursor: pointer;">&#9664;</button>
        <img id="main-image" src="template/img/mai-shiranui.jpg" alt="Mai" style="width: 300px; height: auto; vertical-align: middle; margin-bottom: 20px;">
        <button onclick="nextImage()" style="background: none; border: none; cursor: pointer;">&#9654;</button>
        <button onclick="openFullscreen()" style="background: none; border: none; cursor: pointer; position: relative; top: 190px; right: 345px;">🔍</button>
        <div class="thumbnail-images">
            <img src="template/img/mai-shiranui.jpg" alt="Mai" style="width: 50px; height: auto; cursor: pointer;" onclick="changeImage('template/img/mai-shiranui.jpg')">
            <img src="template/img/mai-shiranui-1.jpg" alt="Mai 1" style="width: 50px; height: auto; cursor: pointer;" onclick="changeImage('template/img/mai-shiranui-1.jpg')">
            <img src="template/img/mai-shiranui-2.jpg" alt="Mai 2" style="width: 50px; height: auto; cursor: pointer;" onclick="changeImage('template/img/mai-shiranui-2.jpg')">
            <img src="template/img/mai-shiranui-3.jpg" alt="Mai 3" style="width: 50px; height: auto; cursor: pointer;" onclick="changeImage('template/img/mai-shiranui-3.jpg')">
        </div>
    </div>
    <div id="fullscreen-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9); z-index: 1000;">
        <span onclick="closeFullscreen()" style="position: absolute; top: 20px; right: 35px; color: white; font-size: 40px; font-weight: bold; cursor: pointer;">&times;</span>
        <img id="fullscreen-image" src="" style="margin: auto; display: block; width: 100%; height: 100%; object-fit: contain;">
    </div>
    <script>
        let images = [
            'template/img/mai-shiranui.jpg',
            'template/img/mai-shiranui-1.jpg',
            'template/img/mai-shiranui-2.jpg',
            'template/img/mai-shiranui-3.jpg'
        ];
        let currentIndex = 0;

        function changeImage(src) {
            document.getElementById('main-image').src = src;
            currentIndex = images.indexOf(src);
        }

        function prevImage() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            document.getElementById('main-image').src = images[currentIndex];
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
        <div class="product-right button">
        <button type="button" style="background: rgba(6, 30, 187, 1);padding: 12px; padding-right: 15px; padding-left: 15px; border-width: 0px; color: white;" onclick="window.location.href='index.php?page=cart'">THÊM VÀO GIỎ HÀNG</button>
        <button type="button" style="background: rgba(78, 177, 66, 1); border-width: 0px;padding: 12px; padding-right: 15px; padding-left: 15px; color: white;" onclick="window.location.href='index.php?page=cart'">MUA NGAY</button>
        </div>
        <ul>
            <br>
            <li>Tình trạng / Kho hàng: Còn hàng</li>
            <li>Thương Hiệu: Ninja Cat</li>
            <li>Tỉ lệ: 1/6</li>
            <li>Mã hàng: 001</li>
        </ul>
    </div>
</div>
<div class="product-description">
    <div class="product-description-left">
    <h2>Mô tả sản phẩm</h2>
    </div>
    <div class="product-description-right">
    <p>Mô hình action figure Silicone 1/6 King of Fighters Mai Shiranui hãng Ninja Cat</p>
    <br>
    <p>Đây là sản phẩm chưa phát hành, được nhà sản xuất niêm yết với giá ưu đãi, khách hàng phải đặt cọc và chờ đợi.</p>
    <br>
    <p>Nên đặt cọc khi hãng cho đặt trước (pre-order) và chờ đợi, bởi sau khi phát hành thì giá sản phẩm thường tăng 15% -> 25%, mà hàng thực tế đẹp, hot, hit thì có thể tăng giá hơn 50% so với giá Pre do nhu cầu của thị trường.</p>
    <br>
    <p>Thời gian phát hành thực tế phụ thuộc vào tình hình thực tế từ phía nhà cung cấp, nếu thay đổi cũng sẽ không thông báo trước, delay vài tháng là chuyện bình thường.</p>
    <br>
    <p>Đây là sản phẩm chưa phát hành, được nhà sản xuất niêm yết với giá ưu đãi, khách hàng phải đặt cọc và chờ đợi.</p>
    <br>
    <p>Nên đặt cọc khi hãng cho đặt trước (pre-order) và chờ đợi, bởi sau khi phát hành thì giá sản phẩm thường tăng 15% -> 25%, mà hàng thực tế đẹp, hot, hit thì có thể tăng giá hơn 50% so với giá Pre do nhu cầu của thị trường.</p>
    <br>
    <p>Thời gian phát hành thực tế phụ thuộc vào tình hình thực tế từ phía nhà cung cấp, nếu thay đổi cũng sẽ không thông báo trước, delay vài tháng là chuyện bình thường.</p>
    <br>
    </div>
</div>
<div class="product-related">
    <h2>Sản phẩm liên quan</h2>
    <br>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <img src="template/img/mai-shiranui.jpg" class="card-img-top" alt="Mai">
                <div class="card-body">
                    <h5 class="card-title">Mô hình action figure Silicone 1/6 King of Fighters Mai Shiranui hãng Ninja Cat</h5>
                    <p class="card-text">Giá: 3,500,000 VND</p>
                    <a href="index.php?page=product" class="btn btn-primary">Xem chi tiết</a>
</div>