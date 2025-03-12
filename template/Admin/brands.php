<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

$query = "SELECT * FROM brands ORDER BY brand_id ASC";
$result = $conn->query($query);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Hãng sản xuất</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?page=admin&action=add_brand" class="btn btn-primary">
            <span data-feather="plus"></span> Thêm hãng
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên hãng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($brand = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($brand['brand_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($brand['brand_name']) . "</td>";
                    echo "<td>
                            <a href='index.php?page=admin&action=update_brand&id=" . $brand['brand_id'] . "' class='btn btn-sm btn-warning'>
                                <span data-feather='edit'></span> Sửa
                            </a>
                            <button class='btn btn-sm btn-danger btn-delete' data-type='brand' data-id='" . $brand['brand_id'] . "'>
                                <span data-feather='trash-2'></span> Xóa
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Không có hãng sản xuất nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
