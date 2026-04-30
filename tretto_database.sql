-- ================================================
-- TRETTO E-COMMERCE SYSTEM DATABASE SCHEMA
-- ================================================
-- This SQL file contains the complete database structure
-- for the Tretto e-commerce system

-- Create Database
CREATE DATABASE IF NOT EXISTS Tretto;
USE Tretto;

-- ================================================
-- TABLE: StoreLocation
-- ================================================
CREATE TABLE IF NOT EXISTS StoreLocation (
    storeID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    city VARCHAR(100),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- TABLE: User (Base User Table)
-- ================================================
CREATE TABLE IF NOT EXISTS User (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    registrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    address VARCHAR(255),
    city VARCHAR(100),
    country VARCHAR(100),
    user_type ENUM('Person', 'Admin') DEFAULT 'Person'
);

-- ================================================
-- TABLE: Person (Extends User)
-- ================================================
CREATE TABLE IF NOT EXISTS Person (
    personID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL UNIQUE,
    dateOfBirth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    address VARCHAR(255),
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Admin (Extends User)
-- ================================================
CREATE TABLE IF NOT EXISTS Admin (
    adminID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL UNIQUE,
    role VARCHAR(100),
    permissions TEXT,
    dateOfHire DATE,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Product (Base Product Table)
-- ================================================
CREATE TABLE IF NOT EXISTS Product (
    prod_ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    image VARCHAR(255),
    storeID INT,
    rating DECIMAL(3, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (storeID) REFERENCES StoreLocation(storeID) ON DELETE SET NULL
);

-- ================================================
-- TABLE: Bag (Extends Product)
-- ================================================
CREATE TABLE IF NOT EXISTS Bag (
    bagID INT PRIMARY KEY AUTO_INCREMENT,
    prod_ID INT NOT NULL UNIQUE,
    bag_type VARCHAR(100),
    material VARCHAR(100),
    capacity VARCHAR(100),
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Clogs (Extends Product)
-- ================================================
CREATE TABLE IF NOT EXISTS Clogs (
    clogID INT PRIMARY KEY AUTO_INCREMENT,
    prod_ID INT NOT NULL UNIQUE,
    clog_string VARCHAR(100),
    size VARCHAR(50),
    color VARCHAR(100),
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Slipper (Extends Product)
-- ================================================
CREATE TABLE IF NOT EXISTS Slipper (
    slipperID INT PRIMARY KEY AUTO_INCREMENT,
    prod_ID INT NOT NULL UNIQUE,
    slipper_string VARCHAR(100),
    sole_type VARCHAR(100),
    size VARCHAR(50),
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Order
-- ================================================
CREATE TABLE IF NOT EXISTS `Order` (
    order_ID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    shippingAddress VARCHAR(255),
    paymentMethod VARCHAR(100),
    deliveryDate DATE,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Payment (Base Payment Table)
-- ================================================
CREATE TABLE IF NOT EXISTS Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    order_ID INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    paymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    method VARCHAR(100),
    status ENUM('Pending', 'Completed', 'Failed', 'Cancelled') DEFAULT 'Pending',
    payment_type ENUM('Visa', 'Cash', 'Exchange') DEFAULT 'Cash',
    FOREIGN KEY (order_ID) REFERENCES `Order`(order_ID) ON DELETE CASCADE
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
    payment_ID INT NOT NULL UNIQUE,
    oldProductID INT,
    newProductID INT,
    exchangeDate DATE,
    reason TEXT,
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID) ON DELETE CASCADE,
    FOREIGN KEY (oldProductID) REFERENCES Product(prod_ID) ON DELETE SET NULL,
    FOREIGN KEY (newProductID) REFERENCES Product(prod_ID) ON DELETE SET NULL
);

-- ================================================
-- TABLE: Refund
-- ================================================
CREATE TABLE IF NOT EXISTS Refund (
    refund_ID INT PRIMARY KEY AUTO_INCREMENT,
    order_ID INT NOT NULL,
    refundAmount DECIMAL(10, 2) NOT NULL,
    refundDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reason TEXT,
    status ENUM('Pending', 'Approved', 'Rejected', 'Processed') DEFAULT 'Pending',
    FOREIGN KEY (order_ID) REFERENCES `Order`(order_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: TrackOrder
-- ================================================
CREATE TABLE IF NOT EXISTS TrackOrder (
    track_ID INT PRIMARY KEY AUTO_INCREMENT,
    order_ID INT NOT NULL UNIQUE,
    status VARCHAR(100),
    lastUpdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    location VARCHAR(255),
    estimatedDelivery DATE,
    FOREIGN KEY (order_ID) REFERENCES `Order`(order_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Cart
-- ================================================
CREATE TABLE IF NOT EXISTS Cart (
    cart_ID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    prod_ID INT NOT NULL,
    quantity INT DEFAULT 1,
    addedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE,
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Favorite
-- ================================================
CREATE TABLE IF NOT EXISTS Favorite (
    favorite_ID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    prod_ID INT NOT NULL,
    addedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE,
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Review
-- ================================================
CREATE TABLE IF NOT EXISTS Review (
    review_ID INT PRIMARY KEY AUTO_INCREMENT,
    prod_ID INT NOT NULL,
    userID INT NOT NULL,
    rating DECIMAL(3, 2),
    comment TEXT,
    reviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    helpful_count INT DEFAULT 0,
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Collection
-- ================================================
CREATE TABLE IF NOT EXISTS Collection (
    collection_ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    image VARCHAR(255)
);

-- ================================================
-- TABLE: Collection_Products (Many-to-Many)
-- ================================================
CREATE TABLE IF NOT EXISTS Collection_Products (
    collectionProductID INT PRIMARY KEY AUTO_INCREMENT,
    collection_ID INT NOT NULL,
    prod_ID INT NOT NULL,
    FOREIGN KEY (collection_ID) REFERENCES Collection(collection_ID) ON DELETE CASCADE,
    FOREIGN KEY (prod_ID) REFERENCES Product(prod_ID) ON DELETE CASCADE
);

-- ================================================
-- TABLE: Suppurt (Support/Support Tickets)
-- ================================================
CREATE TABLE IF NOT EXISTS Suppurt (
    support_ID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    issue TEXT NOT NULL,
    status ENUM('Open', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Open',
    createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responseDate DATETIME,
    resolution TEXT,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

-- ================================================
-- INDEXES for Better Performance
-- ================================================
CREATE INDEX idx_user_email ON User(email);
CREATE INDEX idx_product_category ON Product(category);
CREATE INDEX idx_order_userID ON `Order`(userID);
CREATE INDEX idx_order_status ON `Order`(status);
CREATE INDEX idx_cart_userID ON Cart(userID);
CREATE INDEX idx_favorite_userID ON Favorite(userID);
CREATE INDEX idx_review_prodID ON Review(prod_ID);
CREATE INDEX idx_payment_orderID ON Payment(order_ID);

-- ================================================
-- DATABASE SETUP COMPLETE
-- ================================================
