<?php
session_start();
require_once 'lib/users.php';
require_once 'lib/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu không khớp';
    } else {
        $result = registerUser($pdo, $username, $email, $password, $is_admin);
        if ($result === true) {
            header('Location: login.php?registered=1');
            exit();
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
        *{
            box-sizing:border-box;
        }
        #wrapper{
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:80vh;
        }
        body{    
             font-family: "Montserrat", sans-serif;
             font-size:17px;

        }
        form{
            border:1px solid #dadce0;
            border-radius: 5px; 
            padding: 30px;
        }
        h3{
            text-align: center;
            font-size: 24px;
            font-weight: 500px; /* Độ đậm*/
        }
        .form-group{
            margin-bottom: 15px;
            position: relative;
            
            
        }
        input{
            height: 50px;
            width: 300px;
            outline: none;
            border: 1px solid #dadce0;
            padding: 10px;
            border-radius: 5px;
            font-size: inherit;
        }
        label{
            position: absolute;
            padding: 0px 5px;
            left: 5px;
            top: 50%;
            pointer-events: none;
            transform: translateY(-50%);
            background:#fff;
            transition: 0.3s ease-in-out ;
        }
        .form-group input:focus {
            border: 2px solid #1a73e8;
        }
        .form-group input:focus + label, .form-group input:valid + label {
            top: 0px;
            font-size: 13px;
            font-weight: 500;
            color: blue;
        }
        input#btn-login{
            background: #1a73e8;
            color: #fff;
            cursor: pointer; /* kiểu con trỏ khi di chuyển qua */
        }
        input#btn-login:hover{
            opacity: 0.5; /* Độ mờ khi rê chuột qua*/
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <form action="" method="POST">
            <h3>Đăng Ký</h3>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <input type="text" id="username" name="username" required>
                <label for="username">Tên đăng nhập</label>
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" required>
                <label for="email">Email</label>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required>
                <label for="password">Mật khẩu</label>
            </div>
            <div class="form-group">
                <input type="password" id="confirm_password" name="confirm_password" required>
                <label for="confirm_password">Xác nhận mật khẩu</label>
            </div>
            <input type="submit" value="Đăng ký" id="btn-register">
        </form>
    </div>
</body>
</html>