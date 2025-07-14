<?php
session_start();
require_once '../lib/products.php';
require_once '../lib/db.php';

$productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;

$product = getProductById($pdo, $productId);

if (!$product) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết</title>
    <link rel="stylesheet" href="../css/productdetail.css">
</head>
<body>

    <div class="container">
        <div class="head">
            <a href="home.php"><img src="../images/img/logo.jpg" style="height: 100px; width: 100px;" id="logo"></a>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Tìm kiếm">
                <button class="search-button">
                    <img src="../images/img/211817_search_strong_icon.png" alt="Search">
                </button>
            </div>
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
                <div class="login">
                    <a href="cart.php" title="GIỎ HÀNG">
                        <img src="../images/img/shop.png" >
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])):
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
        <div class="product-detail">
           <div class="left">
            <h1 id="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>
            <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="product-image">
           </div>
            <div class="right">
                <p style="font-weight: 600;">Giá : <span id="product-price" style="font-weight: 600; color: red;"><?php echo number_format($product['price'], 3, ',', '.'); ?> VNĐ</span></p>
                <p id="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
        </div>
        <div class="thanhtoan">
            <button onclick="addToCart(<?php echo $product['id']; ?>);">Thêm vào giỏ hàng</button>
            <button onclick="buyNow(<?php echo $product['id']; ?>);">Mua ngay</button>
        </div>
    </div>

    <script>
function addToCart(productId, showAlert = true) {
    return fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (showAlert) {
                alert('Sản phẩm đã được thêm vào giỏ hàng');
            }
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
        return data.success;
    });
}

function buyNow(productId) {
    addToCart(productId, false).then(success => {
        if (success) {
            window.location.href = 'checkout.php';
        }
    });
}
    </script>
        <script src="js/cart.js">
        
        </script>
</body>
</html>