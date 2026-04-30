<?php
/**
 * Database Structure and Class Hierarchy
 * Tretto E-Commerce System
 * 
 * This file contains the complete database schema and class relationships
 */

// ============================================
// BASE CLASSES & PARENT CLASSES
// ============================================

/**
 * StoreLocation Class
 * Represents physical store locations
 */
class StoreLocation {
    private $storeID;
    private $name;
    private $address;
    private $phone;
    private $email;
    private $city;
    private $country;
    // Methods to be implemented
}

/**
 * User Class (Parent Class)
 * Base user information
 */
class User {
    private $userID;
    private $name;
    private $email;
    private $phone;
    private $password;
    private $registrationDate;
    private $address;
    private $city;
    private $country;
    // Methods to be implemented
}

/**
 * Person Class
 * Extends User - Regular customer information
 */
class Person extends User {
    private $dateOfBirth;
    private $gender;
    private $address;
    // Methods to be implemented
}

/**
 * Admin Class
 * Extends User - Administrator information
 */
class Admin extends User {
    private $adminID;
    private $role;
    private $permissions;
    private $dateOfHire;
    // Methods to be implemented
}

/**
 * Product Class (Parent Class)
 * Base product information
 */
class Product {
    private $prod_ID;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $category;
    private $image;
    private $storeID;
    private $rating;
    // Methods to be implemented
}

/**
 * Bag Class
 * Extends Product - Bag specific attributes
 */
class Bag extends Product {
    private $bag_type;
    private $material;
    private $capacity;
    // Methods to be implemented
}

/**
 * Clogs Class
 * Extends Product - Clogs specific attributes
 */
class Clogs extends Product {
    private $clog_string;
    private $size;
    private $color;
    // Methods to be implemented
}

/**
 * Slipper Class
 * Extends Product - Slipper specific attributes
 */
class Slipper extends Product {
    private $slipper_string;
    private $sole_type;
    private $size;
    // Methods to be implemented
}

// ============================================
// TRANSACTION & ORDER CLASSES
// ============================================

/**
 * Order Class
 * Represents customer orders
 */
class Order {
    private $order_ID;
    private $userID;
    private $orderDate;
    private $totalAmount;
    private $status;
    private $shippingAddress;
    private $paymentMethod;
    private $deliveryDate;
    // Methods to be implemented
}

/**
 * Payment Class (Parent Class)
 * Base payment information
 */
class Payment {
    private $payment_ID;
    private $order_ID;
    private $amount;
    private $paymentDate;
    private $method;
    private $status;
    // Methods to be implemented
}

/**
 * Visa Class
 * Extends Payment - Visa card payment
 */
class Visa extends Payment {
    private $cardNumber;
    private $cardHolderName;
    private $expiryDate;
    private $cvv;
    // Methods to be implemented
}

/**
 * Cash Class
 * Extends Payment - Cash payment method
 */
class Cash extends Payment {
    private $receivedAmount;
    private $changeAmount;
    private $paymentLocation;
    // Methods to be implemented
}

/**
 * Exchange Class
 * Extends Payment - Product exchange payment
 */
class Exchange extends Payment {
    private $oldProductID;
    private $newProductID;
    private $exchangeDate;
    private $reason;
    // Methods to be implemented
}

/**
 * Refund Class
 * Represents refund transactions
 */
class Refund {
    private $refund_ID;
    private $order_ID;
    private $refundAmount;
    private $refundDate;
    private $reason;
    private $status;
    // Methods to be implemented
}

/**
 * TrackOrder Class
 * Order tracking information
 */
class TrackOrder {
    private $track_ID;
    private $order_ID;
    private $status;
    private $lastUpdate;
    private $location;
    private $estimatedDelivery;
    // Methods to be implemented
}

// ============================================
// CUSTOMER INTERACTION CLASSES
// ============================================

/**
 * Cart Class
 * Shopping cart items
 */
class Cart {
    private $cart_ID;
    private $userID;
    private $product_ID;
    private $quantity;
    private $addedDate;
    // Methods to be implemented
}

/**
 * Favorite Class
 * User favorite products
 */
class Favorite {
    private $favorite_ID;
    private $userID;
    private $product_ID;
    private $addedDate;
    // Methods to be implemented
}

/**
 * Review Class
 * Product reviews
 */
class Review {
    private $review_ID;
    private $product_ID;
    private $userID;
    private $rating;
    private $comment;
    private $reviewDate;
    private $helpful_count;
    // Methods to be implemented
}

/**
 * Collection Class
 * Product collections/categories
 */
class Collection {
    private $collection_ID;
    private $name;
    private $description;
    private $createdDate;
    private $image;
    // Methods to be implemented
}

/**
 * Suppurt Class
 * Customer support tickets
 */
class Suppurt {
    private $support_ID;
    private $userID;
    private $issue;
    private $status;
    private $createdDate;
    private $responseDate;
    private $resolution;
    // Methods to be implemented
}

// ============================================
// DATABASE RELATIONSHIPS
// ============================================
/*
CLASS HIERARCHY:
├── StoreLocation
├── User
│   ├── Person
│   └── Admin
├── Product
│   ├── Bag
│   ├── Clogs
│   └── Slipper
├── Order
│   └── TrackOrder
├── Payment
│   ├── Visa
│   ├── Cash
│   └── Exchange
├── Refund
├── Cart
├── Favorite
├── Review
├── Collection
└── Suppurt

RELATIONSHIPS:
- StoreLocation (1) ──── (Many) Product
- User (1) ──── (Many) Order
- User (1) ──── (Many) Cart
- User (1) ──── (Many) Favorite
- User (1) ──── (Many) Review
- User (1) ──── (Many) Suppurt
- Order (1) ──── (Many) Payment
- Order (1) ──── (1) TrackOrder
- Order (1) ──── (1) Refund
- Product (1) ──── (Many) Review
- Product (1) ──── (Many) Cart
- Product (1) ──── (Many) Favorite
- Product (1) ──── (Many) Collection
*/
?>
