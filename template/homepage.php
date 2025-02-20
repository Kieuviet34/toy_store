<div class="container">
    <!-- Banner tự động cuộn -->
    <div class="auto-scroll-banner mb-5">
        <div class="banner-container">
            <div class="banner-slide">
                <img src="template/img/banner1.jpg" alt="Banner 1" class="img-fluid"
                height="100px">
            </div>
            <div class="banner-slide">
                <img src="template/img/banner2.jpg" alt="Banner 2" class="img-fluid" height="100px">
            </div>
            <div class="banner-slide">
                <img src="template/img/banner3.jpg" alt="Banner 3" class="img-fluid" height="100px">
            </div>
        </div>
    </div>

    <!-- Phần hiển thị sản phẩm -->
    <div class="home-products">
        <h2 class="section-title mb-4">Sản phẩm nổi bật</h2>
        <?php include 'product_grid.php';  ?>
    </div>
</div>

<style>
/* CSS cho banner tự động cuộn */
.auto-scroll-banner {
    overflow: hidden;
    position: relative;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.banner-container {
    display: flex;
    animation: slide 15s infinite;
}

.banner-slide {
    min-width: 100%;
    position: relative;
}

@keyframes slide {
    0%, 20% { transform: translateX(0); }
    25%, 45% { transform: translateX(-100%); }
    50%, 70% { transform: translateX(-200%); }
    75%, 95% { transform: translateX(-300%); }
    100% { transform: translateX(0); }
}

/* Responsive design */
@media (max-width: 768px) {
    .banner-container {
        animation-duration: 12s;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
}

.home-products {
    padding: 2rem 0;
    border-top: 2px solid #eee;
}
</style>

<script>
document.querySelector('.banner-container').addEventListener('mouseenter', () => {
    document.querySelector('.banner-container').style.animationPlayState = 'paused';
});

document.querySelector('.banner-container').addEventListener('mouseleave', () => {
    document.querySelector('.banner-container').style.animationPlayState = 'running';
});
</script>