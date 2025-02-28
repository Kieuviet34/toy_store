<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchText = $_POST["searchText"] ?? "";
    $brand = $_POST["brandFilter"] ?? "all";
    echo "Tìm kiếm: $searchText | Hãng: $brand";
}
?>

<?php include 'product_grid.php'; ?>

<form class="search-container" method="POST">
    <input type="text" name="searchText" placeholder="Tìm kiếm...">
    <select name="brandFilter">
        <option value="all">Tất cả</option>
        <option value="brand1">Hãng 1</option>
        <option value="brand2">Hãng 2</option>
        <option value="brand3">Hãng 3</option>
        <option value="brand4">Hãng 4</option>
    </select>
    <button type="submit">Tìm kiếm</button>
</form>

<div class="category-buttons">
    <button>Tất cả</button>
    <button>Hãng 1</button>
    <button>Hãng 2</button>
    <button>Hãng 3</button>
    <button>Hãng 4</button>
</div>

<div class="sort-buttons">
    <button>Phổ biến</button>
    <button>Mới nhất</button>
    <button>Bán chạy</button>
    <button>Giá</button>
</div>
