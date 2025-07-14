<?php
require_once '../lib/db.php';
require_once '../lib/products.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    if (deleteProduct($pdo, $id)) {
        header("Location: manage_items.php?success=1");
        exit();
    } else {
        header("Location: manage_items.php?error=1");
        exit();
    }
} else {
    header('Location: manage_items.php');
    exit;
}
