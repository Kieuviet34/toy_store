<?php
include 'inc/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['cat_name']);

    if (empty($cat_name)) {
        $error = "Vui lòng điền tên danh mục.";
    } else {
        $insert_query = "INSERT INTO categories (cat_name) VALUES (?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('s', $cat_name);

        if ($stmt->execute()) {
            header('Location: index.php?page=admin#categories');
            exit;
        } else {
            $error = "Không thể thêm danh mục: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 50px auto;
        }
        .card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1rem;
        }
        .error {
            color: #dc3545;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .form-control, .form-select, .form-check-input, .form-check-label {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }
        .d-flex.justify-content-end.gap-2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#dashboard">
                                <i class="bi bi-house me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#orders">
                                <i class="bi bi-package me-2"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#customers">
                                <i class="bi bi-people me-2"></i> Khách hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#staff">
                                <i class="bi bi-person-check me-2"></i> Nhân viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=admin#products">
                                <i class="bi bi-box me-2"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php?page=admin#categories">
                                <i class="bi bi-list-ul me-2"></i> Danh mục
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="form-container">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="bi bi-plus-square me-2"></i>Thêm danh mục</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <p class="error"><?php echo htmlspecialchars($error); ?></p>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="row mb-3">
                                    <label for="cat_name" class="col-sm-3 col-form-label">Tên danh mục</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="cat_name" name="cat_name" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Thêm</button>
                                    <a href="index.php?page=admin#categories" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>