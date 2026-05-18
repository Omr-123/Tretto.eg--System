<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!empty($_SESSION['logged_in'])) {
    $role = $_SESSION['user_type'] ?? 'user';
    $redirect = ($role === 'admin')
        ? '/Tretto.eg--System/MVC/View/GUI/admin-dashboard.php'
        : '/Tretto.eg--System/MVC/View/GUI/index.php';
    header("Location: $redirect");
    exit;
}
$authError = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Tretto – Sign In</title>
</head>

<body>

    <div class="page" id="page-login">
        <div class="auth-wrap">
            <div class="auth-side">
                <div class="auth-side-tag">Welcome Back 💕</div>
                <h2 class="auth-side-title">So happy<br>to see you <em>again</em></h2>
                <p class="auth-side-sub">Sign in to access your orders, wishlist, and exclusive member offers.</p>
            </div>
            <div class="auth-form">
                <div class="auth-form-tag">💗 Sign In</div>
                <h2 class="auth-form-title">Login</h2>
                <p class="auth-form-sub">Enter your credentials to continue</p>

                <form method="POST" action="/Tretto.eg--System/MVC/Controller/AuthController.php">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input class="form-input" id="log-email" name="email" type="email" placeholder="your@email.com"
                            value="<?= $_POST['email'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input class="form-input" id="log-pass" name="password" type="password" placeholder="••••••••"
                            required>
                    </div>

                    <div class="error-msg" id="log-err"
                        style="margin-bottom:12px;<?= $authError ? '' : 'display:none' ?>">
                        <?= $authError ?>
                    </div>

                    <button class="btn-primary" type="submit" style="width:100%;margin-bottom:14px">
                        Sign In 💕
                    </button>
                </form>

                <button class="btn-secondary" style="width:100%;margin-bottom:14px"
                    onclick="window.location.href='/Tretto.eg--System/MVC/View/GUI/index.php'">
                    Continue as Guest
                </button>

                <div class="auth-switch" style="margin-top:16px">
                    New to Tretto? <a href="/Tretto.eg--System/MVC/View/GUI/register.php">Create an account</a>
                </div>
            </div>

        </div>
    </div>
</body>

</html>