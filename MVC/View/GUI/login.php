<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="../css/main.css">
      <link rel="stylesheet" href="../css/navbar.css">
      <link rel="stylesheet" href="../css/auth.css">
     <script src="assets/js/login.js" defer></script>
     <title>Document</title>
    <title>Document</title>
</head>
<body>
        <?php include 'component/navbar.php'; ?>

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
                <div class="form-group"><label class="form-label">Email Address</label><input class="form-input"
                        id="log-email" type="email" placeholder="your@email.com"></div>
                <div class="form-group"><label class="form-label">Password</label><input class="form-input"
                        id="log-pass" type="password" placeholder="••••••••"></div>
                <div class="error-msg" id="log-err" style="margin-bottom:12px">Incorrect email or password. Please try
                    again.</div>
                <button class="btn-primary" style="width:100%;margin-bottom:14px" onclick="doLogin()">Sign In
                    💕</button>
                <button class="btn-secondary" style="width:100%" onclick="goTo('home')">Continue as Guest</button>
                <div class="auth-switch" style="margin-top:16px">New to Tretto? <a onclick="goTo('register')">Create an
                        account</a></div>
            </div>
        </div>
    </div>
</body>
</html>