<?php
require_once 'db.php';

function getAllOrders($pdo) {
    $stmt = $pdo->prepare("
        SELECT *
        FROM orders
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT o.*, c.name, c.phone, c.email, c.address FROM orders o JOIN customer_info c ON o.id = c.order_id WHERE o.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getOrderItems($pdo, $orderId) {
    $stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}

function createOrder($pdo, $userId, $totalAmount, $customerName, $phone, $email, $address, $paymentMethod) {
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method, name, phone, email, address) VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$userId, $totalAmount, $paymentMethod, $customerName, $phone, $email, $address]);
        
        if (!$success) {
            error_log("SQL Error in createOrder: " . implode(", ", $stmt->errorInfo()));
            throw new Exception("Failed to execute order insertion statement");
        }
        
        $orderId = $pdo->lastInsertId();
        
        if (!$orderId) {
            throw new Exception("Failed to get last insert ID");
        }
        
        error_log("Order created successfully. Order ID: " . $orderId);
        return $orderId;
    } catch (Exception $e) {
        error_log("Error creating order: " . $e->getMessage());
        throw $e;
    }
}

function addOrderItem($pdo, $orderId, $productId, $quantity, $price) {
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$orderId, $productId, $quantity, $price]);
    if (!$success) {
        error_log("Failed to add order item. Order ID: $orderId, Product ID: $productId");
        throw new Exception("Failed to add order item");
    }
    return true;
}

function updateOrderStatus($pdo, $orderId, $status) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $orderId]);
}
function getOrdersByUserId($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}