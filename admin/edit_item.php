<?php 
include 'admin_header.php';
require_once '../lib/db.php';
require_once '../lib/products.php';

$product = null;
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = getProductById($pdo, $product_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product-id'];
    $name = $_POST['product-name'];
    $price = $_POST['product-price'];
    $description = $_POST['product-description'];
    $type = $_POST['product-type'];
    $image_url = $_POST['current-image'];

    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] == 0) {
        $image_dir = $type === 'club' ? 'Club' : 'Nation';
        $target_dir = __DIR__ . "/../images/{$image_dir}/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['product-image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['product-image']['tmp_name'], $target_file)) {
            $image_url = "images/{$image_dir}/" . $file_name;
        } else {
            echo "<script>alert('Lỗi khi tải lên hình ảnh mới');</script>";
        }
    }

    if (updateProduct($pdo, $id, $name, $description, $price, $image_url)) {
        echo "<script>alert('Cập nhật sản phẩm thành công'); window.location.href = 'manage_items.php';</script>";
    } else {
        echo "<script>alert('Cập nhật sản phẩm thất bại');</script>";
    }
}
?>

<div class="admin-content-main">
    <div class="admin-content-main-title">
        <h1>Sửa sản phẩm</h1>
    </div>
    <div class="admin-content-main-content">
        <div class="admin-content-main-content-product">
            <?php if ($product): ?>
                <form class="product-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product-id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="current-image" value="<?php echo $product['image_url']; ?>">
                    <div class="form-group">
                        <label for="product-name">Tên sản phẩm</label>
                        <input type="text" id="product-name" name="product-name" value="<?php echo $product['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="product-price">Giá sản phẩm</label>
                        <input type="number" id="product-price" name="product-price" value="<?php echo $product['price']; ?>" required step="any">
                    </div>
                    <div class="form-group">
                        <label for="product-description">Mô tả sản phẩm</label>
                        <textarea id="product-description" name="product-description" rows="4"><?php echo $product['description']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="product-type">Loại áo</label>
                        <select id="product-type" name="product-type" required>
                            <option value="club" <?php echo $product['type'] === 'club' ? 'selected' : ''; ?>>Áo câu lạc bộ</option>
                            <option value="national" <?php echo $product['type'] === 'national' ? 'selected' : ''; ?>>Áo tuyển quốc gia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product-image">Hình ảnh sản phẩm</label>
                        <input type="file" id="product-image" name="product-image" accept="image/*">
                        <?php if ($product['image_url']): ?>
                            <img src="<?php echo $product['image_url']; ?>" alt="Current product image" style="max-width: 200px; margin-top: 10px;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn-submit">Cập nhật sản phẩm</button>
                </form>
            <?php else: ?>
                <p>Không tìm thấy sản phẩm.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>