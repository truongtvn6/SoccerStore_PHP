<?php
require_once '../lib/products.php';
require_once '../lib/db.php';
include '../templates/header.php';
?>
        </div>

        <div id="abc">
        <div class="nav" id="nav">
            <img src="../images/img/banner.png" alt="Banner 1">

            <img src="../images/img/banner-2.jpg" alt="Banner 2">
        </div>
    <div class="main">
        <h3>SẢN PHẨM NỔI BẬT</h3>
        <div id="outstanding">
        <ul>
        <?php
            $outstandingProducts = getOutstandingProducts($pdo, 4);
            foreach ($outstandingProducts as $product) {
                $productType = $product['type'] === 'club' ? 'Club' : 'Nation';
                ?>
                    <li>
                        <div class='product-item'>
                            <div class='product-top'>
                                <a href='productdetail.php?productId=<?php echo $product['id']; ?>' class='product-thumb'>
                                    <img src='../<?php echo $product['image_url']; ?>' alt='<?php echo $product['name']; ?>'>
                                </a>
                            </div>
                            <div class='product-info'>
                                <a href='productdetail.php?productId=<?php echo $product['id']; ?>' class='product-cat'><?php echo $productType; ?></a>
                                <a href='productdetail.php?productId=<?php echo $product['id']; ?>' class='product-name'><?php echo $product['name']; ?></a>
                                <div class='product-price'><?php echo $product['price']; ?> vnđ</div>
                            </div>
                        </div>
                        <div class='buy-now'>
                            <a href='productdetail.php?productId=<?php echo $product['id']; ?>'>Xem chi tiết</a>
                        </div>
                    </li>
                <?php
            }                                                                                                                  
        ?>
        </ul>
    </div>
</div>
        <div class="contact">
            <div class="image-section">
                <img src="../images/img/Ronaldo_sporting.jpg" >
                <img src="../images/img/ronaldoMu1.jpg" >
                <img src="../images/img/RonaldoReal.jpg" >
                <img src="../images/img/RonaldoJuventus.jpg" >
                <img src="../images/img/RonaldoMu2.jpg" >
                <img src="../images/img/ronaldoalnassr.jpg" >
            </div>
            <div class="text-section">
                <h2>Chuyên cung cấp áo bóng đá và giày chất lượng tốt giao hàng nhanh</h2>
                <a href="contact.html" id="buy">Liên hệ ngay</a>
            </div>
        </div>
        </div>
        <?php
include '../templates/footer.php';
?>