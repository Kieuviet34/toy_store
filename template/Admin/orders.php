<?php
$queryOrdersList = "
    SELECT 
        o.order_id, 
        CONCAT(c.f_name, ' ', c.l_name) AS customer_name,
        o.order_date, 
        o.order_status, 
        COALESCE(SUM(oi.list_price * oi.quantity * (1 - oi.discount)), 0) AS total_amount
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    LEFT JOIN customers c ON o.customer_id = c.customer_id
    WHERE o.is_deleted = 0
    GROUP BY o.order_id, c.f_name, c.l_name, o.order_date, o.order_status
    ORDER BY o.order_date ASC";
$resultOrdersList = $conn->query($queryOrdersList);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý đơn hàng</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="src/admin/export_orders_excel.php" class="btn btn-primary">
            <span data-feather="file-text"></span> Xuất báo cáo Excel
        </a>
    </div>
</div>
<div class="mb-3">
    <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm đơn hàng...">
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Tên Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="TableBody">
            <?php
            if ($resultOrdersList && $resultOrdersList->num_rows > 0) {
                while ($order = $resultOrdersList->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>#" . htmlspecialchars($order['order_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['customer_name']) . "</td>";
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
                            <button class='btn btn-sm btn-info btn-view' data-id='" . $order['order_id'] . "' data-bs-toggle='modal' data-bs-target='#viewOrderModal'>
                                <span data-feather='eye'></span> Xem đơn hàng
                            </button>
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

<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOrderModalLabel">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <div class="text-center">Đang tải thông tin...</div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-view').forEach(function(button) {
        button.addEventListener('click', function() {
            var orderId = this.getAttribute('data-id');
            fetch('src/admin/get_order_details.php?id=' + orderId)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('orderDetailsContent').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('orderDetailsContent').innerHTML = '<p class=\"text-danger\">Lỗi tải thông tin đơn hàng.</p>';
                });
        });
    });
    const ordersearchInput = document.getElementById('searchInput');
    const orderTableBody = document.getElementById('TableBody');

    ordersearchInput.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length === 0) {
            fetch('src/admin/order_search.php?q=')
            .then(response => response.text())
            .then(html => {
                orderTableBody.innerHTML = html;
            })
            .catch(err => console.error('Error:', err));
            return;
        }

        fetch('src/admin/order_search.php?q=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(html => {
            orderTableBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

