<?php
ob_start();

require_once __DIR__ . '/../../Controller/AuthController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authError = '';
$old = ['email' => ''];
$authSuccess = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_success']);

$formAction = '/Tretto.eg--System/MVC/View/GUI/login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $result = (new AuthController())->processLogin($_POST);

    if ($result['success']) {
        ob_end_clean();
        header('Location: ' . $result['redirect']);
        exit;
    }

    $authError = $result['authError'];
    $old = $result['old'];
} elseif (!empty($_SESSION['logged_in'])) {
    ob_end_clean();
    $role = $_SESSION['user_type'] ?? 'user';
    $redirect = ($role === 'admin')
        ? '/Tretto.eg--System/MVC/View/GUI/admin-dashboard.php'
        : '/Tretto.eg--System/MVC/View/GUI/index.php';
    header('Location: ' . $redirect);
    exit;
}

ob_end_flush();
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

                <?php if ($authSuccess): ?>
                    <div class="success-msg" style="color:#1d9e75;margin-bottom:12px;">
                        <?= htmlspecialchars($authSuccess) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= htmlspecialchars($formAction) ?>">
                    <div class="form-group">
                        <label class="form-label" for="log-email">Email Address</label>
                        <input class="form-input<?= $authError !== '' ? ' input-error' : '' ?>" id="log-email"
                            name="email" type="email" placeholder="your@email.com"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>" autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="log-pass">Password</label>
                        <input class="form-input<?= $authError !== '' ? ' input-error' : '' ?>" id="log-pass"
                            name="password" type="password" placeholder="••••••••"
                            autocomplete="current-password" required>
                    </div>

                    <?php if ($authError !== ''): ?>
                        <div class="error-msg login-error-box" role="alert"
                            style="display:block;color:red;margin-bottom:12px;font-weight:500;">
                            <?= htmlspecialchars($authError) ?>
                        </div>
                    <?php endif; ?>

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

    <?php include 'component/footer.php'; ?>
</body>

</html>
