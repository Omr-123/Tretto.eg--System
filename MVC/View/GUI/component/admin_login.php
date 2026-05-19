<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../config.php';
ensure_session();
if (isset($_SESSION['role'], $_SESSION['admin_id']) && $_SESSION['role'] === 'Admin') {
    redirect_to('MVC/View/GUI/component/admin_dashboard.php');
}
$error = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tretto Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: Arial, sans-serif;
            background: #F9F0F4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .card {
            background: #FFFAFC;
            border: 1px solid rgba(232, 103, 138, .18);
            border-radius: 20px;
            padding: 42px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 35px rgba(232, 103, 138, .08)
        }

        h1 {
            text-align: center;
            color: #2D1B25;
            margin-bottom: 8px
        }

        h1 span {
            color: #E8678A
        }

        .sub {
            text-align: center;
            color: #A07088;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 28px
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6B3D52;
            margin-bottom: 7px
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1.5px solid rgba(232, 103, 138, .18);
            background: #FFF0F3;
            border-radius: 10px;
            margin-bottom: 17px
        }

        .btn {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 10px;
            background: #E8678A;
            color: white;
            font-weight: bold;
            cursor: pointer
        }

        .err {
            background: #FEE2E2;
            color: #991B1B;
            padding: 10px;
            border-radius: 9px;
            margin-bottom: 15px;
            font-size: 13px
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Tretto<span>.</span>eg</h1>
        <div class="sub">Admin Dashboard</div><?php if ($error): ?>
            <div class="err">Invalid email or password.</div><?php endif; ?>
        <form method="POST"
            action="<?= htmlspecialchars(app_url('MVC/Controller/AdminController.php?action=login')) ?>">
            <label>Email</label><input type="email" name="email" placeholder="admin@test.com"
                required><label>Password</label><input type="password" name="password" placeholder="••••••••"
                required><button class="btn" type="submit">Sign In</button></form>
    </div>
</body>

</html>