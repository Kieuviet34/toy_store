<?php
// index.php
$base_dir = __DIR__;

// Xử lý routing
$allowed_pages = ['home', 'shop', 'about', 'contact'];
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
    case 'contact':
        include 'template/contact.php';
        break;
    default:
        include 'template/404.php';
        break;
}

// Include footer
include 'footer.php';