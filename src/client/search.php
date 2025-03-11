<?php
include '../../inc/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$query = isset($data['query']) ? trim($data['query']) : '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}
$sql = "SELECT p.prod_id, p.prod_name, b.brand_name 
        FROM products p 
        JOIN brands b ON p.brand_id = b.brand_id 
        WHERE p.is_deleted = 0 
        AND (LOWER(p.prod_name) LIKE LOWER(?) OR LOWER(b.brand_name) LIKE LOWER(?))
        LIMIT 10"; 

$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param('ss', $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        'prod_id' => $row['prod_id'],
        'prod_name' => $row['prod_name'],
        'brand_name' => $row['brand_name']
    ];
}

echo json_encode($suggestions);

$stmt->close();
$conn->close();
?>