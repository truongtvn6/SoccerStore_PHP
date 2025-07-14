<?php
session_start();
require_once 'lib/cart.php';
require_once 'lib/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng']);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($productId > 0) {
    $result = removeFromCart($pdo, $userId, $productId);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm khỏi giỏ hàng']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
}