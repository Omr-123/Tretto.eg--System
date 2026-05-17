<?php
require_once __DIR__ . "/../../../db.php";
require_once __DIR__ . "/../../Model/reviews.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$model = new ReviewModel($conn);
$reviews = $model->getAllReviews();
$userID = isset($_SESSION['userID']) ? (int) $_SESSION['userID'] : 0;
$products = $model->getAllProducts($userID);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews – Tretto</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/reviews.css">
    <script src="../javascript/navbar.js" defer></script>
</head>

<body>

    <?php include 'component/navbar.php'; ?>

    <div class="page" id="page-reviews">

        <div class="page-header">
            <div class="sec-tag">⭐ Share Your Experience</div>
            <h1 class="sec-title">Reviews & <em>Ratings</em></h1>
        </div>

        <div class="page-wrap">

            <div class="review-form-box">

                <div class="form-heading">Write a Review ✨</div>
                <div class="form-subheading">Only for delivered orders.</div>

                <?php if (!isset($_SESSION['userID'])): ?>

                    <div class="error-msg" style="display:block;">
                        You must be <a href="/Tretto.eg--System/MVC/View/GUI/login.php">logged in</a> to write a review.
                    </div>

                <?php elseif (empty($products)): ?>

                    <div class="error-msg" style="display:block;">
                        You have no delivered orders to review yet.
                    </div>

                <?php else: ?>

                    <form method="POST" action="/Tretto.eg--System/MVC/Controller/reviews_Controller.php?action=store">

                        <div class="form-group">
                            <label class="form-label">Select Product</label>
                            <select class="form-input" name="prod_ID" id="product-id" required>
                                <option value="" disabled selected>— Choose a product —</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['prod_ID'] ?>">
                                        <?= $product['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Your Rating</label>
                            <div class="star-rating" id="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" class="star-btn" data-value="<?= $i ?>">★</button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Your Review</label>
                            <textarea class="form-input" name="comment" id="rev-body" rows="4"
                                placeholder="Tell others what you think…"></textarea>
                        </div>

                        <div class="error-msg" id="rev-err" style="display:none; margin-bottom:10px;">
                            Please select a product, choose a star rating, and write your review.
                        </div>

                        <button type="button" class="btn-primary" id="submit-btn" onclick="submitReview()">
                            Submit Review ✨
                        </button>

                    </form>

                <?php endif; ?>

            </div>

            <div style="margin-top:40px;">
                <div class="sec-tag">💕 Customer Reviews</div>
                <h2 class="sec-title" style="font-size:28px; margin-bottom:24px;">
                    What Girls <em>Say</em>
                </h2>
            </div>

            <div class="reviews-list" id="reviews-list">

                <?php if (!empty($reviews)): ?>

                    <?php foreach ($reviews as $row): ?>
                        <div class="rev-card">

                            <div class="rev-stars">
                                <?php
                                $stars = (int) round((float) $row['rating']);
                                echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
                                ?>
                            </div>

                            <p class="rev-text">
                                <?= $row['comment'] ?>
                            </p>

                            <div class="rev-name">
                                <?= $row['user_name'] ?? 'Anonymous' ?>
                            </div>

                            <div class="rev-prod">
                                <?= $row['product_name'] ?? 'Unknown Product' ?>
                            </div>

                            <div class="rev-date">
                                <?= date('M j, Y', strtotime($row['reviewDate'])) ?>
                            </div>

                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p>No reviews yet. Be the first to share your experience!</p>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php include 'component/footer.php'; ?>
    <script src="../javascript/all.js" defer></script>
    <script>
        let selectedRating = 0;

        document.querySelectorAll('.star-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                selectedRating = parseInt(this.dataset.value);
                document.getElementById('rating-input').value = selectedRating;
                document.querySelectorAll('.star-btn').forEach(function (b) {
                    b.classList.toggle('lit', parseInt(b.dataset.value) <= selectedRating);
                });
            });
        });

        function submitReview() {
            const prodID = document.getElementById('product-id').value;
            const body = document.getElementById('rev-body').value.trim();
            const errBox = document.getElementById('rev-err');

            if (!prodID || selectedRating === 0 || body === '') {
                errBox.style.display = 'block';
                return;
            }

            errBox.style.display = 'none';
            document.getElementById('submit-btn').closest('form').submit();
        }
    </script>

</body>

</html>