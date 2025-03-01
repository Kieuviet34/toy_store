<?php
session_start();
include '../inc/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$prod_id = isset($data['prod_id']) ? (int)$data['prod_id'] : 0;

if ($prod_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID sản phẩm không hợp lệ.']);
    exit;
}

// Lấy customer_id từ session
$customer_id = $_SESSION['user']['customer_id'];

// Kiểm tra xem khách hàng có đơn hàng đang chờ (order_status = 1) hay không
$query = "SELECT order_id FROM orders WHERE customer_id = ? AND order_status = 1 AND is_deleted = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Đơn hàng đã tồn tại
    $order = $result->fetch_assoc();
    $order_id = $order['order_id'];
} else {
    // Tạo đơn hàng mới
    $query = "INSERT INTO orders (customer_id, order_status, order_date, required_date, store_id, staff_id) 
              VALUES (?, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 1, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $customer_id);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Không thể tạo đơn hàng: ' . $stmt->error]);
        exit;
    }
    $order_id = $conn->insert_id;
}

// Kiểm tra xem sản phẩm đã có trong order_items chưa
$query = "SELECT item_id, quantity FROM order_items WHERE order_id = ? AND prod_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $order_id, $prod_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Cập nhật số lượng nếu sản phẩm đã tồn tại
    $item = $result->fetch_assoc();
    $new_quantity = $item['quantity'] + 1;
    $query = "UPDATE order_items SET quantity = ? WHERE item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $new_quantity, $item['item_id']);
} else {
    // Thêm sản phẩm mới vào order_items
    $query = "INSERT INTO order_items (order_id, item_id, prod_id, quantity, list_price, discount) 
              VALUES (?, (SELECT COALESCE(MAX(item_id), 0) + 1 FROM order_items WHERE order_id = ?), ?, 1, 
              (SELECT list_price FROM products WHERE prod_id = ?), 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiii', $order_id, $order_id, $prod_id, $prod_id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không thể thêm sản phẩm: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>