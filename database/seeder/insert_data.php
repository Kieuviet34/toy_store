<?php
require_once 'vendor/autoload.php';
$faker = Faker\Factory::create();

// Thông tin kết nối database
$host = 'localhost';
$user = 'root';
$password = 'Kieuviet2004@';
$database = 'toy_store';

// Kết nối database
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Thiết lập charset UTF-8 để hỗ trợ tiếng Việt
$conn->set_charset("utf8");

// 1. Chèn dữ liệu vào bảng categories
$conn->query("INSERT INTO categories (cat_id, cat_name) VALUES
    (1, 'Đồ chơi giáo dục'),
    (2, 'Đồ chơi vận động'),
    (3, 'Đồ chơi điện tử'),
    (4, 'Búp bê'),
    (5, 'Xe mô hình')
");

// 2. Chèn dữ liệu vào bảng brands
$conn->query("INSERT INTO brands (brand_id, brand_name) VALUES
    (1, 'Lego'),
    (2, 'Hot Wheels'),
    (3, 'Barbie'),
    (4, 'Nerf'),
    (5, 'Fisher-Price')
");

// 3. Chèn dữ liệu vào bảng stores
$stmt_store = $conn->prepare("
    INSERT INTO stores (store_id, store_name, phone, email, street, city, zip_code)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 5; $i++) {
    $store_id = $i;
    $store_name = "Cửa hàng $i";
    $phone = $faker->numerify('09########');
    $email = substr($faker->email, 0, 30);
    $street = $faker->streetAddress;
    $city = $faker->city;
    $zip_code = $faker->numerify('######');

    $stmt_store->bind_param("issssss", $store_id, $store_name, $phone, $email, $street, $city, $zip_code);
    $stmt_store->execute();
}

// 4. Chèn dữ liệu vào bảng staffs
$stmt_staff = $conn->prepare("
    INSERT INTO staffs (
        staff_id, staff_f_name, staff_l_name, staff_img,
        email, phone, is_active, store_id, staff_password
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 10; $i++) {
    $staff_id = $i;
    $staff_f_name = $faker->firstName;
    $staff_l_name = $faker->lastName;
    $staff_img = null; // Để NULL nếu chưa có ảnh
    $email = $faker->email;
    $phone = $faker->numerify('09########');
    $is_active = 1;
    $store_id = $faker->numberBetween(1, 5);
    $staff_password = $faker->password;

    $stmt_staff->bind_param("issssisis", $staff_id, $staff_f_name, $staff_l_name, $staff_img, $email, $phone, $is_active, $store_id, $staff_password);
    $stmt_staff->execute();
}

// 5. Chèn dữ liệu vào bảng products
$stmt_product = $conn->prepare("
    INSERT INTO products (
        prod_id, prod_name, prod_img, brand_id, 
        cat_id, model_year, list_price
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 100; $i++) {
    $prod_id = $i;
    $prod_name = "Sản phẩm $i - " . $faker->word;
    $prod_img = null; // Để NULL nếu chưa có ảnh
    $brand_id = $faker->numberBetween(1, 5);
    $cat_id = $faker->numberBetween(1, 5);
    $model_year = $faker->numberBetween(2010, 2023);
    $list_price = $faker->randomFloat(2, 10, 1000);

    $stmt_product->bind_param("issiiid", $prod_id, $prod_name, $prod_img, $brand_id, $cat_id, $model_year, $list_price);
    $stmt_product->execute();
}

// 6. Chèn dữ liệu vào bảng stocks
$stmt_stock = $conn->prepare("
    INSERT INTO stocks (store_id, prod_id, quantity)
    VALUES (?, ?, ?)
");
for ($i = 1; $i <= 200; $i++) {
    $store_id = $faker->numberBetween(1, 5);
    $prod_id = $faker->numberBetween(1, 100);
    $quantity = $faker->numberBetween(10, 100);

    $stmt_stock->bind_param("iii", $store_id, $prod_id, $quantity);
    $stmt_stock->execute();
}

// 7. Chèn dữ liệu vào bảng customers
$stmt_customer = $conn->prepare("
    INSERT INTO customers (
        customer_id, f_name, l_name, phone, email, 
        street, city, zip_code, customer_password, customer_username
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 50; $i++) {
    $customer_id = $i;
    $f_name = $faker->firstName;
    $l_name = $faker->lastName;
    $phone = $faker->numerify('09########');
    $email = $faker->email;
    $street = $faker->streetAddress;
    $city = $faker->city;
    $zip_code = $faker->postcode;
    $customer_password = $faker->password;
    $customer_username = $faker->userName;

    $stmt_customer->bind_param("isssssssss", $customer_id, $f_name, $l_name, $phone, $email, $street, $city, $zip_code, $customer_password, $customer_username);
    $stmt_customer->execute();
}

// 8. Chèn dữ liệu vào bảng orders
$stmt_order = $conn->prepare("
    INSERT INTO orders (
        order_id, customer_id, order_status, 
        order_date, required_date, shipped_date, 
        store_id, staff_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 30; $i++) {
    $order_id = $i;
    $customer_id = $faker->numberBetween(1, 50); // Sửa customer_name thành customer_id
    $order_status = $faker->numberBetween(1, 3);
    $order_date = $faker->date();
    $required_date = $faker->date();
    $shipped_date = $faker->date();
    $store_id = $faker->numberBetween(1, 5);
    $staff_id = $faker->numberBetween(1, 10);

    $stmt_order->bind_param("iissssii", $order_id, $customer_id, $order_status, $order_date, $required_date, $shipped_date, $store_id, $staff_id);
    $stmt_order->execute();
}

// 9. Chèn dữ liệu vào bảng order_items
$stmt_order_item = $conn->prepare("
    INSERT INTO order_items (
        order_id, item_id, prod_id, quantity, list_price, discount
    ) VALUES (?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 100; $i++) {
    $order_id = $faker->numberBetween(1, 30);
    $item_id = $i;
    $prod_id = $faker->numberBetween(1, 100);
    $quantity = $faker->numberBetween(1, 5);
    $list_price = $faker->randomFloat(2, 10, 100);
    $discount = $faker->randomFloat(2, 0, 0.5);

    $stmt_order_item->bind_param("iiiidd", $order_id, $item_id, $prod_id, $quantity, $list_price, $discount);
    $stmt_order_item->execute();
}

// 10. Chèn dữ liệu vào bảng roles và staff_role
$conn->query("INSERT INTO roles (role_id, role_name) VALUES
    (1, 'Quản lý'),
    (2, 'Nhân viên bán hàng'),
    (3, 'Kế toán')
");

$stmt_staff_role = $conn->prepare("
    INSERT INTO staff_role (staff_id, role_id)
    VALUES (?, ?)
");
for ($i = 1; $i <= 10; $i++) {
    $staff_id = $i;
    $role_id = $faker->numberBetween(1, 3);
    $stmt_staff_role->bind_param("ii", $staff_id, $role_id);
    $stmt_staff_role->execute();
}

// 11. Chèn dữ liệu vào bảng transactions
$stmt_transaction = $conn->prepare("
    INSERT INTO transactions (
        order_id, customer_id, stripe_charge_id, 
        amount, currency, status
    ) VALUES (?, ?, ?, ?, ?, ?)
");
for ($i = 1; $i <= 30; $i++) { // Tạo 30 giao dịch tương ứng với 30 đơn hàng
    $order_id = $i;
    $customer_id = $faker->numberBetween(1, 50);
    $stripe_charge_id = 'ch_' . $faker->uuid; // Giả lập ID từ Stripe
    $amount = $faker->randomFloat(2, 10, 1000); // Số tiền ngẫu nhiên
    $currency = 'USD'; // Có thể thay bằng 'VND' tùy yêu cầu
    $status = $faker->randomElement(['succeeded', 'pending', 'failed']);

    $stmt_transaction->bind_param("iisdss", $order_id, $customer_id, $stripe_charge_id, $amount, $currency, $status);
    $stmt_transaction->execute();
}

echo "Đã chèn dữ liệu mẫu thành công!";

// Đóng kết nối
$conn->close();
?>