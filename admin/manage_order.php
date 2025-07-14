<?php include 'admin_header.php'; ?>
<?php require_once '../lib/orders.php'; ?>
<?php require_once '../lib/db.php'; ?>
<?php
$orders = getAllOrders($pdo);
usort($orders, function($a, $b) {
    return $a['id'] <=> $b['id'];
});
?>

<div class="admin-content-main">
    <div class="admin-content-main-title">
        <h1>Danh sách đơn hàng</h1>
    </div>
    <div class="admin-content-main-content">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Chi tiết</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Tùy chỉnh</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['name']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td><?php echo number_format($order['total_amount'], 3); ?></td>
                        <td>
                            <a href="list_item.php?id=<?php echo $order['id']; ?>" class="detail-btn">Xem</a>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a class="<?php echo $order['status'] === 'confirmed' ? 'detail-btn' : 'non-confirm-btn'; ?>">
                                <?php echo $order['status'] === 'confirmed' ? 'Đã xác nhận' : 'Chưa xác nhận'; ?>
                            </a>
                        </td>
                        <td>
                            <?php if ($order['status'] !== 'confirmed'): ?>
                                <a href="confirm_order.php?id=<?php echo $order['id']; ?>" class="confirm-btn" onclick="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?');">Xác nhận</a>
                            <?php else: ?>
                                <span class="confirmed-text">Xác nhận</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
</section>

<script src="../js/script.js"></script>
</body>
</html>