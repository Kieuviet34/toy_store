create database toy_store;
use toy_store;
create table categories(
	cat_id int primary key not null auto_increment,
    cat_name nvarchar(255)
);
create table brands(
	brand_id int primary key auto_increment,
    brand_name nvarchar(255)
);
create table stores(
	store_id int primary key,
    store_name nvarchar(255),
    phone varchar(15),
    email varchar(30),
    street nvarchar(255),
    city nvarchar(20),
    zip_code varchar(6)
);
create table customers(
	customer_id int primary key auto_increment,
    f_name nvarchar(255),
    l_name nvarchar(255),
    phone varchar(255),
    email varchar(255),
    street varchar(255),
    city varchar(255),
    zip_code varchar(20),
    is_deleted tinyint default 0,
    customer_password varchar(255),
    customer_username varchar(255)
);
create table staffs(
	staff_id int primary key auto_increment,
    staff_f_name nvarchar(255),
    staff_l_name nvarchar(255),
    staff_img blob,
    email nvarchar(255),
    phone varchar(255),
    is_active tinyint,
    is_deleted tinyint default 0,
    store_id int,
    staff_password varchar(255),
    foreign key (store_id) references stores(store_id) 
);
create table products(
	prod_id int primary key auto_increment,
    prod_name nvarchar(255),
    prod_img blob,
    brand_id int,
    cat_id int ,
    model_year smallint,
    list_price decimal(10,2),
    is_deleted tinyint default 0,
    foreign key (brand_id) references brands(brand_id),
	foreign key (cat_id) references categories(cat_id)
);
create table stocks(
	store_id int,
    prod_id int,
    quantity int,
    foreign key (store_id) references stores(store_id),
    foreign key (prod_id) references products(prod_id),
    primary key(store_id, prod_id)
);
create table orders(
	order_id int primary key,
    customer_id int,
    order_status tinyint,
    order_date date,
    required_date date,
    shipped_date date,
    store_id int,
    staff_id int,
    is_deleted tinyint default 0,
    foreign key (store_id) references stores(store_id),
    foreign key(staff_id) references staffs(staff_id),
    foreign key(customer_id) references customers(customer_id)
);
create table order_items(
	order_id int,
    item_id int primary key,
    prod_id int,
    quantity int,
    list_price decimal(10,2),
    discount decimal(2,2),
    foreign key (order_id) references orders(order_id),
    foreign key (prod_id) references products(prod_id)
);
create table roles(
	role_id int primary key,
	role_name nvarchar(20)
);
create table staff_role(
	staff_id int,
    role_id int,
    primary key(staff_id, role_id),
    foreign key (staff_id) references staffs(staff_id),
    foreign key(role_id) references roles(role_id)
);
create table transactions(
	transaction_id int primary key auto_increment,
    order_id INT,
    customer_id INT,
    stripe_charge_id VARCHAR(255),
    amount DECIMAL(10,2),
    currency VARCHAR(3),
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);


