<?php
ob_start();
include '../../inc/database.php';
ob_clean();

header("Content-Type: application/json");

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = (int)$_GET['id'];

    switch ($type) {
        case 'order':
            $stmt = $conn->prepare("UPDATE orders SET is_deleted = 1 WHERE order_id = ?");
            break;
        case 'customer':
            $stmt = $conn->prepare("UPDATE customers SET is_deleted = 1 WHERE customer_id = ?");
            break;
        case 'staff':
            $stmt = $conn->prepare("UPDATE staffs SET is_deleted = 1 WHERE staff_id = ?");
            break;
        case 'product':
            $stmt = $conn->prepare("UPDATE products SET is_deleted = 1 WHERE prod_id = ?");
            break;
        case 'category':
            $stmt = $conn->prepare("DELETE FROM categories WHERE cat_id = ?");
            break;
        case 'brands':
            $stmt = $conn->prepare("DELETE FROM brands WHERE brand_id = ?");
            break;
        default:
            echo json_encode(["status" => "error", "error" => "Invalid type."]);
            exit;
    }

    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "id" => $id, "type" => $type]);
            exit;
        } else {
            echo json_encode(["status" => "error", "error" => $stmt->error]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "error" => $conn->error]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "error" => "Missing parameters."]);
    exit;
}
?>
