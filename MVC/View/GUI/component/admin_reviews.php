<?php
require_once __DIR__ . '/admin_common.php';
$reviews = $controller->viewReviews();
admin_header('Reviews', 'reviews');
?>
<div class="card">
    <div class="card-head">
        <div class="card-title">Reviews</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $r):
                $id = (int) ($r['reviewID'] ?? $r['review_ID'] ?? 0); ?>
                <tr>
                    <td>#<?= $id ?></td>
                    <td><?= h($r['productName'] ?? $r['PID'] ?? '') ?></td>
                    <td><?= h($r['userName'] ?? $r['userID'] ?? '') ?></td>
                    <td><?= h($r['rating'] ?? '') ?></td>
                    <td><?= h($r['comment'] ?? $r['Review_comment'] ?? '') ?></td>
                    <td><button class="btn btn-danger" onclick="delReview(<?= $id ?>)">Delete Comment</button></td>
                </tr><?php endforeach; ?>
            <?php if (!$reviews): ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--muted)">No reviews found.</td>
                </tr><?php endif; ?>
        </tbody>
    </table>
</div>
<script>function delReview(id) { if (!confirm('Delete this review comment?')) return; post('deleteReview', { reviewID: id }).then(r => { toast(r.success ? 'Review deleted.' : 'Failed'); if (r.success) setTimeout(() => location.reload(), 600) }) }</script>
<?php admin_footer(); ?>