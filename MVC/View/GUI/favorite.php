<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../../Controller/favorites_Controller.php';

$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : 0;
$favorites = getFavoritesForView($userID);
$fav=getFav($userID);
$ct=-1;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Favorites — Tretto</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/favorite.css">
    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
</head>

<body>
    <?php include 'component/navbar.php'; ?>

    <div class="page" id="page-favorites">
        <div class="page-header">
            <div class="sec-tag">💕 Saved Items</div>
            <h1 class="sec-title">Your <em>Favorites</em></h1>
        </div>

        <div class="page-wrap">
            <?php if ($userID === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">🔒</div>
                    <div class="empty-title">Please log in</div>
                    <div class="empty-sub">You need to be logged in to view your favorites.</div>
                    <a href="../GUI/login.php" class="btn-primary">Login</a>
                </div>

            <?php elseif (empty($favorites)): ?>
                <div id="fav-empty" class="empty-state">
                    <div class="empty-icon">🤍</div>
                    <div class="empty-title">No favorites yet</div>
                    <div class="empty-sub">Tap the ♡ on any product to save it here.</div>
                    <button class="btn-primary" onclick="window.location.href='products.php'">
                        Browse Products 🌸
                    </button>
                </div>

            <?php else: ?>
                <div class="prod-grid" id="fav-grid">
                    <?php foreach ($favorites as $item): $ct=$ct+1;?>
                        <div class="prod-card">
                            <?php if (!empty($item->variants[0]->img_url[0])): ?>
                                <img src="<?= $item->variants[0]->img_url[0]; ?>" alt="<?= $item->name; ?>" class="prod-img">
                            <?php else: ?>
                                <div class="prod-img prod-img-placeholder">No Image</div>
                            <?php endif; ?>

                            <div class="prod-title"><?= $item->name; ?></div>
                            <div class="prod-price"><?= $item->price; ?> EGP</div>

                            <a href="../../Controller/favorites_Controller.php?action=remove&id=<?= $fav[$ct]['favoriteID']; ?>&User=<?= $_SESSION['userID']?>"
                                class="btn-secondary">Remove</a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
    </div>
    <?php include 'component/footer.php'; ?>
    <script src="../javascript/all.js" defer></script>
</body>

</html>