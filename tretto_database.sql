-- ================================================
-- DATABASE
-- ================================================
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

-- ================================================
-- PRODUCT (DO NOT CHANGE)
-- ================================================
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `PID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `descriptions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Number_Of_Sells` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `BranchID` int DEFAULT NULL,
  PRIMARY KEY (`PID`),
  KEY `BranchID` (`BranchID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`PID`, `name`, `price`, `descriptions`, `category`, `Number_Of_Sells`, `created_at`, `BranchID`) VALUES
(1, 'Rose Pink Pink Clog Soft', 299.99, 'Stylish rose pink clogs with comfortable heel height.', 'Clog', 0, '2026-05-15 14:48:04', 1),
(2, 'Black Crossbody Bag', 499.99, 'Elegant black crossbody bag with multiple compartments.', 'Bag', 0, '2026-05-15 14:48:04', 2),
(3, 'Black Slipper', 199.99, 'Cozy blue slippers made from soft materials.', 'Slipper', 0, '2026-05-15 14:48:04', 3);

-- ================================================
-- PRODUCT VARIANTS
-- ================================================
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE IF NOT EXISTS `product_variants` (
  `pvid` int NOT NULL AUTO_INCREMENT,
  `PID` int NOT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int DEFAULT '0',
  `add_price` decimal(10,2) DEFAULT NULL,
  `sizes` int DEFAULT NULL,
  PRIMARY KEY (`pvid`),
  KEY `PID` (`PID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`pvid`, `PID`, `color`, `stock`, `add_price`, `sizes`) VALUES
(1, 1, '#7BA7BC', 30, 100.00, 38),
(2, 2, '#6BA7BC', 30, 0.00, 30),
(3, 3, '#050505', 100, 0.00, 36);
-- ================================================
-- PRODUCT IMAGES
-- ================================================
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `pvid` int DEFAULT NULL,
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `pvid` (`pvid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`piid`, `pvid`, `images`) VALUES
(1, 1, '../assets/images/rose_pink_clogs_1.png'),
(2, 1, '../assets/images/rose_pink_clogs_2.png'),
(3, 1, '../assets/images/rose_pink_clogs_3.png'),
(4, 2, 'rose_pink_clogs_1.png'),
(5, 3, '../assets/images/Slipper_Black1.webp'),
(6, 3, '../assets/images/Slipper_Black2.webp');
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

-- ================================================
-- CART
-- ================================================
DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cartID` int NOT NULL AUTO_INCREMENT,
  `ID` int NOT NULL,
  `total` int DEFAULT '0',
  PRIMARY KEY (`cartID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartID`, `ID`, `total`) VALUES
(3, 1, 0),
(4, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `cartID` int NOT NULL,
  `PID` int NOT NULL,
  `pvid` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  KEY `PID` (`PID`),
  KEY `pvid` (`pvid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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