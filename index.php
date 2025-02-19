<?php
// index.php
$base_dir = __DIR__;

// Xử lý routing
$allowed_pages = ['home', 'shop', 'about','cart', 'contact', 'login', 'register', 'admin', 'privacy','payment', 'product'];
$page = isset($_GET['page']) && in_array($_GET['page'], $allowed_pages) 
        ? $_GET['page'] 
        : 'home';

// Include header
if($page  != 'admin'){
    include 'header.php';
}

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
    case 'product':
        include 'template/product.php';
        break;
    case 'privacy':
        include 'template/privacy.php';
        break;
    case 'payment':
        include 'template/payment.php';
        break;
    case 'admin':
        include 'template/Admin/index.php';
        break;
    default:
        include 'template/404.php';
        break;
}

// Include footer
if($page!='admin'){
    include 'footer.php';
}