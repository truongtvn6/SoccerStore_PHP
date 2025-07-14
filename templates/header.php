<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="container">
        <div class="head">
            <a href="../pages/home.php">
                <img src="../images/img/logo.jpg" style="height: 100px; width: 100px;">
            </a>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Tìm kiếm" id="search-input">
                <button class="search-button" onclick="search_Product();">
                    <img src="../images/img/211817_search_strong_icon.png" alt="Search">
                </button>
            </div>
                <div class="info">
                    <ul class="list">
                        <a href="../pages/home.php"><li>Home</li></a> 
                        <a href="../pages/about.html"><li>About</li></a>
                        <a href="../pages/blog.html"><li>Blog</li></a>
                        <a href="../pages/contact.html"><li>Contact</li></a>
                    </ul>
                </div>
                <div class="more">
                    <select name="" id="moreoption">
                        <option value="" >Chọn sản phẩm</option>
                        <option value="../pages/homeclb.php" >Áo CLB</option>
                        <option value="../pages/homedtqg.php" >Áo ĐTQG</option>
                        <option value="../pages/shoes.php" >Giày</option>
                    </select>
                </div>
                <div class="login">
                    <a href="../pages/cart.php" title="GIỎ HÀNG">
                        <img src="../images/img/shop.png" >
                    </a>
                    
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
