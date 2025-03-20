<div class="container">
    <!-- Banner tự động cuộn -->
    
    <div class="owl-carousel owl-theme">
        <div class="item">
            <a href="index.php?page=product&id=105">
            <img src="template/img/banner1.jpg" alt="" >
            </a>
        </div>
        <div class="item">
            <a href="index.php?page=product&id=106">
            <img src="template/img/banner2.jpg" alt="" >
            </a>
        </div>
        <div class="item">
            <a href="index.php?page=product&id=2">
            <img src="template/img/re-zero-kara-hajimeru-isekai-seikatsu-rem-artist-masterpiece-winter-maid-image-ver-taito.jpg" alt="" >
            </a>
        </div>
        <div class="item">
            <a href="index.php?page=product&id=3">
            <img src="template/img/one-piece-edward-newgate-portrait-of-pirates-maximum-megahouse.jpeg" alt="" >
            </a>
        </div>
        
</div>
    <!-- Phần hiển thị sản phẩm -->
    <div class="home-products">
        <h2 class="section-title mb-4">Sản phẩm nổi bật</h2>
        <?php include 'inc/product_grid.php';  ?>
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
    height: 100%; 
    object-fit:contain;
    display: block;
}
/* Responsive design */
@media (max-width: 768px) {
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
        autoplayTimeout:2000,
        autoplayHoverPause:true
    });
</script>