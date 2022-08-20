-- DROP DATABASE db_j_store;


CREATE DATABASE db_j_store;
USE db_j_store;

CREATE TABLE tb_Admins
(
	admin_id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	contact_num VARCHAR(15) NOT NULL,
	email_address VARCHAR(100) NOT NULL,
	admin_password VARCHAR(250) NOT NULL,
	user_address VARCHAR(250) NOT NULL,
	gender VARCHAR(10) NOT NULL CHECK (gender IN ('male', 'female')),
	join_date DATETIME NOT NULL DEFAULT NOW(),
	updated_at DATETIME NOT NULL DEFAULT NOW(),
	verified_user bit NOT NULL DEFAULT 0,
	UNIQUE(email_address)
);

CREATE TABLE tb_Users
(
	user_id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	contact_num VARCHAR(15) NOT NULL,
	email_address VARCHAR(100) NOT NULL,
	user_password VARCHAR(20),
	user_address VARCHAR(250) NOT NULL,
	gender VARCHAR(10) NOT NULL CHECK (gender IN ('male', 'female')),
	join_date DATETIME NOT NULL DEFAULT NOW(),
	updated_at DATETIME NOT NULL DEFAULT NOW(),
	updated_by_admin INT,
    FOREIGN KEY(updated_by_admin) REFERENCES tb_ADMINS(admin_id),
	UNIQUE(email_address)
);

CREATE TABLE tb_Categories
(
	cat_id INT PRIMARY KEY AUTO_INCREMENT,
	cat_name VARCHAR(100) NOT NULL,
	cat_image VARCHAR(65535) /*NOT NULL*/,
	inserted_at DATETIME NOT NULL DEFAULT NOW(),
	updated_at DATETIME NOT NULL DEFAULT NOW(),
	updated_by_admin INT,
	UNIQUE(cat_name),
    FOREIGN KEY(updated_by_admin) REFERENCES tb_ADMINS(admin_id) 
);

CREATE TABLE tb_Products
(
	product_id INT PRIMARY KEY AUTO_INCREMENT,
	product_name VARCHAR(100) NOT NULL,
	sku VARCHAR(100) NOT NULL,
	product_image VARCHAR(65535) NOT NULL,
	long_description VARCHAR(250),
	price DECIMAL(7,2) NOT NULL, -- (decimal total 7 numbers including 2 decimal points, limit of decimal numbers is set to 2)
	stock INT NOT NULL DEFAULT 0,
	category_id INT NOT NULL, 
	inserted_at DATETIME NOT NULL DEFAULT NOW(),
	updated_at DATETIME NOT NULL DEFAULT NOW(),
	updated_by_admin INT,
	UNIQUE(product_name),
	UNIQUE(sku),
	FOREIGN KEY(category_id) REFERENCES tb_Categories(cat_id),
    FOREIGN KEY(updated_by_admin) REFERENCES tb_ADMINS(admin_id)
);

CREATE TABLE tb_Orders
(
	order_id INT PRIMARY KEY AUTO_INCREMENT,
	user_id INT NOT NULL,
	order_date DATETIME NOT NULL DEFAULT NOW(),
	sub_total DECIMAL(7,2) NOT NULL,
	shipping_cost DECIMAL(6,2) NOT NULL DEFAULT 0,
	order_status VARCHAR(50) NOT NULL DEFAULT 'waiting' CHECK (order_status IN ('waiting', 'confirmed', 'shipped', 'delivered', 'cancelled')),
	status_date DATETIME NOT NULL DEFAULT NOW(),
	remarks VARCHAR(100), -- in case of cancellation admin can store why order was cancelled
	updated_by_admin INT,
	FOREIGN KEY(updated_by_admin) REFERENCES tb_ADMINS(admin_id),
	FOREIGN KEY(user_id) REFERENCES tb_USERS(user_id)
);

-- relation b/w order and products table M-M
CREATE TABLE tb_Orders_Summary
(
	order_detail_id INT PRIMARY KEY AUTO_INCREMENT,
	order_id INT NOT NULL,
	product_id INT NOT NULL,
	unit_price DECIMAL(7,2) NOT NULL DEFAULT 0,
	quantity INT NOT NULL,
	FOREIGN KEY(order_id) REFERENCES tb_ORDERS(order_id),
	FOREIGN KEY(product_id) REFERENCES tb_PRODUCTS(product_id)
);