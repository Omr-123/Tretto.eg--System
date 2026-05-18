<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!empty($_SESSION['logged_in'])) {
    header('Location: /Tretto.eg--System/MVC/View/GUI/index.php');
    exit;
}
$authError = $_SESSION['auth_error'] ?? '';
$authSuccess = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Tretto – Register</title>
</head>

<body>
    <div class="page" id="page-register">
        <div class="auth-wrap">
            <div class="auth-side">
                <div class="auth-side-tag">Join Tretto.eg 🌸</div>
                <h2 class="auth-side-title">Start your<br><em>gorgeous</em><br>journey</h2>
                <p class="auth-side-sub">Discover handcrafted clogs, slippers & bags made for you.</p>
            </div>
            <div class="auth-form">
                <div class="auth-form-tag">✨ Create Account</div>
                <h2 class="auth-form-title">Register</h2>
                <p class="auth-form-sub">Fill in your details to get started</p>

                <form method="POST" action="/Tretto.eg--System/MVC/Controller/AuthController.php">
                    <input type="hidden" name="action" value="register">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input class="form-input" name="fname" id="reg-fname" type="text" placeholder="Sara"
                                value="<?= $old['fname'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input class="form-input" name="lname" id="reg-lname" type="text" placeholder="Ahmed"
                                value="<?= $old['lname'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input class="form-input" name="email" id="reg-email" type="email" placeholder="your@email.com"
                            value="<?= $old['email'] ?? '' ?>" required>
                        <div class="error-msg" id="reg-email-err"
                            style="<?= $authError === 'email' ? '' : 'display:none' ?>">
                            This email is already registered or invalid.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input class="form-input" name="phone" id="reg-phone" type="tel" placeholder="01xxxxxxxxx"
                            value="<?= $old['phone'] ?? '' ?>">
                        <div class="error-msg" id="reg-phone-err"
                            style="<?= $authError === 'phone' ? '' : 'display:none' ?>">
                            Please enter a valid Egyptian phone number.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input class="form-input" name="password" id="reg-pass" type="password"
                            placeholder="Min. 8 characters" required>
                        <div class="error-msg" id="reg-pass-err"
                            style="<?= $authError === 'password' ? '' : 'display:none' ?>">
                            Password must be at least 8 characters.
                        </div>
                    </div>

                    <div class="error-msg" id="reg-global-err"
                        style="margin-bottom:12px;<?= ($authError && $authError !== 'email' && $authError !== 'phone' && $authError !== 'password') ? '' : 'display:none' ?>">
                        <?= $authError ?>
                    </div>

                    <button class="btn-primary" type="submit" style="width:100%">
                        Create My Account ✨
                    </button>
                </form>

                <div class="auth-switch" style="margin-top:16px">
                    Already have an account? <a href="/Tretto.eg--System/MVC/View/GUI/login.php">Sign In</a>
                </div>
            </div>

        </div>
    </div>
</body>
    <script src="../js/auth.js"></script>

</html>