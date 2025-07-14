<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/admin.css">
    <title>Admin</title>
</head>
<body>
    <section class="admin">
    <div class="row-grid">
        <div class="admin-sidebar">
            <div class="admin-sidebar-top">
                <a href="index.php">
                    <img src="../images/logo.jpg" alt="logo">
                </a>
            </div>
            <div class="admin-sidebar-content">
                <ul>
                    <li><a href="index.php"><i class="ri-dashboard-line"></i>Dashboard<i class="ri-archive-drawer-line"></i></a></li>
                    <li><a href=""><i class="ri-file-list-line"></i>Đơn hàng<i class="ri-add-box-line"></i></a>
                        <ul class="sub-menu">
                            <li><a href="manage_order.php"><i class="ri-arrow-right-s-fill"></i>Danh sách đơn hàng</a></li>
                        </ul>
                    </li>
                    <li><a href=""><i class="ri-file-list-line"></i>Sản phẩm<i class="ri-add-box-line"></i></a>
                        <ul class="sub-menu">
                            <li><a href="manage_items.php"><i class="ri-arrow-right-s-fill"></i>Danh sách sản phẩm</a></li>
                            <li><a href="add_item.php"><i class="ri-arrow-right-s-fill"></i>Thêm</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="admin-content">
            <div class="admin-content-top">
                <div class="admin-content-top-left">
                    <ul>
                        <li>
                            <i class="ri-search-line"></i>
                            <i class="ri-drag-move-line"></i>
                        </li>
                    </ul>
                </div>
                <div class="admin-content-top-right">
                    <ul>
                        <li>
                            <i class="ri-notification-line"></i>
                        </li>
                        <li>
                            <i class="ri-message-line"></i>
                        </li>
                        <li>
                            <img style="width: 40px;" src="../images/login.png" alt="logo">
                            <span>Admin</span><i class="ri-arrow-down-s-fill"></i>
                        </li>
                    </ul>
                </div>
            </div>