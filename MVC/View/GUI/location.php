<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/location.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <title>Locations</title>
</head>

<body>
    <?php include 'component/navbar.php'; ?>
    <?php
    require_once __DIR__ . '/../../Controller/storelocation_Controller.php';
    require_once __DIR__ . '/../../../db.php';
    $locations = getLocationsForView($conn);
    $activeMap = $locations[0]['map_link'] ?? '';
    ?>
    <div class="page" id="page-location">
        <div class="page-header">
            <div class="sec-tag">🌸 Find Us</div>
            <h1 class="sec-title">Our <em>Stores</em></h1>
        </div>
        <div class="page-wrap">
            <div class="location-layout">
                <div class="map-box">
                    <iframe id="store-map" src="<?= $activeMap ?>" width="100%" height="400" style="border:0;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="loc-cards">
                    <?php foreach ($locations as $loc): ?>
                        <div class="loc-card" onclick="document.getElementById('store-map').src='<?= $loc['map_link'] ?>'">
                            <div class="lc-title">🏪 <?= $loc['city'] ?> — <?= $loc['name'] ?></div>
                            <div class="lc-row"><span class="lc-ico">📍</span><span
                                    class="lc-txt"><?= $loc['address'] ?></span></div>
                            <div class="lc-row"><span class="lc-ico">📞</span><span class="lc-txt"><a
                                        href="tel:<?= $loc['phone'] ?>"><?= $loc['phone'] ?></a></span></div>
                            <div class="lc-row"><span class="lc-ico">✉️</span><span
                                    class="lc-txt"><?= $loc['email'] ?></span></div>
                            <div class="lc-hours">
                                <div class="hr-row"><span class="hr-day">Sat – Thu</span><?= $loc['sat_thu_hours'] ?></div>
                                <div class="hr-row"><span class="hr-day">Friday</span><?= $loc['friday_hours'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'component/footer.php'; ?>
    <script src="../javascript/all.js" defer></script>
</body>

</html>