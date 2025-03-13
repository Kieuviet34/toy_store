<?php
    $queryRevenue = "
    SELECT DATE(created_at) as revenue_date,
        SUM(amount) as total_amount
    FROM transactions
    WHERE transaction_status = 'success'
    AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) ASC
    ";
    $resultRevenue = $conn->query($queryRevenue);

    $labels = [];
    $dataset = [];
    while ($row = $resultRevenue->fetch_assoc()) {
    $labels[] = $row['revenue_date'];
    $dataset[] = (float)$row['total_amount'];
    }

    $labelsJson = json_encode($labels);
    $datasetJson = json_encode($dataset);

?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tổng quan</h1>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <a href="index.php?page=admin&action=orders#orders">
            <div class="card-body">
                <h5 class="card-title">Đơn hàng</h5>
                <p class="card-text"><?php echo number_format($totalOrders, 0, ',', '.'); ?></p>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <a href="index.php?page=admin&action=customers#customers">
            <div class="card-body">
                <h5 class="card-title">Khách hàng</h5>
                <p class="card-text"><?php echo number_format($totalCustomers, 0, ',', '.'); ?></p>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <a href="index.php?page=admin&action=staff#staff">
            <div class="card-body">
                <h5 class="card-title">Nhân viên</h5>
                <p class="card-text"><?php echo number_format($totalStaffs, 0, ',', '.'); ?></p>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <a href="index.php?page=admin&action=products#products">
            <div class="card-body">
                <h5 class="card-title">Sản phẩm</h5>
                <p class="card-text"><?php echo number_format($totalProducts, 0, ',', '.'); ?></p>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
            <a href="index.php?page=admin&action=brands#brands">
            <div class="card-body">
                <h5 class="card-title">Hãng</h5>
                <p class="card-text"><?php echo number_format($totalBrands, 0, ',', '.'); ?></p>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <a href="index.php?page=admin&action=categories#categories">
            <div class="card-body">
                <h5 class="card-title">Danh mục</h5>
                <p class="card-text"><?php echo number_format($totalCategories, 0, ',', '.'); ?></p>
            </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Doanh thu 30 ngày gần nhất</h3>
            <canvas id="revenueChart" width="400" height="150"></canvas>
        </div>
    </div>

<!-- Dùng chart.js để vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
<script>
    // Lấy dữ liệu từ PHP (JSON)
    const labels = <?php echo $labelsJson; ?>;      // Mảng ngày
    const dataSet = <?php echo $datasetJson; ?>;    // Mảng doanh thu

    // Cấu hình cho biểu đồ
    const data = {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (VND)',
            data: dataSet,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',   // Màu cột/bar
            borderColor: 'rgba(54, 162, 235, 1)',         // Màu viền
            borderWidth: 2
        }]
    };

    // Tùy chọn cho biểu đồ
    const config = {
        type: 'line', 
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Format tiền tệ
                        callback: function(value) {
                            // Tùy cách format, ví dụ 1,000,000
                            return value.toLocaleString() + '₫';
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Doanh thu 30 ngày gần nhất'
                },
                legend: {
                    display: false
                }
            }
        }
    };

    // Khởi tạo biểu đồ
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, config);
</script>
<style>
    a{
        text-decoration: none;
        color: white;
    }
</style>
