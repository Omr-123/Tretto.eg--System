<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['logged_in'])) {
    header('Location: /Tretto.eg--System/MVC/View/GUI/index.php');
    exit;
}

$fieldErrors = $_SESSION['field_errors'] ?? [];
$globalError = $fieldErrors['_global'] ?? '';
unset($_SESSION['field_errors']);

$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

function fieldClass(string $key, array $errors): string
{
    return isset($errors[$key]) ? 'input-error' : '';
}
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

                <form id="register-form" method="POST" action="/Tretto.eg--System/MVC/Controller/AuthController.php" novalidate>
                    <input type="hidden" name="action" value="register">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="reg-fname">First Name</label>
                            <input class="form-input <?= fieldClass('fname', $fieldErrors) ?>" name="fname" id="reg-fname"
                                type="text" placeholder="Sara" value="<?= htmlspecialchars($old['fname'] ?? '') ?>"
                                autocomplete="given-name">
                            <div class="error-msg" id="reg-fname-err" role="alert"
                                <?= isset($fieldErrors['fname']) ? '' : 'style="display:none"' ?>>
                                <?= htmlspecialchars($fieldErrors['fname'] ?? '') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="reg-lname">Last Name</label>
                            <input class="form-input <?= fieldClass('lname', $fieldErrors) ?>" name="lname" id="reg-lname"
                                type="text" placeholder="Ahmed" value="<?= htmlspecialchars($old['lname'] ?? '') ?>"
                                autocomplete="family-name">
                            <div class="error-msg" id="reg-lname-err" role="alert"
                                <?= isset($fieldErrors['lname']) ? '' : 'style="display:none"' ?>>
                                <?= htmlspecialchars($fieldErrors['lname'] ?? '') ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-email">Email Address</label>
                        <input class="form-input <?= fieldClass('email', $fieldErrors) ?>" name="email" id="reg-email"
                            type="email" placeholder="your@email.com"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>" autocomplete="email">
                        <div class="error-msg" id="reg-email-err" role="alert"
                            <?= isset($fieldErrors['email']) ? '' : 'style="display:none"' ?>>
                            <?= htmlspecialchars($fieldErrors['email'] ?? '') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-phone">Phone Number</label>
                        <input class="form-input <?= fieldClass('phone', $fieldErrors) ?>" name="phone" id="reg-phone"
                            type="tel" placeholder="01xxxxxxxxx"
                            value="<?= htmlspecialchars($old['phone'] ?? '') ?>" autocomplete="tel">
                        <div class="error-msg" id="reg-phone-err" role="alert"
                            <?= isset($fieldErrors['phone']) ? '' : 'style="display:none"' ?>>
                            <?= htmlspecialchars($fieldErrors['phone'] ?? '') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-pass">Password</label>
                        <input class="form-input <?= fieldClass('password', $fieldErrors) ?>" name="password"
                            id="reg-pass" type="password" placeholder="Min. 8 characters" autocomplete="new-password">
                        <div class="error-msg" id="reg-pass-err" role="alert"
                            <?= isset($fieldErrors['password']) ? '' : 'style="display:none"' ?>>
                            <?= htmlspecialchars($fieldErrors['password'] ?? '') ?>
                        </div>
                    </div>

                    <?php if ($globalError): ?>
                        <div class="error-msg" id="reg-global-err" role="alert" style="margin-bottom:12px">
                            <?= htmlspecialchars($globalError) ?>
                        </div>
                    <?php endif; ?>

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
    <script src="../javascript/auth.js"></script>
</body>

</html>
