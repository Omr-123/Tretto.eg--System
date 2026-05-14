 
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
        <form action="products.php" method="GET" style="display: inline;">
            <button type="submit" class="nav-icon">🔍</button>
        </form>
        
        <a href="favorites.php" class="nav-icon">♡<span class="badge" id="fav-badge">0</span></a>
        
        <a href="cart.php" class="nav-icon">
            <span class="cart-icon-wrapper">🛒</span>
            <span class="badge" id="cart-badge">0</span>
        </a>
        
        <a href="profile.php" class="nav-icon">👤</a>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');

    // Animation: Change navbar style on scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.padding = "0.5rem 5%";
            navbar.style.boxShadow = "0 4px 20px rgba(232, 103, 138, 0.1)";
        } else {
            navbar.style.padding = "1rem 5%";
            navbar.style.boxShadow = "none";
        }
    });

    // Animation: Show a toast when something is added (Visual only)
    window.showToast = (message) => {
        const toast = document.getElementById('toast');
        const msgSpan = document.getElementById('tmsg');
        msgSpan.innerText = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    };
});
</script>
