<?php include 'admin_header.php'; ?>
            <?php
        require_once '../lib/db.php';
        require_once '../lib/products.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['product-name'];
            $price = $_POST['product-price'];
            $description = $_POST['product-description'];
            $type = $_POST['product-type'];
            $image_url = '';

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
                    echo "<script>alert('Lỗi khi tải lên hình ảnh');</script>";
                }
            }

            if (addProduct($pdo, $name, $description, $price, $image_url, $type)) {
                echo "<script>alert('Thêm sản phẩm thành công');</script>";
            } else {
                echo "<script>alert('Thêm sản phẩm thất bại');</script>";
            }
        }
        ?>
        <div class="admin-content-main">
            <div class="admin-content-main-title">
                <h1>Thêm sản phẩm mới</h1>
            </div>
            <div class="admin-content-main-content">
                <div class="admin-content-main-content-product">
                    <form class="product-form" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product-name">Tên sản phẩm</label>
                            <input type="text" id="product-name" name="product-name" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="form-group">
                            <label for="product-price">Giá sản phẩm</label>
                            <input type="number" id="product-price" name="product-price" placeholder="Nhập giá sản phẩm" required>
                        </div>
                        <div class="form-group">
                            <label for="product-description">Mô tả sản phẩm</label>
                            <textarea id="product-description" name="product-description" placeholder="Nhập mô tả sản phẩm" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product-type">Loại áo</label>
                            <select id="product-type" name="product-type" required>
                                <option value="">Chọn loại áo</option>
                                <option value="club">Áo câu lạc bộ</option>
                                <option value="national">Áo tuyển quốc gia</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product-image">Hình ảnh sản phẩm</label>
                            <input type="file" id="product-image" name="product-image" accept="image/*">
                        </div>
                        <button type="submit" class="btn-submit">Thêm sản phẩm</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
<?php include 'admin_footer.php'; ?>
</body>
</html>
