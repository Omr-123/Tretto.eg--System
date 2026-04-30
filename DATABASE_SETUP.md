# TRETTO E-COMMERCE SYSTEM - DATABASE SETUP GUIDE

## Database Installation Instructions

### Step 1: Import SQL Schema to MySQL

You have two options to import the database schema:

#### Option A: Using phpMyAdmin (Recommended for beginners)
1. Open phpMyAdmin in your browser (usually at `http://localhost/phpmyadmin`)
2. Click on "Import" tab at the top
3. Click "Choose File" and select `tretto_database.sql`
4. Click "Go" or "Import" button
5. Wait for the import to complete - you should see a success message

#### Option B: Using MySQL Command Line
1. Open Command Prompt or Terminal
2. Navigate to your project directory or where the SQL file is located
3. Run the following command:
```bash
mysql -u root -p < tretto_database.sql
```
4. When prompted, press Enter if there's no password (default), or enter your MySQL root password

#### Option C: Using MySQL Workbench
1. Open MySQL Workbench
2. Open your database connection
3. Go to File → Open SQL Script
4. Select `tretto_database.sql`
5. Click "Execute" (lightning bolt icon) to run the script

### Step 2: Verify Database Creation

After importing, verify that the database was created successfully:

**In phpMyAdmin:**
- You should see "Tretto" database in the left sidebar
- Click on it to see all the tables

**In Command Line:**
```sql
mysql -u root -p
USE Tretto;
SHOW TABLES;
```

You should see all 19 tables created:
- StoreLocation
- User, Person, Admin
- Product, Bag, Clogs, Slipper
- Order, Payment, Visa, Cash, Exchange, Refund, TrackOrder
- Cart, Favorite, Review
- Collection, Collection_Products
- Suppurt

### Step 3: Database Connection Verification

The `database.php` file is already configured and will automatically:
1. Connect to the Tretto database
2. Check connection status
3. Set UTF-8 charset

The connection uses:
- **Host:** localhost
- **Username:** root
- **Password:** (empty by default)
- **Database:** Tretto

### Step 4: Using the Database in Your Code

In your PHP files, the database connection is already available through:

```php
<?php
require_once('../../database.php');

// Use the global $conn variable
$conn->query("SELECT * FROM User");

// Or use the helper function
$connection = getConnection();

// Or execute a query directly
executeQuery("INSERT INTO User (...) VALUES (...)");
?>
```

### Step 5: Modify Database Credentials (If Needed)

If your MySQL credentials are different, update them in `database.php`:

```php
define('DB_HOST', 'localhost');   // Your host
define('DB_USER', 'root');        // Your username
define('DB_PASS', '');            // Your password
define('DB_NAME', 'Tretto');      // Database name
```

## Database Schema Overview

### Core Tables:
- **StoreLocation** - Physical store information
- **User** - User accounts (base table for Person and Admin)
- **Product** - Product catalog (base table for Bag, Clogs, Slipper)

### Relationships:
- Users can have multiple Orders
- Products can be in multiple Collections
- Orders have Payments and can be Refunded or Exchanged
- Orders can be tracked with TrackOrder
- Users can add Products to Cart or Favorites
- Users can write Reviews for Products

## Troubleshooting

### Issue: "Access denied for user 'root'@'localhost'"
**Solution:** Update the password in database.php if your MySQL root user has a password

### Issue: "Database doesn't exist"
**Solution:** Make sure you've successfully imported the SQL file

### Issue: "Table doesn't exist"
**Solution:** Verify all tables were created by running `SHOW TABLES;`

### Issue: "Connection refused"
**Solution:** Make sure MySQL server is running (check XAMPP/WAMP control panel)

## Next Steps

1. Your classes in `/MVC/Model/` are ready to use with the database
2. Start implementing CRUD methods in each class
3. Each class has public attributes matching the database columns
4. Use the `$conn` variable to execute database queries

## Files Structure

```
Tretto.eg--System/
├── database.php              (Database connection - configured)
├── tretto_database.sql       (Database schema - to import)
├── db_structure.php          (Class definitions)
└── MVC/
    └── Model/
        ├── admin.php
        ├── bag.php
        ├── cart.php
        ├── cash.php
        ├── clogs.php
        ├── collection.php
        ├── exchange.php
        ├── favorite.php
        ├── order.php
        ├── payment.php
        ├── person.php
        ├── product.php
        ├── refund.php
        ├── review.php
        ├── slipper.php
        ├── storelocation.php
        ├── suppurt.php
        ├── track_order.php
        ├── user.php
        └── visa.php
```

---
**Status:** Database schema ready for import and connection configured ✓
