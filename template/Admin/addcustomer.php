<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);

    if (empty($f_name) || empty($l_name) || empty($email) || empty($phone) || empty($city) || empty($street)) {
        $error = "Vui lòng điền đầy đủ tất cả các trường.";
    } else {
        $insert_query = "INSERT INTO customers (f_name, l_name, email, phone, city, street, is_deleted) VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ssssss', $f_name, $l_name, $email, $phone, $city, $street);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success" role="alert">Thêm khách hàng thành công!</div>';
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=admin#customers"; }, 2000);</script>';
            exit;
        } else {
            $error = "Không thể thêm khách hàng: " . $stmt->error;
        }
    }
}
?>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm khách hàng</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-warning" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row mb-3">
                    <label for="f_name" class="col-sm-3 col-form-label">Tên</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="f_name" name="f_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="l_name" class="col-sm-3 col-form-label">Họ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="l_name" name="l_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="phone" class="col-sm-3 col-form-label">Số điện thoại</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="city" class="col-sm-3 col-form-label">Thành phố</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="street" class="col-sm-3 col-form-label">Địa chỉ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="street" name="street" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                    <a href="index.php?page=admin#customers" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>