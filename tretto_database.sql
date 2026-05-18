-- ================================================
-- DATABASE
-- ================================================
DROP DATABASE IF EXISTS Tretto;
CREATE DATABASE Tretto;
USE Tretto;

-- ================================================
-- STORE LOCATION
-- ================================================
CREATE TABLE StoreLocation (
    storeID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    address VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(100),
    city VARCHAR(100),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO StoreLocation (name, address, phone, email, city, country) VALUES
('Main Branch', '123 Nile Street', '01000000001', 'main@tretto.com', 'Cairo', 'Egypt'),
('Alex Branch', '45 Sea Road', '01000000002', 'alex@tretto.com', 'Alexandria', 'Egypt');

-- ================================================
-- USERS
-- ================================================
CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    address VARCHAR(255),
    city VARCHAR(100),
    country VARCHAR(100),
    role ENUM('user','admin') DEFAULT 'user',
    registrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, phone, password, address, city, country, role) VALUES
('Ziad Haby', 'ziad@test.com', '01555594689', '123456', 'Nile St', 'Cairo', 'Egypt', 'admin'),
('Ahmed Ali', 'ahmed@test.com', '01012345678', '123456', 'Dokki', 'Giza', 'Egypt', 'user'),
('Sara Mohamed', 'sara@test.com', '01111111111', '123456', 'Maadi', 'Cairo', 'Egypt', 'user');

-- ================================================
-- PRODUCT (DO NOT CHANGE)
-- ================================================
CREATE TABLE product (
    PID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    price DECIMAL(10,2),
    descriptions TEXT,
    category VARCHAR(100),
    Number_Of_Sells INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    BranchID INT,
    FOREIGN KEY (BranchID) REFERENCES StoreLocation(storeID) ON DELETE SET NULL
);

INSERT INTO product (name, price, descriptions, category, Number_Of_Sells, BranchID) VALUES
('Rose Pink Clogs', 299.99, 'Comfortable clogs', 'Clog', 10, 1),
('Black Bag', 499.99, 'Elegant bag', 'Bag', 5, 1),
('Blue Slippers', 199.99, 'Soft slippers', 'Slipper', 20, 2);

-- ================================================
-- PRODUCT VARIANTS
-- ================================================
CREATE TABLE product_variants (
    pvid INT PRIMARY KEY AUTO_INCREMENT,
    PID INT,
    color VARCHAR(50),
    stock INT DEFAULT 0,
    add_price DECIMAL(10,2),
    sizes INT,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

INSERT INTO product_variants (PID, color, stock, add_price, sizes) VALUES
(1, 'Pink', 50, 0.00, 38),
(2, 'Black', 30, 10.00, 0),
(3, 'Blue', 100, 0.00, 42);

-- ================================================
-- PRODUCT IMAGES
-- ================================================
CREATE TABLE product_images (
    piid INT PRIMARY KEY AUTO_INCREMENT,
    pvid INT,
    images VARCHAR(255),
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid) ON DELETE CASCADE
);

INSERT INTO product_images (pvid, images) VALUES
(1, 'clogs1.jpg'),
(1, 'clogs2.jpg'),
(2, 'bag1.jpg'),
(3, 'slippers1.jpg');

-- ================================================
-- PRODUCT TYPES
-- ================================================
CREATE TABLE Bag (
    PID INT PRIMARY KEY,
    capacityLiters INT,
    numpackets INT,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

CREATE TABLE Clogs (
    PID INT PRIMARY KEY,
    heelHeight DECIMAL(5,2),
    strapType VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

CREATE TABLE Slipper (
    PID INT PRIMARY KEY,
    materialsoftness VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

INSERT INTO Bag VALUES (2, 5, 3);
INSERT INTO Clogs VALUES (1, 5.5, 'Ankle Strap');
INSERT INTO Slipper VALUES (3, 'Ultra Soft');

-- ================================================
-- CART
-- ================================================
CREATE TABLE cart (
    cartID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    total DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

INSERT INTO cart (userID, total) VALUES
(1, 0),
(2, 0),
(3, 0);

-- ================================================
-- CART ITEMS
-- ================================================
CREATE TABLE cart_items (
    cartItemID INT PRIMARY KEY AUTO_INCREMENT,
    cartID INT,
    PID INT,
    pvid INT,
    quantity INT DEFAULT 1,
    price DECIMAL(10,2),
    FOREIGN KEY (cartID) REFERENCES cart(cartID) ON DELETE CASCADE,
    FOREIGN KEY (PID) REFERENCES product(PID),
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid)
);

INSERT INTO cart_items (cartID, PID, pvid, quantity, price) VALUES
(1, 1, 1, 2, 299.99),
(2, 2, 2, 1, 499.99);

-- ================================================
-- ORDERS
-- ================================================
CREATE TABLE orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10,2),
    status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (userID) REFERENCES users(userID)
);

INSERT INTO orders (userID, totalAmount, status) VALUES
(2, 899.98, 'Pending'),
(3, 199.99, 'Processing');

-- ================================================
-- ORDER ITEMS
-- ================================================
CREATE TABLE order_items (
    itemID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    PID INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (PID) REFERENCES product(PID)
);

INSERT INTO order_items (orderID, PID, quantity, price) VALUES
(1, 1, 2, 299.99),
(1, 2, 1, 499.99),
(2, 3, 1, 199.99);

-- ================================================
-- PAYMENT
-- ================================================
CREATE TABLE payment (
    paymentID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    amount DECIMAL(10,2),
    method VARCHAR(50),
    status ENUM('Pending','Completed','Failed') DEFAULT 'Pending',
    paymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderID) REFERENCES orders(orderID)
);

INSERT INTO payment (orderID, amount, method, status) VALUES
(1, 899.98, 'Cash', 'Pending'),
(2, 199.99, 'Visa', 'Completed');

-- ================================================
-- FAVORITES ⭐
-- ================================================
CREATE TABLE favorites (
    favoriteID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    PID INT,
    addedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

INSERT INTO favorites (userID, PID) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1);

-- ================================================
-- REVIEW ⭐ NEW
-- ================================================
CREATE TABLE Review (
    review_ID INT PRIMARY KEY AUTO_INCREMENT,
    prod_ID INT NOT NULL,
    userID INT NOT NULL,
    rating DECIMAL(3,2),
    comment TEXT,
    reviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    helpful_count INT DEFAULT 0,

    FOREIGN KEY (prod_ID) REFERENCES product(PID) ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

INSERT INTO Review (prod_ID, userID, rating, comment, helpful_count) VALUES
(1, 1, 4.5, 'Very comfortable and stylish!', 10),
(1, 2, 4.0, 'Good quality but expensive', 5),
(2, 2, 5.0, 'Perfect bag!', 12),
(3, 3, 4.2, 'Soft and cozy', 3),
(2, 1, 3.8, 'Nice but average', 2);

-- ================================================
-- REFUND
-- ================================================
CREATE TABLE refund (
    refundID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    userID INT,
    refundAmount DECIMAL(10,2),
    status ENUM('Pending','Approved','Rejected','Processed') DEFAULT 'Pending',
    reason TEXT,
    refundDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

INSERT INTO refund (orderID, userID, refundAmount, status, reason) VALUES
(1, 2, 100.00, 'Pending', 'Late delivery'),
(2, 3, 50.00, 'Approved', 'Wrong size');

-- ================================================
-- EXCHANGE
-- ================================================
CREATE TABLE exchange (
    exchangeID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    userID INT,
    paymentID INT,
    oldProductID INT,
    newProductID INT,
    reason TEXT,
    status ENUM('Pending','Approved','Rejected','Processed') DEFAULT 'Pending',
    exchangeDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderID) REFERENCES orders(orderID),
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (paymentID) REFERENCES payment(paymentID),
    FOREIGN KEY (oldProductID) REFERENCES product(PID) ON DELETE SET NULL,
    FOREIGN KEY (newProductID) REFERENCES product(PID) ON DELETE SET NULL
);

INSERT INTO exchange (orderID, userID, paymentID, oldProductID, newProductID, reason, status)
VALUES (1, 2, 1, 1, 2, 'Size issue', 'Pending');

-- ================================================
-- TRACK ORDER
-- ================================================
CREATE TABLE track_order (
    trackID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT UNIQUE,
    shippingAddress VARCHAR(255),
    trackingNumber VARCHAR(100),
    shippingFee DECIMAL(10,2),
    currentLocation VARCHAR(255),
    deliveryDate DATE,
    deliveryName VARCHAR(255),
    deliveryPhone VARCHAR(20),
    status VARCHAR(50),
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE
);

INSERT INTO track_order (orderID, shippingAddress, trackingNumber, shippingFee, currentLocation, deliveryDate, deliveryName, deliveryPhone, status)
VALUES
(1, 'Cairo Street', 'TRK123456', 50.00, 'Warehouse', '2026-05-20', 'Ahmed Ali', '01012345678', 'Shipped'),
(2, 'Giza Street', 'TRK987654', 30.00, 'Transit', '2026-05-22', 'Sara Mohamed', '01111111111', 'Processing');

-- ================================================
-- SUPPORT
-- ================================================
CREATE TABLE support (
    supportID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    issue TEXT,
    status ENUM('Open','In Progress','Resolved','Closed') DEFAULT 'Open',
    createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

INSERT INTO support (userID, issue, status) VALUES
(2, 'Order not delivered', 'Open'),
(3, 'Need refund help', 'In Progress');