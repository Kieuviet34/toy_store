# Toy Store

Đây là trang web sử dụng PHP thuần và MYSQL, cho phép user tìm kiếm và mua sản phẩm, có implement chức năng admin cơ bản.

## Installation

### Prerequisites

- [Install Composer](https://getcomposer.org/download/)

### Installing Faker

Để cài package chèn data của insert_data.php, chạy cmd sau

```sh
cd database/seeder
```

```sh
composer require fakerphp/faker
```
Sau khi cài xong thì chạy 
```sh
php insert_data.php
```
nếu có lỗi thì mở php.ini uncomment những dòng sau (bỏ dấu ; ở trước dòng đó):
```
    extension=curl
    extension=fileinfo
    extension=gd
    extension=mysqli
    extension=openssl
```
## Database Setup

Để tạo table, chèn file create_table.sql trong `database/migration` vào MySQL hoặc phpadmin

## Running the Application

Để chạy web, sử dụng xampp hoặc wampp rồi truy cập link dưới:

```
http://localhost/path/to/clone/toy_store/index.php?page=home
```
## Note
File csv muốn mass receive được nên nhập data dạng sau:

```
    prod_name,prod_img_path,brand_name,cat_name,model_year,list_price
    "name","path_to_product_img", "brand name", "category name", year(number), price(number)
```
