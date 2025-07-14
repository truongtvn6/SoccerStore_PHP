<?php
require_once '../lib/db.php';
require_once '../lib/products.php';

$products = getAllProducts($pdo);
$clubProducts = array_filter($products, function($product) {
    return $product['type'] === 'club';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        CÂU LẠC BỘ
    </title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
        </head>
        <body>
            <div id="wrapper">
                <div class="head">
                    <div class="search-container">
                        <input type="text" class="search-input" id="search-input" placeholder="Tìm kiếm">
                        <button class="search-button" onclick="search_Product();">
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
                    
                </div>
                <div class="headline">
                    <h3>ÁO CÂU LẠC BỘ</h3>
                </div>
                <ul class="products" id="products">
                    <?php foreach ($clubProducts as $product): ?>
                        <li>
                            <div class="product-item">
                                <div class="product-top">
                                    <a href="productdetail.php?productId=<?php echo $product['id']; ?>" class="product-thumb">
                                        <img src="../<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    </a>
                                    <a href="" class="buy-now">Mua ngay</a>
                                </div>
                                <div class="product-info">
                                    <a href="" class="product-cat"></a>
                                    <a href="" class="product-name"><?php echo htmlspecialchars($product['name']); ?></a>
                                    <div class="product-price"><?php echo number_format($product['price'], 3, ',', '.'); ?>vnđ</div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <script src="../js/script.js"></script>
        </body>
    </html>