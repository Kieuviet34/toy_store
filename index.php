<?php
session_start();
$base_dir = __DIR__;

// Xử lý routing
$allowed_pages = ['home', 'shop', 'about','cart', 'contact', 'login', 'register', 'admin', 'privacy','payment', 
                'product', 'checkout', 'logout', 'info','update_info','reset_password','forgot_password', 'vnpay', 'stripe',
                'thanks','show_brand_prod', 'change_password','view_history'];
$page = isset($_GET['page']) && in_array($_GET['page'], $allowed_pages) 
        ? $_GET['page'] 
        : '404';
if ($page === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php?page=home");
    exit();
}
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
    case 'checkout':
        include 'template/checkout.php';
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
    case 'forgot_password':
        include 'template/forgot_password.php';
        break;
    case 'reset_password':
        include 'template/reset_password.php';
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
    case 'info':
        include 'template/info.php';
        break;
    case 'update_info':
        include 'template/update_info.php';
        break;
    case 'vnpay':
        include 'template/vnpay_pay.php';
        break;
    case 'stripe':
        include 'src/stripe_payment.php';
        break;
    case 'change_password':
        include 'template/change_pass.php';
        break;
    case 'thanks':
        include 'template/thanks.php';
        break;
    case 'show_brand_prod':
        include 'template/show_brand_prod.php';
        break;
    case 'view_history':
        include 'template/view_history.php';
        break;
    default:
        include 'template/404.php';
        break;
}

// Include footer
if($page!='admin'){
    include 'footer.php';
}