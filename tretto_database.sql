-- ================================================
-- TRETTO E-COMMERCE SYSTEM DATABASE SCHEMA
-- ================================================
-- This SQL file contains the complete database structure
-- for the Tretto e-commerce system

-- Create Database
DROP DATABASE IF EXISTS Tretto;
CREATE DATABASE IF NOT EXISTS Tretto;
USE Tretto;

-- ================================================
-- TABLE: StoreLocation
-- ================================================
CREATE TABLE IF NOT EXISTS StoreLocation (
    BranchID INT PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR(100) NOT NULL,
    Address VARCHAR(255) NOT NULL
);

-- ================================================
-- TABLE: Person (Extends User)
-- ================================================
CREATE TABLE IF NOT EXISTS person (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
-- ================================================
-- TABLE: User (Base User Table)
-- ================================================
CREATE TABLE IF NOT EXISTS users (
    ID INT PRIMARY KEY,
    shippingaddress VARCHAR(100) UNIQUE NOT NULL,
    phoneNumber VARCHAR(20),
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);
-- ================================================
-- TABLE: Admin (Extends User)
-- ================================================
CREATE TABLE IF NOT EXISTS admins (
    ID INT PRIMARY KEY,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Product (Base Product Table)
-- ================================================
CREATE TABLE IF NOT EXISTS product (
    PID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    descriptions TEXT,
    category VARCHAR(100), -- 'Clog', 'Bag', 'Slipper'
    Number_Of_Sells INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    BranchID INT,
    FOREIGN KEY (BranchID) REFERENCES StoreLocation(BranchID) ON DELETE SET NULL
);

-- ================================================
-- TABLE: ProductVariant (For Color Variants)
-- ================================================
CREATE TABLE IF NOT EXISTS product_variants (
    pvid INT PRIMARY KEY AUTO_INCREMENT,
    PID INT NOT NULL,
    color VARCHAR(50), -- e.g., 'Rose Pink'
    stock INT DEFAULT 0,
    add_price DECIMAL(10, 2), -- Additional price for this variant
    sizes INT, -- e.g., '38'
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS product_images (
    piid INT PRIMARY KEY AUTO_INCREMENT,
    pvid INT,
    images VARCHAR(255) NOT NULL,
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Bag (Extends Product)
-- ================================================
CREATE TABLE IF NOT EXISTS Bag (
    PID INT PRIMARY KEY,
    capacityLiters INT DEFAULT 0, -- 'Crossbody', 'Tote'
    numpackets INT DEFAULT 0,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Clogs (Extends Product)
-- ================================================
CREATE TABLE IF NOT EXISTS Clogs (
    PID INT PRIMARY KEY,
    heelHeight DECIMAL(5, 2) DEFAULT 0.00,
    strapType VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Slipper (Extends Product)
-- ================================================
CREATE TABLE IF NOT EXISTS Slipper (
    PID INT PRIMARY KEY,
    materialsoftness VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);
-- ================================================
-- TABLE: Orders
-- ================================================
CREATE TABLE IF NOT EXISTS orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    ID INT NOT NULL,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10, 2) NOT NULL,
    order_status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    cartID INT,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE,
    FOREIGN KEY (cartID) REFERENCES cart(cartID) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    itemID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE
);
-- ================================================
-- TABLE: Payment (Base Payment Table)
-- ================================================
CREATE TABLE IF NOT EXISTS Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    paymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    method VARCHAR(100),
    status ENUM('Pending', 'Completed', 'Failed', 'Cancelled') DEFAULT 'Pending',
    payment_type ENUM('Visa', 'Cash', 'Exchange') DEFAULT 'Cash',
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Visa (Extends Payment)
-- ================================================
CREATE TABLE IF NOT EXISTS Visa (
    visaID INT PRIMARY KEY AUTO_INCREMENT,
    payment_ID INT NOT NULL UNIQUE,
    cardNumber VARCHAR(20),
    cardHolderName VARCHAR(255),
    expiryDate DATE,
    cvv VARCHAR(10),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Cash (Extends Payment)
-- ================================================
CREATE TABLE IF NOT EXISTS Cash (
    cashID INT PRIMARY KEY AUTO_INCREMENT,
    payment_ID INT NOT NULL UNIQUE,
    receivedAmount DECIMAL(10, 2),
    changeAmount DECIMAL(10, 2),
    paymentLocation VARCHAR(255),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Exchange (Extends Payment)
-- ================================================
CREATE TABLE IF NOT EXISTS Exchange (
    exchangeID INT PRIMARY KEY AUTO_INCREMENT,
    ID INT NOT NULL,
    reason TEXT,
    payment_ID INT NOT NULL UNIQUE,
    status ENUM('Pending', 'Approved', 'Rejected', 'Processed') DEFAULT 'Pending',
    oldProductID INT,
    newProductID INT,
    exchangeDate DATE,
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID) ON DELETE CASCADE,
    FOREIGN KEY (oldProductID) REFERENCES Product(prod_ID) ON DELETE SET NULL,
    FOREIGN KEY (newProductID) REFERENCES Product(prod_ID) ON DELETE SET NULL,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Refund
-- ================================================
CREATE TABLE IF NOT EXISTS Refund (
    refundID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT NOT NULL,
    ID INT NOT NULL,
    refundAmount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected', 'Processed') DEFAULT 'Pending',
    itemID INT,
    refundDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reason TEXT,
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE,
    FOREIGN KEY (itemID) REFERENCES order_items(itemID) ON DELETE SET NULL
);

-- ================================================
-- TABLE: TrackOrder
-- ================================================
CREATE TABLE IF NOT EXISTS TrackOrder (
    orderID INT PRIMARY KEY,
    shippingaddress VARCHAR(255),
    trackingnumber VARCHAR(100),
    shippnigfee DECIMAL(10, 2),
    order_current_location VARCHAR(255),
    deliveryDate DATE,
    delivery_name VARCHAR(255),
    delivery_number VARCHAR(20),
    status VARCHAR(100),
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Cart
-- ================================================
CREATE TABLE IF NOT EXISTS Cart (
    cartID INT PRIMARY KEY AUTO_INCREMENT,
    ID INT NOT NULL,
    total INT DEFAULT 0,
    FOREIGN KEY (ID) REFERENCES users(ID) ON DELETE CASCADE
    );
-- ================================================
-- TABLE: Cart item
-- ================================================
CREATE TABLE IF NOT EXISTS cart_items (
    cartID INT PRIMARY KEY,
    PID INT NOT NULL,
    pvid INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (cartID) REFERENCES cart(cartID),
    FOREIGN KEY (PID) REFERENCES product(PID),
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid)
);
-- ================================================
-- TABLE: Favorite
-- ================================================
CREATE TABLE IF NOT EXISTS Favorite (
    favID INT PRIMARY KEY AUTO_INCREMENT,
    ID INT NOT NULL,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS favorite_items (
    favID INT PRIMARY KEY,
    PID INT NOT NULL,
    pvid INT NOT NULL,
    FOREIGN KEY (favID) REFERENCES Favorite(favID),
    FOREIGN KEY (PID) REFERENCES product(PID),
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid)
);

-- ================================================
-- TABLE: Review
-- ================================================
CREATE TABLE IF NOT EXISTS Review (
    reviewID INT PRIMARY KEY AUTO_INCREMENT,
    PID INT NOT NULL,
    ID INT NOT NULL,
    rating DECIMAL(3, 2),
    comment TEXT,
    reviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PID) REFERENCES Product(prod_ID) ON DELETE CASCADE,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS collections (
    collectionID INT PRIMARY KEY AUTO_INCREMENT,
    collectionName VARCHAR(255) NOT NULL,
    descriptions TEXT,
    image VARCHAR(255),
    createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS collection_items (
    collectionID INT PRIMARY KEY,
    PID INT NOT NULL,
    FOREIGN KEY (collectionID) REFERENCES collections(collectionID) ON DELETE CASCADE,
    FOREIGN KEY (PID) REFERENCES product(prod_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Suppurt (
    ID INT ,
    email VARCHAR(255) NOT NULL,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);
-- ================================================
-- TABLE: INSERT INTO ALL TABLES
-- ================================================
-- Insert Store Locations
INSERT INTO StoreLocation (city, Address) VALUES
('Cairo', '123 Nile Street, Cairo, Egypt'),
('Alexandria', '456 Mediterranean Ave, Alexandria, Egypt'),
('Giza', '789 Pyramid Road, Giza, Egypt');
-- Insert Person
INSERT INTO person (ID,name, email, password) VALUES
(1, 'John Doe', 'john@example.com', 'hashed_password');
-- Insert User
INSERT INTO users (ID, shippingaddress, phoneNumber) VALUES
(1, '123 Nile Street, Cairo, Egypt', '0123456789');
-- Insert Products
INSERT INTO product (name, price, descriptions, category, BranchID) VALUES
('Rose Pink Clogs', 299.99, 'Stylish rose pink clogs with comfortable heel height.', 'Clog', 1),
('Black Crossbody Bag', 499.99, 'Elegant black crossbody bag with multiple compartments.', 'Bag', 2),
('Blue Slippers', 199.99, 'Cozy blue slippers made from soft materials.', 'Slipper', 3);
-- Insert Product Variants
INSERT INTO product_variants (PID, color, stock, add_price, sizes) VALUES
(1, 'Rose Pink', 50, 0.00, 38),
(2, 'Black', 30, 0.00, 12),
(3, 'Blue', 100, 0.00, 11);
-- Insert Product Images
INSERT INTO product_images (pvid, images) VALUES
(1, 'rose_pink_clogs_1.jpg'),
(1, 'rose_pink_clogs_2.jpg'),
(2, 'black_crossbody_bag_1.jpg'),
(2, 'black_crossbody_bag_2.jpg'),
(3, 'blue_slippers_1.jpg'),
(3, 'blue_slippers_2.jpg');
-- Insert Bag Details
INSERT INTO Bag (PID, capacityLiters, numpackets) VALUES
(2, 5, 3);
-- Insert Clogs Details
INSERT INTO Clogs (PID, heelHeight, strapType) VALUES
(1, 5.00, 'Ankle Strap');
-- Insert Slipper Details
INSERT INTO Slipper (PID, materialsoftness) VALUES
(3, 'Soft and Plush');
-- Insert Orders
INSERT INTO orders (ID, totalAmount, order_status) VALUES
(1, 299.99, 'Pending');
INSERT INTO order_items (orderID, quantity, price) VALUES
(1, 1, 299.99);
