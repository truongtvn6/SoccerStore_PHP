<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giày</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div id="wrapper">
        <div class="head">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Tìm kiếm" id="search-input">
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
            <h3>GIÀY</h3>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>