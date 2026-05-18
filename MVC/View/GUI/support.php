<?php
require_once __DIR__ . '/../../Controller/support_Controller.php';

$controller = new SupportController();
$data = $controller->loadPage();

$supportResult = $data['supportResult'];
$faqResult = $data['faqResult'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>

    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/support.css">

    <script src="../javascript/navbar.js" defer></script>
    <script src="../javascript/all.js" defer></script>
</head>

<body>

    <?php include 'component/navbar.php'; ?>

    <div class="page" id="page-support">

        <!-- HERO -->
        <div class="support-hero">
            <div class="sec-tag" style="color:rgba(255,255,255,.55)">
                💕 We're Here
            </div>

            <div class="support-hero-title">
                Customer Support 🌸
            </div>

            <div class="support-hero-sub">
                Reach out via WhatsApp, phone, or email — we reply within 24 hours!
            </div>
        </div>

        <div class="support-cards">

            <?php if ($supportResult && $supportResult->num_rows > 0): ?>

                <?php while ($row = $supportResult->fetch_assoc()): ?>

                    <div class="sup-card">

                        <div class="sup-icon">
                            <?php
                            if ($row['type'] == 'whatsapp')
                                echo "📱";
                            elseif ($row['type'] == 'phone')
                                echo "📞";
                            else
                                echo "✉️";
                            ?>
                        </div>

                        <div class="sup-title">
                            <?php echo ucfirst($row['type']); ?>
                        </div>

                        <div class="sup-txt">
                            <?php echo $row['description']; ?>
                        </div>

                        <div class="sup-lbl">
                            <?php echo ucfirst($row['type']); ?>
                        </div>

                        <div class="sup-contact">
                            <?php echo $row['value']; ?>
                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>
                <p style="color:white;">No support data found</p>
            <?php endif; ?>

        </div>

        <div class="faq-section">

            <div class="faq-title">
                Frequently Asked Questions 💕
            </div>

            <?php if ($faqResult && $faqResult->num_rows > 0): ?>

                <?php while ($faq = $faqResult->fetch_assoc()): ?>

                    <div class="faq-item" onclick="toggleFaq(this)">

                        <div class="faq-q">
                            <?php echo $faq['question']; ?>
                            <span class="faq-arrow">▾</span>
                        </div>

                        <div class="faq-a">
                            <?php echo $faq['answer']; ?>
                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>
                <p style="color:white;">No FAQ found</p>
            <?php endif; ?>

        </div>

    </div>
    <?php include 'component/footer.php'; ?>
    <script src="../javascript/all.js" defer></script>
</body>

</html>