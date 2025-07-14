<?php
include 'admin_header.php';
require_once '../lib/orders.php';
require_once '../lib/db.php';

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $order = getOrderById($pdo, $orderId);
    $orderItems = getOrderItems($pdo, $orderId);
}
?>
            <div class="admin-content-main">
                <div class="admin-content-main-title">
                    <h1>Chi tiết đơn hàng</h1>
                </div>
                <div class="admin-content-main-content">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = 0;
                            foreach ($orderItems as $item):
                                $subtotal = $item['price'] * $item['quantity'];
                                $totalAmount += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td>
                        <?php
                        $image_path = !empty($item['image_url']) ? "../" . $item['image_url'] : 'default_image_path.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars(isset($item['name']) ? $item['name'] : 'Product'); ?>" style="max-width: 100px; max-height: 100px;">
                    </td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo number_format($item['price'], 3, ',', '.'); ?>đ</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($subtotal, 3, ',', '.'); ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td style="font-weight: 700;" colspan="5">Tổng cộng</td>
                                <td style="font-weight: 700;"><?php echo number_format($totalAmount, 3, ',', '.'); ?>đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include 'admin_footer.php'; ?>
</body>
</html>