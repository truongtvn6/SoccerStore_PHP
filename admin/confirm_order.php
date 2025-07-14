<?php
require_once '../lib/orders.php';
require_once '../lib/users.php';
require_once '../lib/db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isUserAdmin($pdo, $_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $result = updateOrderStatus($pdo, $orderId, 'confirmed');
    
    if ($result) {
        $_SESSION['success_message'] = "Đơn hàng đã được xác nhận thành công.";
    } else {
        $_SESSION['error_message'] = "Có lỗi xảy ra khi xác nhận đơn hàng.";
    }
}

header('Location: manage_order.php');
exit();