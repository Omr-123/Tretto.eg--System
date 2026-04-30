    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/all.css">
        <title>Shopping Cart</title>
    </head>


   <body>

    <?php include 'navbar.php'; ?>
    <div class="page" id="page-cart">
        <div class="cart-container">
            <div class="cart-header">
                <h1>Shopping Cart</h1>
                <p id="cart-item-count">0 items in your cart</p>
            </div>

            <div class="cart-content">
                <div class="cart-items-section">
                    <div class="cart-items" id="cart-items">
                        <!-- Cart items will be dynamically loaded here via PHP -->
                        <!-- Sample cart item structure -->
                        <div class="cart-item" data-item-id="1">
                            <div class="item-image">
                                <img src="/images/product.jpg" alt="Product">
                            </div>
                            <div class="item-details">
                                <h3 class="item-name">Product Name</h3>
                                <p class="item-category">Category</p>
                                <p class="item-price">EGP 0.00</p>
                            </div>
                            <div class="item-quantity">
                                <button class="qty-btn minus" onclick="updateQty(this, -1)">−</button>
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="qty-btn plus" onclick="updateQty(this, 1)">+</button>
                            </div>
                            <div class="item-total">
                                <p class="total-price">EGP 0.00</p>
                            </div>
                            <button class="btn-remove" onclick="removeItem(this)">×</button>
                        </div>
                    </div>

                    <div class="cart-empty" id="cart-empty-msg" style="display: none;">
                        <p>Your cart is empty</p>
                        <button class="btn-primary" onclick="goTo('products')">Continue Shopping</button>
                    </div>
                </div>

                <div class="cart-summary-section">
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">EGP 0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span id="shipping">EGP 0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span id="tax">EGP 0.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="total">EGP 0.00</span>
                        </div>
                        <button class="btn-primary checkout-btn">Proceed to Checkout</button>
                        <button class="btn-secondary" onclick="goTo('products')">Continue Shopping</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script src="../javascript/cart.js" defer></script>
   </body>
</html>