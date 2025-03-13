<?php
session_start();
include '../../inc/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập để thanh toán.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = isset($data['order_id']) ? (int)$data['order_id'] : 0;
$status = isset($data['status']) ? (int)$data['status'] : 0;

if ($order_id <= 0 || $status <= 0) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ.']);
    exit;
}

$customer_id = $_SESSION['user']['customer_id'];

// Bắt đầu transaction của MySQL
$conn->begin_transaction();

try {
    // Cập nhật trạng thái đơn hàng (ví dụ: status = 2 cho thanh toán thành công)
    $updateQuery = "UPDATE orders SET order_status = ? WHERE order_id = ? AND customer_id = ? AND is_deleted = 0";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("iii", $status, $order_id, $customer_id);
    if (!$stmt->execute() || $stmt->affected_rows <= 0) {
        throw new Exception("Không thể cập nhật đơn hàng.");
    }
    $stmt->close();

    // Tính tổng số tiền của đơn hàng từ bảng order_items
    $totalQuery = "SELECT SUM(list_price * quantity * (1 - discount)) as total FROM order_items WHERE order_id = ?";
    $stmtTotal = $conn->prepare($totalQuery);
    $stmtTotal->bind_param("i", $order_id);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result()->fetch_assoc();
    $amount = isset($resultTotal['total']) ? (float)$resultTotal['total'] : 0;
    $stmtTotal->close();

    // Tạo payment_id dựa trên thời gian hiện tại (YmdHis)
    $payment_id = date('YmdHis');

    // Insert giao dịch vào bảng transactions với phương thức thanh toán 'cash'
    $insertQuery = "INSERT INTO transactions (order_id, customer_id, amount, payment_method, payment_id, payment_status, payment_details, transaction_status) VALUES (?, ?, ?, 'cash', ?, 'success', '', 'success')";
    $stmtInsert = $conn->prepare($insertQuery);
    $stmtInsert->bind_param("iids", $order_id, $customer_id, $amount, $payment_id);
    if (!$stmtInsert->execute() || $stmtInsert->affected_rows <= 0) {
        throw new Exception("Không thể ghi nhận giao dịch.");
    }
    $stmtInsert->close();

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
