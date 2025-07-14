<?php
session_start();
require_once '../lib/orders.php';
require_once '../lib/db.php';
require_once '../lib/cart.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$cartItems = getCartItems($pdo, $_SESSION['user_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../lib/orders.php';
    require_once '../lib/cart.php';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];
    $userId = $_SESSION['user_id'];
    
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    error_log("Received POST data: " . print_r($_POST, true));
    error_log("User ID: " . $userId);
    error_log("Total Amount: " . $totalAmount);
    try {
        $pdo->beginTransaction();
    
        $orderId = createOrder($pdo, $userId, $totalAmount, $name, $phone, $email, $address, $paymentMethod);
        if (!$orderId) {
            throw new \Exception("Failed to create order");
        }
    
        foreach ($cartItems as $item) {
            if (!addOrderItem($pdo, $orderId, $item['product_id'], $item['quantity'], $item['price'])) {
                throw new \Exception("Failed to add item to order");
            }
        }
    
        $pdo->commit();
        error_log("Order process completed successfully");
        $success_message = "Cảm ơn bạn đã đặt hàng! Đơn hàng của bạn đã được xử lý thành công.";
        $cartItems = [];
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'truomgu123@gmail.com';
        $mail->Password = 'meet xhsp hjrx ssez';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        $mail->setFrom('truomgu123@gmail.com', 'Tứ Trường');
        $mail->addAddress($email);
    
        $mail->isHTML(true);
        $mail->Subject = 'Hóa đơn đơn hàng';
        $mail->Body    = 'Cảm ơn bạn đã đặt hàng! Đây là hóa đơn của bạn: <br> Tổng cộng: ' . number_format($totalAmount, 3, ',', '.') . ' ₫<br> Các mặt hàng đã mua:<br>';
           $cartItems = getCartItems($pdo, $_SESSION['user_id']);
    error_log("Cart Items: " . print_r($cartItems, true));
        foreach ($cartItems as $item) {
            $mail->Body .= '<div>';
            $mail->Body .= '<strong>' . htmlspecialchars($item['name']) . '</strong><br>';
            if (filter_var($item['image_url'], FILTER_VALIDATE_URL)) {
                $mail->Body .= '<img src="' . htmlspecialchars($item['image_url']) . '" alt="' . htmlspecialchars($item['name']) . '"><br>';
            } else {
                error_log("Invalid image URL: " . $item['image_url']);
            }
            $mail->Body .= 'Giá: ' . number_format($item['price'], 3, ',', '.') . ' ₫<br>';
            $mail->Body .= 'Số lượng: ' . htmlspecialchars($item['quantity']) . '<br><br>';
            $mail->Body .= '</div>';
        }
        $mail->send();
        error_log("Hóa đơn đã được gửi đến email: " . $email);
    } catch (\Exception $e) {
        error_log("Không thể gửi email: {$mail->ErrorInfo}");
        $error_message = "Có lỗi xảy ra khi gửi hóa đơn. Vui lòng kiểm tra email của bạn.";
    }
    if (!clearCart($pdo, $userId)) {
        throw new \Exception("Failed to clear cart");
    }
}
?>
<script>
var cartItems = <?php echo json_encode($cartItems ?? []); ?>;
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="../css/checkout.css"> 
</head>
<body>
    <div class="checkout-container">
        <div class="head">
            <a href="home.php">
                <img src="../images/img/logo.jpg" id="logo" style="height: 100px; width: 100px;">
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
    </div>
    <h1>Thanh toán </h1>
        <div class="main">
            
        
        <?php if (isset($success_message)):
    <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
<?php elseif (isset($error_message)):
    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

        <form id="checkout-form" action="checkout.php" method="POST">
    <label for="name">Họ và tên:</label>
    <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="address">Địa chỉ giao hàng:</label>
                <input type="text" id="address" name="address" required>

                <label for="payment-method">Phương thức thanh toán:</label>
                <select id="payment-method" name="payment_method" required>
                    <option value="credit-card">Thẻ tín dụng</option>
                    <option value="cod" selected>Thanh toán khi nhận hàng (COD)</option>
                </select>

                <button type="submit">Xác nhận thanh toán</button>
            </form>

            <img src="../images/img/Thanhks.jpg" alt="">    
        </div>
        
        <div class="order-summary">
            <h2>Đơn hàng của bạn</h2>
            <table id="order-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody id="order-items">
                    <!-- Các mục đơn hàng sẽ được thêm vào đây bằng JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                            <td><strong>Tổng cộng:</strong></td>
                        <td id="total-price"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

        </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Form will be submitted normally, no need for preventDefault()
        
        // Clear the cart in localStorage after successful submission
        localStorage.removeItem('cart');
    });


        // New function to display order summary
        function displayOrderSummary() {
            const orderItems = document.getElementById('order-items');
            const totalPriceElement = document.getElementById('total-price');
            let totalPrice = 0;

            orderItems.innerHTML = '';

            cartItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${parseFloat(item.price).toLocaleString('vi-VN', {minimumFractionDigits: 3, maximumFractionDigits: 3})} ₫</td>
                    <td>${item.quantity}</td>
                `;
                orderItems.appendChild(row);
                totalPrice += parseFloat(item.price) * item.quantity;
            });

            // Cập nhật tổng giá với định dạng mới
            totalPriceElement.textContent = totalPrice.toLocaleString('vi-VN', {minimumFractionDigits: 3, maximumFractionDigits: 3}) + ' ₫';
        }

        // Call the function to display the order summary
        displayOrderSummary();
    });
    </script>
</body>
</html>
