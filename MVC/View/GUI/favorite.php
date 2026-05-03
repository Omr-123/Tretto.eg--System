<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="stylesheet" href="../css/main.css">
       <link rel="stylesheet" href="../css/navbar.css">
       <link rel="stylesheet" href="../css/favorite.css">
    <title>Document</title>
</head>
<body>
        <?php include 'component/navbar.php'; ?>

    <div class="page" id="page-favorites">
        <div class="page-header">
            <div class="sec-tag">💕 Saved Items</div>
            <h1 class="sec-title">Your <em>Favorites</em></h1>
        </div>
        <div class="page-wrap">
            <div id="fav-empty" class="empty-state">
                <div class="empty-icon">🤍</div>
                <div class="empty-title">No favorites yet</div>
                <div class="empty-sub">Tap the ♡ on any product to save it here.</div><button class="btn-primary"
                    onclick="goTo('products')">Browse Products 🌸</button>
            </div>
            <div class="prod-grid" id="fav-grid" style="display:none"></div>
        </div>
    </div>
</body>
</html>



I NEED CODE BE DYMAIC AND CONNECTED TO THE BACKEND TO DISPLAY FAVORITE PRODUCTS.To make the favorite products page dynamic and connected to the backend, you can use PHP to fetch the