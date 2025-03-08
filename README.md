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
Nếu có báo lỗi khi install package thì cop lỗi lên stackoverflow tìm file cần tải rồi chèn vào php.ini 
## Database Setup

Để tạo table, chèn file create_table.sql trong `database/migration` vào mysql hoặc phpadmin

## Running the Application

Để chạy web, sử dụng xampp hoặc wampp rồi truy cập link dưới:

```
http://localhost/path/to/clone/toy_store/index.php?page=home
```