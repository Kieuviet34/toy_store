<?php
$queryCategoriesList = "SELECT * FROM categories ORDER BY cat_id ASC";
$resultCategoriesList = $conn->query($queryCategoriesList);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý danh mục</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?page=admin&action=add_category" class="btn btn-primary">
            <span data-feather="plus"></span> Thêm danh mục
        </a>
    </div>
</div>
<div class="mb-3">
    <input type="text" class="form-control" placeholder="Tìm kiếm danh mục...">
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultCategoriesList && $resultCategoriesList->num_rows > 0) {
                while ($category = $resultCategoriesList->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($category['cat_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($category['cat_name']) . "</td>";
                    echo "<td>
                            <a href='index.php?page=admin&action=update_category&id=" . $category['cat_id'] . "' class='btn btn-sm btn-warning'>
                                <span data-feather='edit'></span> Sửa
                            </a>
                            <button class='btn btn-sm btn-danger btn-delete' data-type='category' data-id='" . $category['cat_id'] . "'>
                                <span data-feather='trash-2'></span> Xóa
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Không có danh mục nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>