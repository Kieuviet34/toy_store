<div class="container">
    <!-- Banner tự động cuộn -->
    
    <div class="owl-carousel owl-theme">
        <div class="item">
            <img src="template/img/banner1.jpg" alt="" >
        </div>
        <div class="item">
        <img src="template/img/banner2.jpg" alt="" >
        </div>
        <div class="item">
        <img src="template/img/banner3.jpg" alt="" >
        </div>
</div>
    <!-- Phần hiển thị sản phẩm -->
    <div class="home-products">
        <h2 class="section-title mb-4">Sản phẩm nổi bật</h2>
        <?php include 'product_grid.php';  ?>
    </div>
</div>

<style>
.owl-carousel .item img {
    width: 100%;
    height: auto;
    display: block;
    max-height: 100%;
    max-width: 100%;
}

.owl-carousel .item img {
    width: 100%;
    height: 300px; 
    object-fit:contain;
    display: block;
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
    var owl = $('.owl-carousel');
owl.owlCarousel({
    items:1,
    loop:true,
    margin:10,
    autoplay:true,
    autoplayTimeout:1000,
    autoplayHoverPause:true
});
</script>