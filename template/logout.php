<?php
// Kiểm tra xem session đã được khởi động chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


session_unset(); 
session_destroy(); 


header("Location: ../index.php?page=home");
exit();
?>