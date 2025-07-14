<?php
require_once 'db.php';

function addToCart($pdo, $userId, $productId, $quantity) {
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
    return $stmt->execute([$userId, $productId, $quantity, $quantity]);
}

function getCartItems($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function updateCartItemQuantity($pdo, $userId, $productId, $quantity) {
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$quantity, $userId, $productId]);
}

function removeFromCart($pdo, $userId, $productId) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$userId, $productId]);
}

function clearCart($pdo, $userId) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $success = $stmt->execute([$userId]);
    if (!$success) {
        error_log("Failed to clear cart for user ID: $userId");
        throw new Exception("Failed to clear cart");
    }
    return true;
}