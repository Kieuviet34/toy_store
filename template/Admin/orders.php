<?php
$queryOrdersList = "
    SELECT 
        o.order_id, 
        o.customer_id, 
        o.order_date, 
        o.order_status, 
        COALESCE(SUM(oi.list_price * oi.quantity * (1 - oi.discount)), 0) AS total_amount
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.is_deleted = 0
    GROUP BY o.order_id, o.customer_id, o.order_date, o.order_status
    ORDER BY o.order_date ASC";
$resultOrdersList = $conn->query($queryOrdersList);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý đơn hàng</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
            <span data-feather="plus"></span> Thêm đơn hàng
        </button>
    </div>
</div>
<div class="mb-3">
    <input type="text" class="form-control" placeholder="Tìm kiếm đơn hàng...">
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Mã Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultOrdersList && $resultOrdersList->num_rows > 0) {
                while ($order = $resultOrdersList->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>#" . htmlspecialchars($order['order_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['customer_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                    echo "<td>" . number_format($order['total_amount'], 0, ',', '.') . "₫</td>";
                    echo "<td>";
                    if ($order['order_status'] == 1) {
                        echo '<span class="badge bg-warning">Đang chờ xử lý</span>';
                    } else {
                        echo '<span class="badge bg-success">Đã giao</span>';
                    }
                    echo "</td>";
                    echo "<td>
                            <a href='index.php?page=admin&action=update_order&id=" . $order['order_id'] . "' class='btn btn-sm btn-warning'>
                                <span data-feather='edit'></span> Sửa
                            </a>
                            <button class='btn btn-sm btn-danger btn-delete' data-type='order' data-id='" . $order['order_id'] . "'>
                                <span data-feather='trash-2'></span> Xóa
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có đơn hàng nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>