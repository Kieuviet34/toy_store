<?php
$queryCustomersList = "SELECT * FROM customers WHERE is_deleted = 0 ORDER BY customer_id ASC";
$resultCustomersList = $conn->query($queryCustomersList);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý khách hàng</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?page=admin&action=add_customer" class="btn btn-primary">
            <span data-feather="plus"></span> Thêm khách hàng
        </a>
    </div>
</div>
<div class="mb-3">
    <input type="text" class="form-control" placeholder="Tìm kiếm khách hàng...">
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Username</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultCustomersList && $resultCustomersList->num_rows > 0) {
                while ($customer = $resultCustomersList->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($customer['customer_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['f_name'] . ' ' . $customer['l_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['city']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['customer_username']) . "</td>";
                    echo "<td>
                            <a href='index.php?page=admin&action=update_customer&id=" . $customer['customer_id'] . "' class='btn btn-sm btn-warning'>
                                <span data-feather='edit'></span> Sửa
                            </a>
                            <button class='btn btn-sm btn-danger btn-delete' data-type='customer' data-id='" . $customer['customer_id'] . "'>
                                <span data-feather='trash-2'></span> Xóa
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có khách hàng nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>