

<div class="container mt-5">
    <h1 class="display-4 text-center">Giỏ Hàng</h1>
    
    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>Sản Phẩm</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Tổng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <!-- Các mặt hàng trong giỏ sẽ được hiển thị ở đây động -->
        </tbody>
    </table>
    
    <div class="text-right mt-4">
        <h4>Tổng Cộng: <span id="cart-total">0₫</span></h4>
        <a href="checkout.php" class="btn btn-success">Tiến Hành Thanh Toán</a>
    </div>
</div>

<?php
include 'footer.php';
?>
