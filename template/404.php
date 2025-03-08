<?php
// 404.php - Trang báo lỗi không tìm thấy
http_response_code(404);
?>

<?php ?>

<div class="error-container" style="text-align: center; padding: 100px 20px; min-height: 60vh;">
    <h1 style="font-size: 120px; color: #007bff; margin-bottom: 20px;">404</h1>
    <h2 style="font-size: 32px; margin-bottom: 20px;">Oops! Trang không tồn tại</h2>
    <p style="font-size: 18px; color: #666; margin-bottom: 40px;">
        Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc tạm thời không khả dụng.
    </p>
    
    <div class="action-buttons" style="display: flex; gap: 15px; justify-content: center;">
        <a href="index.php?page=home" 
           class="cta-button" 
           style="background-color: #007bff; color: white; padding: 12px 30px; 
                  text-decoration: none; border-radius: 5px; transition: 0.3s;">
            Về Trang Chủ
        </a>
        
        <a href="javascript:history.back()" 
           class="cta-button" 
           style="background-color: #e9ecef; color: #333; padding: 12px 30px;
                  text-decoration: none; border-radius: 5px; transition: 0.3s;">
            Quay Lại
        </a>
    </div>
</div>

<?php ?>