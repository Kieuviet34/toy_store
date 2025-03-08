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
    <link rel="stylesheet" href="template/css/checkout.css">
    <link rel="stylesheet" href=template/css/shop.css>
    <link rel="stylesheet" href=template/css/info.css>
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
    <script src="OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
    <style>
        .search-suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-suggestions a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
        }
        .search-suggestions a:hover {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
<header>
    <div class="px-3 py-2 bg-dark text-white">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="index.php?page=home" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
            <img src="template/img/allainstore.jpg" class="img-fluid img-thumbnail rounded-circle" width="50px"> Allain Store
          </a>

          <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
            <li>
              <a href="index.php?page=home" class="nav-link text-secondary">
                <i class="bi bi-house d-block mx-auto mb-1" width="24" height="24"></i>
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
                <i class="bi bi-shop d-block mx-auto mb-1" width="24" height="24"></i>
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
      <div class="container d-flex flex-wrap justify-content-center position-relative">
        <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto position-relative">
          <input type="search" class="form-control" id="searchInput" placeholder="Tìm kiếm..." aria-label="Search">
          <div id="searchSuggestions" class="search-suggestions"></div>
        </form>

        <div class="text-end">
          <?php if (isset($_SESSION['user'])): ?>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Tài khoản
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="index.php?page=info">Thông tin tài khoản</a></li>
                <li><a class="dropdown-item" href="index.php?page=logout">Đăng xuất</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="index.php?page=login">
              <button type="button" class="btn btn-light text-dark me-2">Đăng nhập</button>
            </a>
            <a href="index.php?page=register">
              <button type="button" class="btn btn-primary">Đăng ký</button>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
  <div class="main-content">

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.trim();
    const suggestionsDiv = document.getElementById('searchSuggestions');

    if (query.length < 1) {
        suggestionsDiv.style.display = 'none';
        return;
    }

    fetch('src/search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => response.json())
    .then(data => {
        suggestionsDiv.innerHTML = '';
        if (data.length > 0) {
            data.forEach(item => {
                const link = document.createElement('a');
                link.href = 'index.php?page=product&id=' + item.prod_id;
                link.textContent = `${item.prod_name} (${item.brand_name})`;
                suggestionsDiv.appendChild(link);
            });
            suggestionsDiv.style.display = 'block';
        } else {
            suggestionsDiv.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        suggestionsDiv.style.display = 'none';
    });
});

document.addEventListener('click', function(event) {
    const searchInput = document.getElementById('searchInput');
    const suggestionsDiv = document.getElementById('searchSuggestions');
    if (!searchInput.contains(event.target) && !suggestionsDiv.contains(event.target)) {
        suggestionsDiv.style.display = 'none';
    }
});
</script>
</body>
</html>