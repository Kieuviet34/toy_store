<?php
include 'inc/database.php';

if (!isset($_SESSION['user'])) {
    echo '<div class="container mt-5"><h1 class="display-4 text-center">Giỏ Hàng</h1><p class="text-center">Vui lòng đăng nhập để xem giỏ hàng.</p></div>';
    exit;
}

$customer_id = $_SESSION['user']['customer_id'];

$query = "SELECT o.order_id 
          FROM orders o 
          WHERE o.customer_id = ? AND o.order_status = 1 AND o.is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><h1 class="display-4 text-center">Giỏ Hàng</h1><p class="text-center">Giỏ hàng của bạn đang trống.</p></div>';
    exit;
}

$order = $result->fetch_assoc();
$order_id = $order['order_id'];

$query = "SELECT oi.*, p.prod_name, p.list_price 
          FROM order_items oi 
          JOIN products p ON oi.prod_id = p.prod_id 
          WHERE oi.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$items = $stmt->get_result();

$total = 0;
$item_count = $items->num_rows; 
?>

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
            <?php while ($item = $items->fetch_assoc()): ?>
                <?php 
                $item_total = $item['list_price'] * $item['quantity'] * (1 - $item['discount']);
                $total += $item_total;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['prod_name']); ?></td>
                    <td><?php echo number_format($item['list_price'], 0, ',', '.'); ?>₫</td>
                    <td>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(<?php echo $item['item_id']; ?>, -1)">-</button>
                            <input type="text" class="form-control text-center" value="<?php echo $item['quantity']; ?>" readonly>
                            <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(<?php echo $item['item_id']; ?>, 1)">+</button>
                        </div>
                    </td>
                    <td><?php echo number_format($item_total, 0, ',', '.'); ?>₫</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?php echo $item['item_id']; ?>)">Xóa</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <div class="text-right mt-4">
        <h4>Tổng Cộng: <span id="cart-total"><?php echo number_format($total, 0, ',', '.'); ?>₫</span></h4>
        <button class="btn btn-danger" onclick="clearCart(<?php echo $order_id; ?>)">Xóa Giỏ Hàng</button>
        <button class="btn btn-success" onclick="proceedToCheckout(<?php echo $item_count; ?>)">Tiến Hành Thanh Toán</button>
    </div>
</div>

<script>
function updateQuantity(itemId, change) {
    fetch('src/update_quantity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ item_id: itemId, change: change })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi cập nhật số lượng.');
    });
}

function removeFromCart(itemId) {
    fetch('src/remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + data.error);
        }
    });
}

function clearCart(orderId) {
    if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        fetch('src/clear_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi khi xóa giỏ hàng.');
        });
    }
}

function proceedToCheckout(itemCount) {
    if (itemCount > 0) {
        window.location.href = 'index.php?page=checkout';
    } else {
        alert('Giỏ hàng của bạn đang trống, vui lòng thêm sản phẩm.');
    }
}
</script>