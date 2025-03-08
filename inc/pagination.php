<?php
include 'database.php';

// PHÂN TRANG
$limit = 12;
$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$currentPage = max(1, $currentPage);
$startAt = $limit * ($currentPage - 1);

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'prod_name';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
$filter_brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_price_min = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? (float)$_GET['price_min'] : null;
$filter_price_max = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? (float)$_GET['price_max'] : null;

$total_query = "SELECT COUNT(*) as total 
                FROM products p
                JOIN brands b ON p.brand_id = b.brand_id
                JOIN categories c ON p.cat_id = c.cat_id
                WHERE p.is_deleted = 0";
$params = [];
$types = '';

if ($filter_brand) {
    $total_query .= " AND b.brand_name = ?";
    $params[] = $filter_brand;
    $types .= 's';
}
if ($filter_category) {
    $total_query .= " AND c.cat_name = ?";
    $params[] = $filter_category;
    $types .= 's';
}
if ($filter_price_min !== null) {
    $total_query .= " AND p.list_price >= ?";
    $params[] = $filter_price_min;
    $types .= 'd';
}
if ($filter_price_max !== null) {
    $total_query .= " AND p.list_price <= ?";
    $params[] = $filter_price_max;
    $types .= 'd';
}

$stmt = $conn->prepare($total_query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$countRow = $stmt->get_result()->fetch_assoc();
$totalProducts = $countRow['total'];
$totalPages = ceil($totalProducts / $limit);

$query = "SELECT p.prod_id, p.prod_img, p.prod_name, p.list_price, b.brand_name, c.cat_name 
          FROM products p
          JOIN brands b ON p.brand_id = b.brand_id
          JOIN categories c ON p.cat_id = c.cat_id
          WHERE p.is_deleted = 0";

$params = [];
$types = '';
if ($filter_brand) {
    $query .= " AND b.brand_name = ?";
    $params[] = $filter_brand;
    $types .= 's';
}
if ($filter_category) {
    $query .= " AND c.cat_name = ?";
    $params[] = $filter_category;
    $types .= 's';
}
if ($filter_price_min !== null) {
    $query .= " AND p.list_price >= ?";
    $params[] = $filter_price_min;
    $types .= 'd';
}
if ($filter_price_max !== null) {
    $query .= " AND p.list_price <= ?";
    $params[] = $filter_price_max;
    $types .= 'd';
}

$allowed_sort_columns = ['prod_name', 'list_price', 'brand_name', 'cat_name'];
$sort_by = in_array($sort_by, $allowed_sort_columns) ? $sort_by : 'prod_name';
$sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';
$query .= " ORDER BY " . $conn->real_escape_string($sort_by) . " " . $sort_order;

$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $startAt;
$types .= 'ii';

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$links = "";
for ($i = 1; $i <= $totalPages; $i++) {
    $url = "index.php?page=shop&p=$i" .
           ($filter_brand ? "&brand=" . urlencode($filter_brand) : '') .
           ($filter_category ? "&category=" . urlencode($filter_category) : '') .
           ($filter_price_min !== null ? "&price_min=$filter_price_min" : '') .
           ($filter_price_max !== null ? "&price_max=$filter_price_max" : '') .
           "&sort_by=$sort_by&sort_order=$sort_order";
    if ($i == $currentPage) {
        $links .= "<span class='current-page'>$i</span> ";
    } else {
        $links .= "<a href='$url' class='page-link'>$i</a> ";
    }
}
?>