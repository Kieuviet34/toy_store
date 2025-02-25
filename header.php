<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AllainStore</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/headers/">
    <link rel="stylesheet" href="template/css/style.css">
    <link rel="stylesheet" href="template/css/login.css">
    <link rel="stylesheet" href="template/css/homepage.css">
    <link rel="stylesheet" href="template/css/about.css">
    <link rel="stylesheet" href="template/css/contact.css">
    <link rel="stylesheet" href="template/css/product_grid.css">
    <link rel="stylesheet" href="template/assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="template/css/header.css">
    <link rel="stylesheet" href="template/css/product.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="template/assets/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css">
    <script src="jquery.min.js"></script>
    <script src="OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
</head>
<body>
<header>
    <div class="px-3 py-2 bg-dark text-white">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="index.php?page=home" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
          <img src="template/img/allainstore.jpg" class="img-fluid  img-thumbnail rounded-circle "
          width="50px"> Allain Store
          </a>

          <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
            <li>
              <a href="index.php?page=home" class="nav-link text-secondary">
                <i class="bi bi-house d-block mx-auto mb-1" width="24" height="24" ></i>
                Home
              </a>
            </li>
            
            <li>
              <a href="index.php?page=cart" class="nav-link text-white">
                <i class="bi bi-cart d-block mx-auto mb-1" width="24" height="24"></i>
                Cart
              </a>
            </li>
            <li>
              <a href="index.php?page=shop" class="nav-link text-white">
                <i class="bi bi-shop  d-block mx-auto mb-1" width="24" height="24"></i>
                Products
              </a>
            </li>
            <li>
              <a href="index.php?page=contact" class="nav-link text-white">
                <i class="bi bi-person-vcard d-block mx-auto mb-1" width="24" height="24"></i>
                Contact
              </a>
            </li>
            <li>
              <a href="index.php?page=about" class="nav-link text-white">
                <i class="bi bi-info-circle d-block mx-auto mb-1" width="24" height="24"></i>
                About Us
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="px-3 py-2 border-bottom mb-3">
      <div class="container d-flex flex-wrap justify-content-center">
        <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto">
          <input type="search" class="form-control" placeholder="Tìm kiếm..." aria-label="Search">
        </form>

        <div class="text-end">
          <a href="index.php?page=login">
          <button type="button" class="btn btn-light text-dark me-2">Đăng nhập</button>
          </a>

          <a href="index.php?page=register">
          <button type="button" class="btn btn-primary">Đăng ký</button>
          </a>
        </div>
      </div>
    </div>
  </header>
    <div class="main-content">