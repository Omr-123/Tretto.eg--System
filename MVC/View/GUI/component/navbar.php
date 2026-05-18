<?php
require_once __DIR__ . '/../../../../db.php';
require_once __DIR__ . '/../../../Controller/UserController.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

$isLoggedIn = !empty($_SESSION['logged_in']);
$userName = $_SESSION['name'] ?? '';
$Nav=new UserController();
$numCart=0;
// فاف counter
$favCount = 0;
if ($isLoggedIn) {
    require_once __DIR__ . '/../../../../db.php';
    $userID = intval($_SESSION['userID']);
    $favCount = $Nav->Fav_Num($_SESSION['userID']);
    $numCart=$Nav->cart_Number($_SESSION['userID']);
}
?>

<nav id="navbar">
    <a class="logo" href="/Tretto.eg--System/MVC/View/GUI/index.php">Tretto<span>.</span>eg <span
            class="logo-heart">♥</span></a>
    <ul class="nav-links">
        <li><a href="/../index.php" id="nl-home">Home</a></li>
        <li><a href="/../products.php" id="nl-products">Products</a></li>
        <li><a href="/../trackorder.php" id="nl-track">Track Order</a></li>
        <li><a href="/../location.php" id="nl-location">Stores</a></li>
        <li><a href="/../support.php" id="nl-support">Support</a></li>
    </ul>
    <div class="nav-acts">
        <form action="/Tretto.eg--System/MVC/View/GUI/products.php" method="GET" style="display:inline;">
            <button type="submit" class="nav-icon">🔍</button>
        </form>

        <a href="/Tretto.eg--System/MVC/View/GUI/favorite.php" class="nav-icon">
            ♡<span class="badge" id="fav-badge"><?= $favCount ?></span>
        </a>

        <a href="/Tretto.eg--System/MVC/View/GUI/cart.php" class="nav-icon">
            <span class="cart-icon-wrapper">🛒</span>
            <span class="badge" id="cart-badge"><?= $numCart;?> </span>
        </a>

        <?php if ($isLoggedIn): ?>
            <span class="nav-username">Hi, <?= $userName ?> 💕</span>
            <form method="POST" action="/Tretto.eg--System/MVC/Controller/AuthController.php" style="display:inline;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btn-nav">Logout</button>
            </form>
        <?php else: ?>
            <a href="/Tretto.eg--System/MVC/View/GUI/register.php" class="btn-nav" id="auth-btn">Sign In</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.padding = "0.5rem 5%";
                navbar.style.boxShadow = "0 4px 20px rgba(232, 103, 138, 0.1)";
            } else {
                navbar.style.padding = "1rem 5%";
                navbar.style.boxShadow = "none";
            }
        });

        window.showToast = (message) => {
            const toast = document.getElementById('toast');
            const msgSpan = document.getElementById('tmsg');
            msgSpan.innerText = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        };
    });
</script>