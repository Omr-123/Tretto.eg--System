<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../css/main.css">
      <link rel="stylesheet" href="../css/auth.css">
    <title>Document</title>
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
                <div class="form-row">
                    <div class="form-group"><label class="form-label">First Name</label><input class="form-input"
                            id="reg-fname" type="text" placeholder="Sara"></div>
                    <div class="form-group"><label class="form-label">Last Name</label><input class="form-input"
                            id="reg-lname" type="text" placeholder="Ahmed"></div>
                </div>
                <div class="form-group"><label class="form-label">Email Address</label><input class="form-input"
                        id="reg-email" type="email" placeholder="your@email.com">
                    <div class="error-msg" id="reg-email-err">This email is already registered or invalid.</div>
                </div>
                <div class="form-group"><label class="form-label">Phone Number</label><input class="form-input"
                        id="reg-phone" type="tel" placeholder="01xxxxxxxxx">
                    <div class="error-msg" id="reg-phone-err">Please enter a valid Egyptian phone number.</div>
                </div>
                <div class="form-group"><label class="form-label">Password</label><input class="form-input"
                        id="reg-pass" type="password" placeholder="Min. 8 characters">
                    <div class="error-msg" id="reg-pass-err">Password must be at least 8 characters.</div>
                </div>
                <div class="error-msg" id="reg-global-err" style="margin-bottom:12px">Please fill in all required
                    fields.</div>
                <button class="btn-primary" style="width:100%" onclick="doRegister()">Create My Account ✨</button>
                <div class="auth-switch" style="margin-top:16px">Already have an account? <a
                        onclick="goTo('login')">Sign In</a></div>
            </div>
        </div>
    </div>
</body>
</html>