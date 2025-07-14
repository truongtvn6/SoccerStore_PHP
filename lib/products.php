<?php
require_once 'db.php';

function getAllProducts($pdo, $limit = null, $offset = 0) {
    $sql = "SELECT * FROM products ORDER BY id ASC";
    if ($limit !== null) {
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    } else {
        $stmt = $pdo->prepare($sql);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addProduct($pdo, $name, $description, $price, $image_url, $type) {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, type) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $description, $price, $image_url, $type]);
}

function updateProduct($pdo, $id, $name, $description, $price, $image_url) {
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    return $stmt->execute([$name, $description, $price, $image_url, $id]);
}

function deleteProduct($pdo, $id) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT image_url, type FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $stmt = $pdo->prepare("DELETE FROM order_items WHERE product_id = ?");
            $stmt->execute([$id]);

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                $image_path = IMAGES_PATH . '/' . ($product['type'] === 'club' ? 'Club' : 'Nation') . '/' . basename($product['image_url']);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        $pdo->commit();

        // Reorder IDs
        $stmt = $pdo->prepare("SET @count = 0; UPDATE products SET id = @count:= @count + 1;");
        $stmt->execute();

        return $result ?? false;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}

function getOutstandingProducts($pdo, $limit = 4) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE outstanding = 1 ORDER BY id LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getTotalProductCount($pdo) {
    $sql = "SELECT COUNT(*) as total FROM products";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}