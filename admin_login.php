<?php
session_start();
require_once 'lib/db.php';
require_once 'lib/users.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];

    $adminUser = getAdminUser($pdo);
    if ($adminUser && verifyPassword($password, $adminUser['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['user_id'] = $adminUser['id'];
        header('Location: admin/index.php');
        exit();
    } else {
        $error = 'Invalid admin password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Đăng nhập</title>
</head>
<body>
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
            font-weight: 500px;
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
            cursor: pointer;
        }
        input#btn-login:hover{
            opacity: 0.5;
        }
    </style>

    <div id="wrapper">
        <form action="" method="POST">
            <h3>Admin Đăng Nhập</h3>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <input type="password" id="password" name="password" required>
                <label for="password">Mật khẩu Admin</label>
            </div>
            <input type="submit" value="Đăng nhập" id="btn-login">
        </form>
    </div>
</body>
</html>