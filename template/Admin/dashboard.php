<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tổng quan</h1>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Đơn hàng</h5>
                <p class="card-text"><?php echo number_format($totalOrders, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Khách hàng</h5>
                <p class="card-text"><?php echo number_format($totalCustomers, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Nhân viên</h5>
                <p class="card-text"><?php echo number_format($totalStaffs, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Sản phẩm</h5>
                <p class="card-text"><?php echo number_format($totalProducts, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Danh mục</h5>
                <p class="card-text"><?php echo number_format($totalCategories, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
</div>