-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 18, 2026 at 07:34 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tretto`
--

-- --------------------------------------------------------

--
-- Table structure for table `bag`
--

DROP TABLE IF EXISTS `bag`;
CREATE TABLE IF NOT EXISTS `bag` (
  `PID` int NOT NULL,
  `capacityLiters` int DEFAULT NULL,
  `numpackets` int DEFAULT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bag`
--

INSERT INTO `bag` (`PID`, `capacityLiters`, `numpackets`) VALUES
(2, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cartID` int NOT NULL AUTO_INCREMENT,
  `ID` int NOT NULL,
  `total` int DEFAULT '0',
  PRIMARY KEY (`cartID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartID`, `ID`, `total`) VALUES
(3, 1, 0),
(4, 0, 0),
(5, 2, 0);

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

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cartID`, `PID`, `pvid`, `quantity`, `price`) VALUES
(3, 5, 1, 1, 299.99),
(3, 1, 1, 2, 299.99),
(5, 4, 1, 1, 299.99),
(5, 3, 3, 2, 199.00),
(3, 4, 1, 2, 299.99),
(5, 5, 5, 4, 550.00);

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

DROP TABLE IF EXISTS `checkout`;
CREATE TABLE IF NOT EXISTS `checkout` (
  `checkoutID` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `cartID` int NOT NULL,
  `shippingAddress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `governorate` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliveryDate` date NOT NULL,
  `paymentMethod` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`checkoutID`),
  KEY `userID` (`userID`),
  KEY `cartID` (`cartID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`checkoutID`, `userID`, `cartID`, `shippingAddress`, `city`, `governorate`, `building`, `deliveryDate`, `paymentMethod`, `created_at`) VALUES
(1, 2, 2, 'Cairo Street', 'Cairo', 'Cairo', 'Building 12', '2026-05-20', 'Cash', '2026-05-18 11:59:44'),
(2, 3, 3, 'Giza Street', 'Giza', 'Giza', 'Building 8', '2026-05-22', 'Visa', '2026-05-18 11:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `clogs`
--

DROP TABLE IF EXISTS `clogs`;
CREATE TABLE IF NOT EXISTS `clogs` (
  `PID` int NOT NULL,
  `heelHeight` decimal(5,2) DEFAULT NULL,
  `strapType` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clogs`
--

INSERT INTO `clogs` (`PID`, `heelHeight`, `strapType`) VALUES
(1, 5.50, 'Ankle Strap');

-- --------------------------------------------------------

--
-- Table structure for table `exchange`
--

DROP TABLE IF EXISTS `exchange`;
CREATE TABLE IF NOT EXISTS `exchange` (
  `exchangeID` int NOT NULL AUTO_INCREMENT,
  `orderID` int DEFAULT NULL,
  `userID` int DEFAULT NULL,
  `paymentID` int DEFAULT NULL,
  `oldProductID` int DEFAULT NULL,
  `newProductID` int DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Approved','Rejected','Processed') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `exchangeDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`exchangeID`),
  KEY `orderID` (`orderID`),
  KEY `userID` (`userID`),
  KEY `paymentID` (`paymentID`),
  KEY `oldProductID` (`oldProductID`),
  KEY `newProductID` (`newProductID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exchange`
--

INSERT INTO `exchange` (`exchangeID`, `orderID`, `userID`, `paymentID`, `oldProductID`, `newProductID`, `reason`, `status`, `exchangeDate`) VALUES
(1, 1, 2, 1, 1, 2, 'Size issue', 'Pending', '2026-05-18 11:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `created_at`) VALUES
(1, 'How do I track my order?', 'Once your order is shipped you will receive a tracking number by email or SMS. You can also log in to your account and visit the \"Track Order\" section to see the current location and estimated delivery date.', '2026-05-18 11:59:44'),
(2, 'Can I return or exchange an item?', 'Yes. You may request a return or exchange within 14 days of receiving your order, provided the item is unused and in its original packaging. Visit the \"Refunds & Exchanges\" section in your account or contact our support team to start the process.', '2026-05-18 11:59:44'),
(3, 'How long does delivery take?', 'Standard delivery within Cairo and Giza takes 2–3 business days. Other governorates typically take 4–6 business days. You will see the estimated delivery date on your order confirmation page.', '2026-05-18 11:59:44'),
(4, 'How do I cancel my order?', 'Orders can be cancelled before they are shipped. Log in to your account, go to \"My Orders\", and select \"Cancel Order\" if the option is still available. If the order has already been shipped, please wait for delivery and then submit a return request.', '2026-05-18 11:59:44'),
(5, 'How do I contact customer support?', 'You can reach us via WhatsApp, phone, or email. Our contact details are listed on the Contact Us page. Support is available Saturday to Thursday 10:00 AM – 10:00 PM.', '2026-05-18 11:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `favoriteID` int NOT NULL AUTO_INCREMENT,
  `userID` int DEFAULT NULL,
  `PID` int DEFAULT NULL,
  `addedDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`favoriteID`),
  KEY `userID` (`userID`),
  KEY `PID` (`PID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favoriteID`, `userID`, `PID`, `addedDate`) VALUES
(1, 1, 1, '2026-05-18 11:59:44'),
(10, 1, 3, '0000-00-00 00:00:00'),
(11, 1, 1, '0000-00-00 00:00:00'),
(4, 3, 1, '2026-05-18 11:59:44'),
(12, 1, 1, '0000-00-00 00:00:00'),
(9, 2, 3, '0000-00-00 00:00:00'),
(8, 2, 1, '0000-00-00 00:00:00'),
(13, 1, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int NOT NULL AUTO_INCREMENT,
  `userID` int DEFAULT NULL,
  `orderDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `totalAmount` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `shippingAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliveryDate` date DEFAULT NULL,
  `paymentMethod` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`orderID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `orderDate`, `totalAmount`, `status`, `shippingAddress`, `deliveryDate`, `paymentMethod`) VALUES
(1, 2, '2026-05-18 11:59:44', 899.98, 'Pending', 'Cairo Street', '2026-05-20', 'Cash'),
(2, 3, '2026-05-18 11:59:44', 199.99, 'Processing', 'Giza Street', '2026-05-22', 'Visa');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `itemID` int NOT NULL AUTO_INCREMENT,
  `orderID` int DEFAULT NULL,
  `PID` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`itemID`),
  KEY `orderID` (`orderID`),
  KEY `PID` (`PID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`itemID`, `orderID`, `PID`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 299.99),
(2, 1, 2, 1, 499.99),
(3, 2, 3, 1, 199.99);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `paymentID` int NOT NULL AUTO_INCREMENT,
  `orderID` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Completed','Failed') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `paymentDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`paymentID`),
  KEY `orderID` (`orderID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `orderID`, `amount`, `method`, `status`, `paymentDate`) VALUES
(1, 1, 899.98, 'Cash', 'Pending', '2026-05-18 11:59:44'),
(2, 2, 199.99, 'Visa', 'Completed', '2026-05-18 11:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`PID`, `name`, `price`, `descriptions`, `category`, `Number_Of_Sells`, `created_at`, `BranchID`) VALUES
(1, 'Rose Pink Pink Clog Soft', 299.99, 'Stylish rose pink clogs with comfortable heel height.', 'Clog', 0, '2026-05-15 11:48:04', 1),
(2, 'Black Crossbody Bag', 499.99, 'Elegant black crossbody bag with multiple compartments.', 'Bag', 0, '2026-05-15 11:48:04', 2),
(3, 'Black Slipper', 199.99, 'Cozy blue slippers made from soft materials.', 'Slipper', 0, '2026-05-15 11:48:04', 3),
(4, 'SIRENA PEARL SLIDES - Golden Hour', 349.99, 'Step into elegance with our Sirena Pearl Slides, where every detail whispers sophistication. Intricately hand-embellished with clusters of glossy pearls, these slides are the perfect blend of luxe and ease. Designed for bridal events, chic evenings, or just elevating your everyday look, Sirena brings shimmer and femininity with every step. Crafted with comfort in mind, they’re as wearable as they are unforgettable..', 'Slipper', 5, '2026-05-15 11:48:04', 1),
(5, 'Loafer Clog', 550.00, 'Comfort clog designed for everyday wear, providing lightweight support, breathable material, and a durable sole for long-lasting comfort.', 'Clog', 0, '2026-05-18 18:36:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `piid` int NOT NULL AUTO_INCREMENT,
  `pvid` int DEFAULT NULL,
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`piid`),
  KEY `pvid` (`pvid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`piid`, `pvid`, `images`) VALUES
(1, 1, '../assets/images/rose_pink_clogs_1.png'),
(2, 1, '../assets/images/rose_pink_clogs_2.png'),
(3, 1, '../assets/images/rose_pink_clogs_3.png'),
(4, 2, 'rose_pink_clogs_1.png'),
(5, 3, '../assets/images/Slipper_Black1.webp'),
(6, 3, '../assets/images/Slipper_Black2.webp'),
(7, 4, '../assets/images/Slipper_Golden1.webp'),
(8, 4, '../assets/images/Slipper_Golden2.webp'),
(9, 4, '../assets/images/Slipper_Golden3.webp'),
(10, 5, '../assets/images/Clog_Green1.webp'),
(11, 5, '../assets/images/Clog_Green2.webp'),
(12, 5, '../assets/images/Clog_Green3.webp');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`pvid`, `PID`, `color`, `stock`, `add_price`, `sizes`) VALUES
(1, 1, '#7BA7BC', 30, 100.00, 38),
(2, 2, '#6BA7BC', 30, 0.00, 30),
(3, 3, '#050505', 100, 0.00, 36),
(4, 4, '#FFD700', 30, 15.00, 32),
(5, 5, '#008000', 25, 0.00, 38),
(6, 5, '#008000', 25, 0.00, 39),
(7, 5, '#008000', 25, 0.00, 40),
(8, 5, '#008000', 25, 0.00, 41);

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

DROP TABLE IF EXISTS `refund`;
CREATE TABLE IF NOT EXISTS `refund` (
  `refundID` int NOT NULL AUTO_INCREMENT,
  `orderID` int DEFAULT NULL,
  `userID` int DEFAULT NULL,
  `refundAmount` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Processed') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `refundDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`refundID`),
  KEY `orderID` (`orderID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `refund`
--

INSERT INTO `refund` (`refundID`, `orderID`, `userID`, `refundAmount`, `status`, `reason`, `refundDate`) VALUES
(1, 1, 2, 100.00, 'Pending', 'Late delivery', '2026-05-18 11:59:44'),
(2, 2, 3, 50.00, 'Approved', 'Wrong size', '2026-05-18 11:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review` (
  `review_ID` int NOT NULL AUTO_INCREMENT,
  `prod_ID` int NOT NULL,
  `userID` int NOT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `reviewDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `helpful_count` int DEFAULT '0',
  PRIMARY KEY (`review_ID`),
  KEY `prod_ID` (`prod_ID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_ID`, `prod_ID`, `userID`, `rating`, `comment`, `reviewDate`, `helpful_count`) VALUES
(1, 1, 1, 4.50, 'Very comfortable and stylish!', '2026-05-18 11:59:44', 10),
(2, 1, 2, 4.00, 'Good quality but expensive', '2026-05-18 11:59:44', 5),
(3, 2, 2, 5.00, 'Perfect bag!', '2026-05-18 11:59:44', 12),
(4, 3, 3, 4.20, 'Soft and cozy', '2026-05-18 11:59:44', 3),
(5, 2, 1, 3.80, 'Nice but average', '2026-05-18 11:59:44', 2);

-- --------------------------------------------------------

--
-- Table structure for table `slipper`
--

DROP TABLE IF EXISTS `slipper`;
CREATE TABLE IF NOT EXISTS `slipper` (
  `PID` int NOT NULL,
  `materialsoftness` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slipper`
--

INSERT INTO `slipper` (`PID`, `materialsoftness`) VALUES
(3, 'Ultra Soft');

-- --------------------------------------------------------

--
-- Table structure for table `storelocation`
--

DROP TABLE IF EXISTS `storelocation`;
CREATE TABLE IF NOT EXISTS `storelocation` (
  `BranchID` int NOT NULL AUTO_INCREMENT,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sat_thu_hours` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `friday_hours` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_link` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`BranchID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `storelocation`
--

INSERT INTO `storelocation` (`BranchID`, `city`, `name`, `address`, `phone`, `email`, `sat_thu_hours`, `friday_hours`, `map_link`) VALUES
(1, 'Cairo', 'Main Branch', '123 Nile Street', '01000000001', 'main@tretto.com', '10:00 AM - 10:00 PM', '2:00 PM - 10:00 PM', 'https://maps.google.com/main'),
(2, 'Alexandria', 'Alex Branch', '45 Sea Road', '01000000002', 'alex@tretto.com', '10:00 AM - 11:00 PM', 'Closed', 'https://maps.google.com/alex');

-- --------------------------------------------------------

--
-- Table structure for table `support_contacts`
--

DROP TABLE IF EXISTS `support_contacts`;
CREATE TABLE IF NOT EXISTS `support_contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('whatsapp','phone','email') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_contacts`
--

INSERT INTO `support_contacts` (`id`, `type`, `value`, `description`, `created_at`) VALUES
(1, 'whatsapp', '+201000000001', 'WhatsApp support — available Sat–Thu 10 AM–10 PM', '2026-05-18 11:59:44'),
(2, 'phone', '+201000000003', 'Cairo main branch direct line', '2026-05-18 11:59:44'),
(3, 'email', 'returns@tretto.com', 'Refunds and exchange requests', '2026-05-18 11:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `track_order`
--

DROP TABLE IF EXISTS `track_order`;
CREATE TABLE IF NOT EXISTS `track_order` (
  `trackID` int NOT NULL AUTO_INCREMENT,
  `orderID` int DEFAULT NULL,
  `shippingAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trackingNumber` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shippingFee` decimal(10,2) DEFAULT NULL,
  `currentLocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliveryDate` date DEFAULT NULL,
  `deliveryName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deliveryPhone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`trackID`),
  UNIQUE KEY `orderID` (`orderID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `track_order`
--

INSERT INTO `track_order` (`trackID`, `orderID`, `shippingAddress`, `trackingNumber`, `shippingFee`, `currentLocation`, `deliveryDate`, `deliveryName`, `deliveryPhone`, `status`) VALUES
(1, 1, 'Cairo Street', 'TRK123456', 50.00, 'Warehouse', '2026-05-20', 'Ahmed Ali', '01012345678', 'Shipped'),
(2, 2, 'Giza Street', 'TRK987654', 30.00, 'Transit', '2026-05-22', 'Sara Mohamed', '01111111111', 'Processing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `registrationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `phone`, `password`, `address`, `city`, `country`, `role`, `registrationDate`) VALUES
(1, 'Ziad Hany', 'ziad@test.com', '01555594689', '123456', 'Nile St', 'Cairo', 'Egypt', 'admin', '2026-05-18 11:59:43'),
(2, 'Ahmed Ali', 'ahmed@test.com', '01012345678', '123456', 'Dokki', 'Giza', 'Egypt', 'user', '2026-05-18 11:59:43'),
(3, 'Sara Mohamed', 'sara@test.com', '01111111111', '123456', 'Maadi', 'Cairo', 'Egypt', 'user', '2026-05-18 11:59:43');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
