<?php
require_once __DIR__ . '/../../../config.php';
ensure_session();

if (!isset($_SESSION['role'], $_SESSION['admin_id']) || $_SESSION['role'] !== 'Admin') {
    redirect_to('MVC/View/GUI/component/admin_login.php');
}

require_once __DIR__ . '/../../../Controller/AdminController.php';
$controller = $controller ?? new AdminController();
$APP_BASE = app_base_url();
$adminName = $_SESSION['admin_name'] ?? 'Admin';

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function admin_img_url(?string $image): string {
    $image = trim((string)$image);
    if ($image === '') return 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="90" height="70"><rect width="100%" height="100%" fill="#fff0f3"/><text x="50%" y="52%" dominant-baseline="middle" text-anchor="middle" fill="#a07088" font-size="11">No Image</text></svg>');
    if (preg_match('/^https?:\/\//i', $image) || str_starts_with($image, '/') || str_starts_with($image, '../')) return $image;
    if (str_contains($image, 'assets/images/')) return app_url('MVC/View/' . ltrim(substr($image, strpos($image, 'assets/images/')), '/'));
    return app_url('MVC/View/assets/images/' . $image);
}

function admin_header(string $title = 'Admin', string $active = ''): void {
    global $APP_BASE, $adminName;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($title) ?> — Tretto Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}:root{--blush:#FFF0F3;--rose:#E8678A;--rose-d:#C44A6D;--dark:#2D1B25;--mid:#6B3D52;--muted:#A07088;--white:#FFFAFC;--border:rgba(232,103,138,.18);--danger:#e74c3c}body{font-family:Arial,sans-serif;background:#F9F0F4;color:var(--dark)}.layout{display:flex;min-height:100vh}.sidebar{width:240px;background:var(--white);border-right:1px solid var(--border);position:fixed;height:100vh;padding:22px 14px;overflow:auto}.logo{font-size:24px;font-weight:800;margin-bottom:4px}.logo span{color:var(--rose)}.sub{font-size:12px;color:var(--muted);margin-bottom:25px}.nav-title{font-size:10px;text-transform:uppercase;letter-spacing:.14em;color:var(--muted);margin:17px 10px 8px;font-weight:bold}.nav-item{display:flex;gap:9px;align-items:center;padding:11px 12px;border-radius:10px;color:var(--mid);font-size:13px;font-weight:bold;cursor:pointer;text-decoration:none;margin-bottom:4px}.nav-item:hover,.nav-item.active{background:rgba(232,103,138,.1);color:var(--rose-d)}.side-footer{margin-top:25px;border-top:1px solid var(--border);padding-top:15px}.logout{display:inline-block;background:#FEE2E2;color:#991B1B;text-decoration:none;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:bold;margin-top:12px}.main{margin-left:240px;flex:1}.topbar{height:64px;background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 32px;position:sticky;top:0;z-index:5}.title{font-size:21px;font-weight:800}.content{padding:30px}.section{display:none}.section.active{display:block}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:22px}.stat{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:20px}.stat-label{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.12em;font-weight:bold;margin-bottom:8px}.stat-value{font-size:28px;font-weight:800}.card,.form-box{background:var(--white);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:20px}.form-box{padding:25px}.card-head{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}.card-title{font-weight:800;font-size:17px}table{width:100%;border-collapse:collapse}th{background:var(--blush);color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.1em;text-align:left;padding:12px}td{padding:12px;border-bottom:1px solid var(--border);font-size:13px;vertical-align:middle}tr:last-child td{border-bottom:none}.badge{display:inline-block;padding:4px 10px;border-radius:14px;font-size:10px;font-weight:bold;text-transform:uppercase}.badge-low{background:#FEF3C7;color:#92400E}.badge-active{background:#D1FAE5;color:#065F46}.badge-out,.badge-cancelled,.badge-rejected{background:#FEE2E2;color:#991B1B}.badge-pending{background:#FEF3C7;color:#92400E}.badge-processing{background:#DBEAFE;color:#1E40AF}.badge-shipped{background:#E0F2FE;color:#0369A1}.badge-delivered,.badge-approved{background:#D1FAE5;color:#065F46}.btn{display:inline-block;border:none;border-radius:8px;padding:7px 12px;cursor:pointer;font-weight:bold;font-size:12px;text-decoration:none;margin:2px}.btn-rose{background:var(--rose);color:white}.btn-danger{background:#FEE2E2;color:#991B1B}.btn-warning{background:#FEF3C7;color:#92400E}.btn-outline{background:white;color:var(--rose-d);border:1px solid var(--border)}.grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}.group{margin-bottom:15px}.full{grid-column:1/-1}label{display:block;font-size:11px;color:var(--mid);font-weight:bold;text-transform:uppercase;margin-bottom:7px;letter-spacing:.08em}input,select,textarea{width:100%;padding:11px 12px;border:1.5px solid var(--border);border-radius:9px;background:var(--blush);outline:none}textarea{resize:vertical}.img-thumb,.thumb{width:78px;height:60px;object-fit:cover;border-radius:9px;border:1px solid var(--border);background:var(--blush)}.thumb{width:95px;height:75px}.alert{display:none;margin-bottom:14px;padding:12px;border-radius:10px;font-size:13px}.alert.show{display:block}.alert.err{background:#FEE2E2;color:#991B1B}.alert.ok{background:#D1FAE5;color:#065F46}#toast{position:fixed;right:25px;bottom:25px;background:var(--dark);color:white;padding:12px 18px;border-radius:12px;transform:translateY(80px);opacity:0;transition:.25s;z-index:99}#toast.show{transform:translateY(0);opacity:1}.muted{color:var(--muted);font-size:12px}.image-grid{display:flex;gap:10px;flex-wrap:wrap}.image-box{display:inline-flex;flex-direction:column;gap:6px;align-items:flex-start}.type{display:none}@media(max-width:900px){.stats{grid-template-columns:1fr}.grid{grid-template-columns:1fr}.sidebar{position:static;width:100%;height:auto}.main{margin-left:0}.layout{display:block}}
</style>
</head>
<body>
<div id="toast"></div>
<div class="layout">
<aside class="sidebar">
    <div class="logo">Tretto<span>.</span>eg</div><div class="sub">Admin Dashboard</div>
    <div class="nav-title">Overview</div><a class="nav-item <?= $active==='dashboard'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_dashboard.php#dashboard')) ?>">🏠 Dashboard</a>
    <div class="nav-title">Products</div><a class="nav-item <?= $active==='products'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_dashboard.php#products')) ?>">👟 Products</a><a class="nav-item <?= $active==='add-product'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_dashboard.php#add-product')) ?>">➕ Add Product</a>
    <div class="nav-title">Orders</div><a class="nav-item <?= $active==='orders'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_dashboard.php#orders')) ?>">📦 Orders</a><a class="nav-item <?= $active==='refunds'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_dashboard.php#refunds')) ?>">🔄 Refunds</a><a class="nav-item <?= $active==='reports'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_dashboard.php#reports')) ?>">📊 Reports</a>
    <div class="nav-title">More</div><a class="nav-item <?= $active==='reviews'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_reviews.php')) ?>">⭐ Reviews</a><a class="nav-item <?= $active==='exchanges'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_exchanges.php')) ?>">🔁 Exchanges</a><a class="nav-item <?= $active==='locations'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_locations.php')) ?>">📍 Locations</a><a class="nav-item <?= $active==='support'?'active':'' ?>" href="<?= h(app_url('MVC/View/GUI/component/admin_support.php')) ?>">🎧 Support</a>
    <div class="side-footer"><strong><?= h($adminName) ?></strong><div class="muted">Full Access</div><a href="<?= h(app_url('MVC/View/GUI/component/logout.php')) ?>" class="logout">🚪 Logout</a></div>
</aside>
<main class="main"><div class="topbar"><div class="title"><?= h($title) ?></div><div><?= h($adminName) ?></div></div><div class="content">
<?php }
function admin_footer(): void { global $APP_BASE; ?>
</div></main></div>
<script>
const APP_BASE = <?= json_encode($APP_BASE) ?>;
function toast(msg){const t=document.getElementById('toast');t.textContent=msg||'Done';t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000)}
function post(action,obj){return fetch(APP_BASE+'/MVC/Controller/AdminController.php?action='+action,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams(obj)}).then(r=>r.json())}
</script>
<div id="deleteModal" class="modal-overlay">
    <div class="modal-box">
        <h3 id="deleteModalTitle">Confirm Delete</h3>
        <p id="deleteModalText">Are you sure you want to delete this item?</p>

        <div class="modal-actions">
            <button type="button" class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn btn-danger" id="deleteModalBtn">Delete</button>
        </div>
    </div>
</div>

<style>
.modal-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(45,27,37,.35);
    z-index:99999;
    align-items:center;
    justify-content:center;
}
.modal-overlay.show{
    display:flex;
}
.modal-box{
    width:100%;
    max-width:420px;
    background:var(--white);
    border:1px solid var(--border);
    border-radius:16px;
    padding:24px;
    box-shadow:0 18px 50px rgba(45,27,37,.18);
}
.modal-box h3{
    margin-bottom:8px;
    color:var(--dark);
}
.modal-box p{
    color:var(--muted);
    font-size:14px;
    margin-bottom:20px;
}
.modal-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
}
</style>

<script>
let deleteActionCallback = null;

function openDeleteModal(text, callback) {
    document.getElementById('deleteModalText').textContent = text || 'Are you sure you want to delete this item?';
    deleteActionCallback = callback;
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    deleteActionCallback = null;
}

document.getElementById('deleteModalBtn').addEventListener('click', function () {
    if (typeof deleteActionCallback === 'function') {
        deleteActionCallback();
    }
    closeDeleteModal();
});
</script>

</body></html>
<?php }
