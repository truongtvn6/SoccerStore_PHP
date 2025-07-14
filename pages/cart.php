<?php
session_start();
require_once '../lib/cart.php';
require_once '../lib/products.php';
require_once '../lib/db.php';

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $cartItems = getCartItems($pdo, $userId);

    $total = array_sum(array_map(function($item) {
        return $item['price'] * $item['quantity'];
    }, $cartItems));

    echo json_encode([
        'success' => true,
        'total' => number_format($total, 3, ',', '.')
    ]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$cartItems = getCartItems($pdo, $userId);
$isCartEmpty = empty($cartItems);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../css/cart.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <div class="head">
        <a href="home.php">
            <img src="../images/img/logo.jpg" id="logo" style="width: 100px; height: 100px;">
        </a>

            <div class="info">
                <ul class="list">
                    <a href="home.php"><li>Home</li></a> 
                    <a href="about.html"><li>About</li></a>
                    <a href="blog.html"><li>Blog</li></a>
                    <a href="contact.html"><li>Contact</li></a>
                </ul>
            </div>
            <div class="more">
                <select name="" id="moreoption">
                    <option value="" >Chọn sản phẩm</option>
                    <option value="homeclb.php" >Áo CLB</option>
                    <option value="homedtqg.php" >Áo ĐTQG</option>
                    <option value="shoes.php" >Giày</option>
                </select>
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-info">
                            <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <a href="../logout.php" class="logout-btn" title="ĐĂNG XUẤT">Đăng xuất</a>
                            <a href="#" title="THÔNG TIN TÀI KHOẢN">
                                <img src="../images/img/login.png" alt="User Profile">
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="../login.php" title="ĐĂNG NHẬP">
                            <img src="../images/img/login.png" alt="Login">
                        </a>
                    <?php endif; ?>
                </div>
    </div>

    <div class="cart-container">
        <h1>Giỏ hàng của bạn</h1>
        <div id="cart-items">
            <?php if (empty($cartItems)): ?>
                <p>Giỏ hàng của bạn đang trống</p>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                        <img src="../<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars(str_replace('"', '&quot;', $item['name'])); ?>" class="product-image">
                        <div class="product-info">
                            <p class="product-name"><?php echo htmlspecialchars($item['name']); ?></p>
                            <p class="product-price">Giá: <?php echo number_format($item['price'], 3,); ?> VND</p>
                            <p class="product-quantity">Số lượng: <?php echo $item['quantity']; ?></p>
                            <button class="remove-btn" onclick="removeFromCart(<?php echo $item['product_id']; ?>);">Xóa</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <p id="total-price">Tổng giá: <?php echo number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cartItems)), 3, ',', '.'); ?> VND</p>
        <button onclick="checkout(<?php echo $isCartEmpty ? 'true' : 'false'; ?>);" class="thanhtoan">Tiến hành thanh toán</button>
    </div>

    <script src="js/cart.js">
        
    </script>
        <script>
    function updateTotalPrice() {
        $.ajax({
            url: 'cart.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#total-price').text('Tổng giá: ' + data.total + ' VND');
                } else {
                    console.error('Không thể cập nhật tổng giá:', data.message);
                }
            },
            error: function() {
                console.error('Có lỗi xảy ra khi cập nhật tổng giá');
            }
        });
    }
    </script>
</body>
</html>
