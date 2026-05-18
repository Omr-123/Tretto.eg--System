DROP DATABASE IF EXISTS Tretto;
CREATE DATABASE Tretto;
USE Tretto;

-- ================================================
-- STORE LOCATION
-- ================================================
CREATE TABLE StoreLocation (
    BranchID INT PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    sat_thu_hours VARCHAR(100),
    friday_hours VARCHAR(100),
    map_link TEXT
) ENGINE=InnoDB;

INSERT INTO StoreLocation (city, name, address, phone, email, sat_thu_hours, friday_hours, map_link)
VALUES
('Cairo', 'Main Branch', '123 Nile Street', '01000000001', 'main@tretto.com', '10:00 AM - 10:00 PM', '2:00 PM - 10:00 PM', 'https://maps.google.com/main'),
('Alexandria', 'Alex Branch', '45 Sea Road', '01000000002', 'alex@tretto.com', '10:00 AM - 11:00 PM', 'Closed', 'https://maps.google.com/alex');

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
) ENGINE=InnoDB;

INSERT INTO users (name, email, phone, password, address, city, country, role)
VALUES
('Ziad Hany', 'ziad@test.com', '01555594689', '123456', 'Nile St', 'Cairo', 'Egypt', 'admin'),
('Ahmed Ali', 'ahmed@test.com', '01012345678', '123456', 'Dokki', 'Giza', 'Egypt', 'user'),
('Sara Mohamed', 'sara@test.com', '01111111111', '123456', 'Maadi', 'Cairo', 'Egypt', 'user');

-- ================================================
-- PRODUCT
-- ================================================
CREATE TABLE product (
    PID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    descriptions TEXT,
    category VARCHAR(100),
    Number_Of_Sells INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    BranchID INT,
    FOREIGN KEY (BranchID) REFERENCES StoreLocation(BranchID)
) ENGINE=InnoDB;

INSERT INTO product (name, price, descriptions, category, Number_Of_Sells, BranchID)
VALUES
('Rose Pink Pink Clog Soft', 299.99, 'Stylish rose pink clogs with comfortable heel height.', 'Clog', 0, 1),
('Black Crossbody Bag', 499.99, 'Elegant black crossbody bag with multiple compartments.', 'Bag', 0,  1),
('Black Slipper', 199.99, 'Cozy blue slippers made from soft materials.', 'Slipper', 0,  1),
('SIRENA PEARL SLIDES - Golden Hour', 349.99, 'Step into elegance with our Sirena Pearl Slides, where every detail whispers sophistication. Intricately hand-embellished with clusters of glossy pearls, these slides are the perfect blend of luxe and ease. Designed for bridal events, chic evenings, or just elevating your everyday look, Sirena brings shimmer and femininity with every step. Crafted with comfort in mind, they’re as wearable as they are unforgettable..', 'Slipper', 5,  1),
('Loafer Clog', 550.00, 'Comfort clog designed for everyday wear, providing lightweight support, breathable material, and a durable sole for long-lasting comfort.', 'Clog', 0, 1);

-- ================================================
-- PRODUCT VARIANTS
-- ================================================
CREATE TABLE product_variants (
    pvid INT PRIMARY KEY AUTO_INCREMENT,
    PID INT,
    color VARCHAR(50),
    stock INT DEFAULT 0,
    add_price DECIMAL(10,2) DEFAULT 0,
    sizes INT,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `product_variants` (`pvid`, `PID`, `color`, `stock`, `add_price`, `sizes`) VALUES
(1, 1, '#7BA7BC', 30, 100.00, 38),
(2, 2, '#6BA7BC', 30, 0.00, 30),
(3, 3, '#050505', 100, 0.00, 36),
(4, 4, '#FFD700', 30, 15.00, 32),
(5, 5, '#008000', 25, 0.00, 38),
(6, 5, '#008000', 25, 0.00, 39),
(7, 5, '#008000', 25, 0.00, 40),
(8, 5, '#008000', 25, 0.00, 41);

-- ================================================
-- PRODUCT IMAGES
-- ================================================
CREATE TABLE product_images (
    piid INT PRIMARY KEY AUTO_INCREMENT,
    pvid INT,
    images VARCHAR(255),
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO product_images (piid,pvid, images)
VALUES
(1, 1, '../assets/images/rose_pink_clogs_1.png'),
(2, 1, '../assets/images/rose_pink_clogs_2.png'),
(3, 1, '../assets/images/rose_pink_clogs_3.png'),
(4, 2, '../assets/images/rose_pink_clogs_1.png'),
(5, 3, '../assets/images/Slipper_Black1.webp'),
(6, 3, '../assets/images/Slipper_Black2.webp'),
(7, 4, '../assets/images/Slipper_Golden1.webp'),
(8, 4, '../assets/images/Slipper_Golden2.webp'),
(9, 4, '../assets/images/Slipper_Golden3.webp'),
(10, 5, '../assets/images/Clog_Green1.webp'),
(11, 5, '../assets/images/Clog_Green2.webp'),
(12, 5, '../assets/images/Clog_Green3.webp');
-- ================================================
-- PRODUCT TYPES
-- ================================================
CREATE TABLE Bag (
    PID INT PRIMARY KEY,
    capacityLiters INT,
    numpackets INT,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Clogs (
    PID INT PRIMARY KEY,
    heelHeight DECIMAL(5,2),
    strapType VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Slipper (
    PID INT PRIMARY KEY,
    materialsoftness VARCHAR(100),
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

INSERT INTO cart (userID, total)
VALUES
(1, 0),
(2, 0),
(3, 0);


CREATE TABLE cart_items (
    cartID INT,
    PID INT,
    pvid INT,
    quantity INT DEFAULT 1,
    price DECIMAL(10,2),
    FOREIGN KEY (PID) REFERENCES product(PID),
    FOREIGN KEY (pvid) REFERENCES product_variants(pvid)
) ENGINE=InnoDB;

INSERT INTO `cart_items` (`cartID`, `PID`, `pvid`, `quantity`, `price`) VALUES
(3, 5, 1, 1, 299.99),
(3, 1, 1, 2, 299.99),
(5, 4, 1, 1, 299.99),
(5, 3, 3, 2, 199.00),
(3, 4, 1, 2, 299.99),
(5, 5, 5, 4, 550.00);

-- ================================================
-- CHECKOUT
-- ================================================
CREATE TABLE checkout (
    checkoutID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    cartID INT,
    shippingAddress VARCHAR(255),
    city VARCHAR(100),
    governorate VARCHAR(100),
    building VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (cartID) REFERENCES cart(cartID)
) ENGINE=InnoDB;

INSERT INTO checkout (userID, cartID, shippingAddress, city, governorate, building)
VALUES
(1, 1, 'Nile Street', 'Cairo', 'Cairo', 'Building 12'),
(2, 2, 'Dokki Street', 'Giza', 'Giza', 'Building 8');

-- ================================================
-- ORDERS
-- ================================================
CREATE TABLE orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10,2),
    status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
    shippingAddress VARCHAR(255),
    deliveryDate DATE,
    paymentMethod VARCHAR(50),
    FOREIGN KEY (userID) REFERENCES users(userID)
) ENGINE=InnoDB;

INSERT INTO orders (userID, totalAmount, status, shippingAddress, deliveryDate, paymentMethod)
VALUES
(1, 899.98, 'Pending', 'Cairo Street', '2026-05-20', 'Cash'),
(2, 199.99, 'Processing', 'Giza Street', '2026-05-22', 'Visa');

-- ================================================
-- ORDER ITEMS
-- ================================================
CREATE TABLE order_items (
    itemID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    PID INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (orderID) REFERENCES orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (PID) REFERENCES product(PID)
) ENGINE=InnoDB;

INSERT INTO order_items (orderID, PID, quantity, price)
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
    amount DECIMAL(10,2),
    method VARCHAR(50),
    status ENUM('Pending','Completed','Failed') DEFAULT 'Pending',
    paymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderID) REFERENCES orders(orderID)
) ENGINE=InnoDB;

INSERT INTO payment (orderID, amount, method, status)
VALUES
(1, 899.98, 'Cash', 'Pending'),
(2, 199.99, 'Visa', 'Completed');

-- ================================================
-- FAVORITES
-- ================================================
CREATE TABLE favorites (
    favoriteID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    PID INT,
    addedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PID) REFERENCES product(PID) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO favorites (userID, PID)
VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1);

-- ================================================
-- REVIEW
-- ================================================
CREATE TABLE review (
    review_ID INT PRIMARY KEY AUTO_INCREMENT,
    prod_ID INT,
    userID INT,
    rating DECIMAL(3,2),
    comment TEXT,
    reviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    helpful_count INT DEFAULT 0,
    FOREIGN KEY (prod_ID) REFERENCES product(PID) ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO review (prod_ID, userID, rating, comment, helpful_count)
VALUES
(1, 1, 4.5, 'Very comfortable', 10),
(1, 2, 4.0, 'Good quality', 5),
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
    FOREIGN KEY (orderID) REFERENCES orders(orderID),
    FOREIGN KEY (userID) REFERENCES users(userID)
) ENGINE=InnoDB;

INSERT INTO refund (orderID, userID, refundAmount, status, reason)
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
    status ENUM('Pending','Approved','Rejected','Processed') DEFAULT 'Pending',
    exchangeDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderID) REFERENCES orders(orderID),
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (paymentID) REFERENCES payment(paymentID),
    FOREIGN KEY (oldProductID) REFERENCES product(PID),
    FOREIGN KEY (newProductID) REFERENCES product(PID)
) ENGINE=InnoDB;

INSERT INTO exchange (orderID, userID, paymentID, oldProductID, newProductID, reason, status)
VALUES
(1, 2, 1, 1, 2, 'Size issue', 'Pending');

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
) ENGINE=InnoDB;

INSERT INTO track_order (orderID, shippingAddress, trackingNumber, shippingFee, currentLocation, deliveryDate, deliveryName, deliveryPhone, status)
VALUES
(1, 'Cairo Street', 'TRK123456', 50.00, 'Warehouse', '2026-05-20', 'Ahmed Ali', '01012345678', 'Shipped'),
(2, 'Giza Street', 'TRK987654', 30.00, 'Transit', '2026-05-22', 'Sara Mohamed', '01111111111', 'Processing');

-- ================================================
-- SUPPORT CONTACTS
-- ================================================
CREATE TABLE support_contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('whatsapp','phone','email'),
    value VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO support_contacts (type, value, description)
VALUES
('whatsapp', '+201000000001', 'WhatsApp support'),
('phone', '+201000000003', 'Cairo hotline'),
('email', 'returns@tretto.com', 'Refunds support');

-- ================================================
-- FAQ
-- ================================================
CREATE TABLE faq (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(255),
    answer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

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