
    <div class="hearts-bg" id="hearts-bg"></div>

    <div id="cur"></div>
    <div id="cur-r"></div>
    <div id="toast"><span class="t-ico">✨</span><span id="tmsg"></span></div>
    <div id="confirm-modal">
        <div class="confirm-box">
            <div class="confirm-title">Remove Item? 🛍</div>
            <div class="confirm-txt">Are you sure you want to remove this item from your cart?</div>
            <div class="confirm-acts">
                <button class="btn-primary" style="padding:10px 20px" onclick="confirmRemove()">Yes, Remove</button>
                <button class="btn-secondary" style="padding:10px 20px" onclick="closeConfirm()">Cancel</button>
            </div>
        </div>
    </div>
<nav id="navbar">
    <a class="logo" onclick="goTo('home')">Tretto<span>.</span>eg <span class="logo-heart">♥</span></a>
    <ul class="nav-links">
        <li><a href="index.php" id="nl-home">Home</a></li>
        <li><a href="products.php" id="nl-products">Products</a></li>
        <li><a href="track.php" id="nl-track">Track Order</a></li>
        <li><a href="location.php" id="nl-location">Stores</a></li>
        <li><a href="support.php" id="nl-support">Support</a></li>
    </ul>
    <div class="nav-acts">
        <button class="nav-icon" onclick="goTo('products.php')">🔍</button>
        <button class="nav-icon" onclick="goTo('favorites.php')">♡<span class="badge" id="fav-badge">0</span></button>
        <button class="nav-icon" onclick="goTo('cart.php')">🛍<span class="badge" id="cart-badge">3</span></button>
        <button class="btn-nav" id="auth-btn" onclick="goTo('register.php')">Sign In</button>
    </div>
</nav>
