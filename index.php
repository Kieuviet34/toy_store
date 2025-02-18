<?php
// index.php
$base_dir = __DIR__;

// Xử lý routing
$allowed_pages = ['home', 'shop', 'about','cart', 'contact', 'login', 'register', 'admin'];
$page = isset($_GET['page']) && in_array($_GET['page'], $allowed_pages) 
        ? $_GET['page'] 
        : 'home';

// Include header
include 'header.php';

// Include nội dung trang
switch ($page) {
    case 'home':
        include 'template/homepage.php';
        break;
    case 'shop':
        include 'template/shop.php';
        break;
    case 'about':
        include 'template/about.php';
        break;
    case 'cart':
        include 'template/cart.php';
        break;
    case 'contact':
        include 'template/contact.php';
        break;
    case 'login':
        include 'template/login.php';
        break;
    case 'register':
        include 'template/register.php';
        break;
    case 'admin':
        include 'template/Admin';
        break;
    default:
        include 'template/404.php';
        break;
}

// Include footer
include 'footer.php';