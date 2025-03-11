<?php
$queryProductsList = "
    SELECT p.*, b.brand_name 
    FROM products p 
    JOIN brands b ON p.brand_id = b.brand_id 
    WHERE p.is_deleted = 0 
    ORDER BY p.prod_id ASC";
$resultProductsList = $conn->query($queryProductsList);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý sản phẩm</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?page=admin&action=add_product" class="btn btn-primary">
            <span data-feather="plus"></span> Thêm sản phẩm
        </a>
        <a href="index.php?page=admin&action=mass_receiving" class="btn btn-primary ms-2">
            <span data-feather="upload"></span> Mass Receiving
        </a>
    </div>
</div>
<div class="mb-3">
    <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Hãng</th>
                <th>Năm sản xuất</th>
                <th>Giá</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultProductsList && $resultProductsList->num_rows > 0) {
                while ($product = $resultProductsList->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($product['prod_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['prod_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['brand_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['model_year']) . "</td>";
                    echo "<td>" . number_format($product['list_price'], 0, ',', '.') . "₫</td>";
                    echo "<td>
                            <a href='index.php?page=admin&action=update_product&prod_id=" . $product['prod_id'] . "' class='btn btn-sm btn-warning'>
                                <span data-feather='edit'></span> Sửa
                            </a>
                            <button class='btn btn-sm btn-danger btn-delete' data-type='product' data-id='" . $product['prod_id'] . "'>
                                <span data-feather='trash-2'></span> Xóa
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Không có sản phẩm nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>