<?php
include 'database.php';

// PHÂN TRANG
$limit = 12;
$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$currentPage = max(1, $currentPage);
$startAt = $limit * ($currentPage - 1);

// Đếm tổng số sản phẩm
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM products");
$stmt->execute();
$countRow = $stmt->get_result()->fetch_assoc();
$totalProducts = $countRow['total'];
$totalPages = ceil($totalProducts / $limit);

// Lấy danh sách sản phẩm theo phân trang
$query = "SELECT p.prod_id,p.prod_img, p.prod_name, p.list_price, b.brand_name, c.cat_name 
          FROM products p
          JOIN brands b ON p.brand_id = b.brand_id
          JOIN categories c ON p.cat_id = c.cat_id
          WHERE p.is_deleted = 0
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $startAt);
$stmt->execute();
$result = $stmt->get_result();

// Tạo links phân trang
$links = "";
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        $links .= "<span class='current-page'>$i</span> ";
    } else {
        $links .= "<a href='index.php?page=shop&p=$i' class='page-link'>Page $i</a> ";
    }
}
?>
