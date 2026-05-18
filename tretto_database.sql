-- ================================================
-- DATABASE
-- ================================================
<<<<<<< HEAD
DROP DATABASE IF EXISTS Tretto;
CREATE DATABASE Tretto;
USE Tretto;

CREATE TABLE StoreLocation (
    BranchID INT PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR
(100) NOT NULL,
    name VARCHAR
(100) NOT NULL,
    address VARCHAR
(255) NOT NULL,
    phone VARCHAR
(20) NOT NULL,
    email VARCHAR
(100) NOT NULL,
    sat_thu_hours VARCHAR
(100),
    friday_hours VARCHAR
(100),
    map_link TEXT
);

INSERT INTO StoreLocation
    (city, name, address, phone, email, sat_thu_hours, friday_hours, map_link)
VALUES
    ('Cairo', 'Main Branch', '123 Nile Street', '01000000001', 'main@tretto.com',
        '10:00 AM - 10:00 PM', '2:00 PM - 10:00 PM', 'https://maps.google.com/main'),

    ('Alexandria', 'Alex Branch', '45 Sea Road', '01000000002', 'alex@tretto.com',
        '10:00 AM - 11:00 PM', 'Closed', 'https://maps.google.com/alex');
-- ================================================
-- USERS
-- ================================================
CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR
(255),
    email VARCHAR
(100) UNIQUE,
    phone VARCHAR
(20),
    password VARCHAR
(255),
    address VARCHAR
(255),
    city VARCHAR
(100),
    country VARCHAR
(100),
    role ENUM
('user','admin') DEFAULT 'user',
    registrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users
    (name, email, phone, password, address, city, country, role)
VALUES
    ('Ziad Hany', 'ziad@test.com', '01555594689', '123456', 'Nile St', 'Cairo', 'Egypt', 'admin'),
    ('Ahmed Ali', 'ahmed@test.com', '01012345678', '123456', 'Dokki', 'Giza', 'Egypt', 'user'),
    ('Sara Mohamed', 'sara@test.com', '01111111111', '123456', 'Maadi', 'Cairo', 'Egypt', 'user');

=======
-- This SQL file contains the complete database structure
-- for the Tretto e-commerce system

-- Create Database
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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662
-- ================================================
-- PRODUCT (DO NOT CHANGE)
-- ================================================
<<<<<<< HEAD
CREATE TABLE product (
    PID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR
(255),
    price DECIMAL
(10,2),
    descriptions TEXT,
    category VARCHAR
(100),
    Number_Of_Sells INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    BranchID INT,

    FOREIGN KEY
(BranchID)
    REFERENCES StoreLocation
(BranchID)
    ON
DELETE
SET NULL
=======
CREATE TABLE IF NOT EXISTS admins (
    ID INT PRIMARY KEY,
    FOREIGN KEY (ID) REFERENCES person(ID) ON DELETE CASCADE
);

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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662
);
INSERT INTO product
    (name, price, descriptions, category, Number_Of_Sells, BranchID)
VALUES
    ('Rose Pink Clogs', 299.99, 'Comfortable clogs', 'Clog', 10, 1),
    ('Black Bag', 499.99, 'Elegant bag', 'Bag', 5, 1),
    ('Blue Slippers', 199.99, 'Soft slippers', 'Slipper', 20, 2);

-- ================================================
-- PRODUCT VARIANTS
-- ================================================
<<<<<<< HEAD
CREATE TABLE product_variants (
    pvid INT PRIMARY KEY AUTO_INCREMENT,
    PID INT,
    color VARCHAR
(50),
    stock INT DEFAULT 0,
    add_price DECIMAL
(10,2),
    sizes INT,
    FOREIGN KEY
(PID) REFERENCES product
(PID) ON
DELETE CASCADE
);

INSERT INTO product_variants
    (PID, color, stock, add_price, sizes)
VALUES
    (1, 'Pink', 50, 0.00, 38),
    (2, 'Black', 30, 10.00, 0),
    (3, 'Blue', 100, 0.00, 42);
=======
CREATE TABLE IF NOT EXISTS Bag (
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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662

-- ================================================
-- PRODUCT IMAGES
-- ================================================
<<<<<<< HEAD
CREATE TABLE product_images (
    piid INT PRIMARY KEY AUTO_INCREMENT,
    pvid INT,
    images VARCHAR
(255),
    FOREIGN KEY
(pvid) REFERENCES product_variants
(pvid) ON
DELETE CASCADE
);

INSERT INTO product_images
    (pvid, images)
VALUES
    (1, 'clogs1.jpg'),
    (1, 'clogs2.jpg'),
    (2, 'bag1.jpg'),
    (3, 'slippers1.jpg');
=======
CREATE TABLE IF NOT EXISTS Slipper (
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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662

-- ================================================
-- PRODUCT TYPES
-- ================================================
CREATE TABLE Bag
(
    PID INT PRIMARY KEY,
    capacityLiters INT,
    numpackets INT,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

CREATE TABLE Clogs
(
    PID INT PRIMARY KEY,
    heelHeight DECIMAL(5,2),
    strapType VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

INSERT INTO favorites (userID, PID) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1);

<<<<<<< HEAD
CREATE TABLE Slipper
(
    PID INT PRIMARY KEY,
    materialsoftness VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
);

INSERT INTO Bag
VALUES
    (2, 5, 3);
INSERT INTO Clogs
VALUES
    (1, 5.5, 'Ankle Strap');
INSERT INTO Slipper
VALUES
    (3, 'Ultra Soft');
=======
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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662

-- ================================================
-- CART
-- ================================================
<<<<<<< HEAD
CREATE TABLE cart (
    cartID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    total DECIMAL
(10,2) DEFAULT 0,
    FOREIGN KEY
(userID) REFERENCES users
(userID) ON
DELETE CASCADE
=======
CREATE TABLE IF NOT EXISTS TrackOrder (
    orderID INT PRIMARY KEY,
    shippingaddress VARCHAR(255),
    trackingnumber VARCHAR(100),
    shippnigfee DECIMAL(10, 2),
    order_current_location VARCHAR(255),
    deliveryDate DATE,
    deliveryName VARCHAR(255),
    deliveryPhone VARCHAR(20),
    status VARCHAR(50),
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662
);

INSERT INTO cart
    (userID, total)
VALUES
    (1, 0),
    (2, 0),
    (3, 0);

-- ================================================
-- CART ITEMS
-- ================================================
<<<<<<< HEAD
CREATE TABLE cart_items (
    cartItemID INT PRIMARY KEY AUTO_INCREMENT,
    cartID INT,
    PID INT,
    pvid INT,
    quantity INT DEFAULT 1,
    price DECIMAL
(10,2),
    FOREIGN KEY
(cartID) REFERENCES cart
(cartID) ON
DELETE CASCADE,
    FOREIGN KEY (PID)
REFERENCES product
(PID),
    FOREIGN KEY
(pvid) REFERENCES product_variants
(pvid)
);

INSERT INTO cart_items
    (cartID, PID, pvid, quantity, price)
VALUES
    (1, 1, 1, 2, 299.99),
    (2, 2, 2, 1, 499.99);


CREATE TABLE checkout
(
    checkoutID INT
    AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    cartID INT NOT NULL,
    shippingAddress VARCHAR
    (255) NOT NULL,
    city VARCHAR
    (100),
    governorate VARCHAR
    (100),
    building VARCHAR
    (100),
    deliveryDate DATE NOT NULL,
    paymentMethod VARCHAR
    (50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
    (userID) REFERENCES users
    (userID),
    FOREIGN KEY
    (cartID) REFERENCES cart
    (cartID)
);
    INSERT INTO checkout
        (userID, cartID, shippingAddress, city, governorate, building, deliveryDate, paymentMethod)
    VALUES
        (2, 2, 'Cairo Street', 'Cairo', 'Cairo', 'Building 12', '2026-05-20', 'Cash'),
        (3, 3, 'Giza Street', 'Giza', 'Giza', 'Building 8', '2026-05-22', 'Visa');
    -- ================================================
    -- ORDERS
    -- ================================================
    CREATE TABLE orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    userID         INT,
    orderDate      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount    DECIMAL
    (10,2),
    status         ENUM
    ('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
    shippingAddress VARCHAR
    (255) NULL,
    deliveryDate   DATE NULL,
    paymentMethod  VARCHAR
    (50) NULL,
    FOREIGN KEY
    (userID) REFERENCES users
    (userID)
);

    INSERT INTO orders
        (userID, totalAmount, status, shippingAddress, deliveryDate, paymentMethod)
    VALUES
        (2, 899.98, 'Pending', 'Cairo Street', '2026-05-20', 'Cash'),
        (3, 199.99, 'Processing', 'Giza Street', '2026-05-22', 'Visa');

    -- ================================================
    -- ORDER ITEMS
    -- ================================================
    CREATE TABLE order_items (
    itemID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    PID INT,
    quantity INT,
    price DECIMAL
    (10,2),
    FOREIGN KEY
    (orderID) REFERENCES orders
    (orderID) ON
    DELETE CASCADE,
    FOREIGN KEY (PID)
    REFERENCES product
    (PID)
);

    INSERT INTO order_items
        (orderID, PID, quantity, price)
    VALUES
        (1, 1, 2, 299.99),
        (1, 2, 1, 499.99),
        (2, 3, 1, 199.99);

    -- ================================================
    -- PAYMENT
    -- ================================================
    CREATE TABLE payment (
    paymentID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    amount DECIMAL
    (10,2),
    method VARCHAR
    (50),
    status ENUM
    ('Pending','Completed','Failed') DEFAULT 'Pending',
    paymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
    (orderID) REFERENCES orders
    (orderID)
);

    INSERT INTO payment
        (orderID, amount, method, status)
    VALUES
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
    FOREIGN KEY
    (userID) REFERENCES users
    (userID) ON
    DELETE CASCADE,
    FOREIGN KEY (PID)
    REFERENCES product
    (PID) ON
    DELETE CASCADE
);

    INSERT INTO favorites
        (userID, PID)
    VALUES
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
    rating DECIMAL
    (3,2),
    comment TEXT,
    reviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    helpful_count INT DEFAULT 0,

    FOREIGN KEY
    (prod_ID) REFERENCES product
    (PID) ON
    DELETE CASCADE,
    FOREIGN KEY (userID)
    REFERENCES users
    (userID) ON
    DELETE CASCADE
);

    INSERT INTO Review
        (prod_ID, userID, rating, comment, helpful_count)
    VALUES
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
    refundAmount DECIMAL
    (10,2),
    status ENUM
    ('Pending','Approved','Rejected','Processed') DEFAULT 'Pending',
    reason TEXT,
    refundDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
    (orderID) REFERENCES orders
    (orderID) ON
    DELETE CASCADE,
    FOREIGN KEY (userID)
    REFERENCES users
    (userID) ON
    DELETE CASCADE
);

    INSERT INTO refund
        (orderID, userID, refundAmount, status, reason)
    VALUES
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
    status ENUM
    ('Pending','Approved','Rejected','Processed') DEFAULT 'Pending',
    exchangeDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
    (orderID) REFERENCES orders
    (orderID),
    FOREIGN KEY
    (userID) REFERENCES users
    (userID),
    FOREIGN KEY
    (paymentID) REFERENCES payment
    (paymentID),
    FOREIGN KEY
    (oldProductID) REFERENCES product
    (PID) ON
    DELETE
    SET NULL
    ,
    FOREIGN KEY
    (newProductID) REFERENCES product
    (PID) ON
    DELETE
    SET NULL
    );

    INSERT INTO exchange
        (orderID, userID, paymentID, oldProductID, newProductID, reason, status)
    VALUES
        (1, 2, 1, 1, 2, 'Size issue', 'Pending');

    -- ================================================
    -- TRACK ORDER
    -- ================================================
    CREATE TABLE track_order (
    trackID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT UNIQUE,
    shippingAddress VARCHAR
    (255),
    trackingNumber VARCHAR
    (100),
    shippingFee DECIMAL
    (10,2),
    currentLocation VARCHAR
    (255),
    deliveryDate DATE,
    deliveryName VARCHAR
    (255),
    deliveryPhone VARCHAR
    (20),
    status VARCHAR
    (50),
    FOREIGN KEY
    (orderID) REFERENCES orders
    (orderID) ON
    DELETE CASCADE
);

    INSERT INTO track_order
        (orderID, shippingAddress, trackingNumber, shippingFee, currentLocation, deliveryDate, deliveryName, deliveryPhone, status)
    VALUES
        (1, 'Cairo Street', 'TRK123456', 50.00, 'Warehouse', '2026-05-20', 'Ahmed Ali', '01012345678', 'Shipped'),
        (2, 'Giza Street', 'TRK987654', 30.00, 'Transit', '2026-05-22', 'Sara Mohamed', '01111111111', 'Processing');

    -- ================================================
    -- SUPPORT
    -- ================================================
    CREATE TABLE
    IF NOT EXISTS support_contacts
    (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    type     ENUM
    ('whatsapp', 'phone', 'email') NOT NULL,
    value       VARCHAR
    (255) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

    INSERT INTO support_contacts
        (type, value, description)
    VALUES
        ('whatsapp', '+201000000001', 'WhatsApp support — available Sat–Thu 10 AM–10 PM'),
        ('phone', '+201000000003', 'Cairo main branch direct line'),
        ('email', 'returns@tretto.com', 'Refunds and exchange requests');

    -- ================================================
    -- FAQ
    -- ================================================
    CREATE TABLE
    IF NOT EXISTS faq
    (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    question   VARCHAR
    (255) NOT NULL,
    answer     TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

    INSERT INTO faq
        (question, answer)
    VALUES
        (
            'How do I track my order?',
            'Once your order is shipped you will receive a tracking number by email or SMS. You can also log in to your account and visit the "Track Order" section to see the current location and estimated delivery date.'
),
        (
            'Can I return or exchange an item?',
            'Yes. You may request a return or exchange within 14 days of receiving your order, provided the item is unused and in its original packaging. Visit the "Refunds & Exchanges" section in your account or contact our support team to start the process.'
),
        (
            'How long does delivery take?',
            'Standard delivery within Cairo and Giza takes 2–3 business days. Other governorates typically take 4–6 business days. You will see the estimated delivery date on your order confirmation page.'
),
        (
            'How do I cancel my order?',
            'Orders can be cancelled before they are shipped. Log in to your account, go to "My Orders", and select "Cancel Order" if the option is still available. If the order has already been shipped, please wait for delivery and then submit a return request.'
),
        (
            'How do I contact customer support?',
            'You can reach us via WhatsApp, phone, or email. Our contact details are listed on the Contact Us page. Support is available Saturday to Thursday 10:00 AM – 10:00 PM.'
);
=======
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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662
