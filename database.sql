CREATE DATABASE simple_einvoice;
USE simple_einvoice;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    tagline VARCHAR(255),
    address VARCHAR(255),
    phone VARCHAR(50),
    email VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id VARCHAR(50) UNIQUE,
    business_id INT,
    customer_name VARCHAR(255),
    customer_phone VARCHAR(50),
    date DATE,
    total_amount DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (business_id) REFERENCES users(id)
);

CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT,
    description VARCHAR(255),
    quantity INT,
    unit_cost DECIMAL(10,2),
    amount DECIMAL(10,2),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);
