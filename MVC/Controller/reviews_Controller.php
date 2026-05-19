<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../Model/reviews.php';
class ReviewController
{
    private ReviewModel $model;
    public function __construct(mysqli $conn)
    {
        $this->model = new ReviewModel($conn);
    }
    public function index(): void
    {
        $reviews = $this->model->getAllReviews() ?: [];
        $userID = isset($_SESSION['userID']) ? (int) $_SESSION['userID'] : 0;
        $products = $userID > 0 ? ($this->model->getAllProducts($userID) ?: []) : [];
        require __DIR__ . '/../View/GUI/reviews.php';
    }
    public function store(): void
    {
        if (!isset($_SESSION['userID'])) {
            header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
            exit;
        }
        $prod_ID = isset($_POST['prod_ID']) ? (int) $_POST['prod_ID'] : 0;
        $rating = isset($_POST['rating']) ? (float) $_POST['rating'] : 0;
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        $userID = (int) $_SESSION['userID'];

        if ($prod_ID === 0 || $rating < 1 || $rating > 5 || strlen($comment) < 3) {
            $_SESSION['review_error'] = 'Please select a product, choose a star rating (1–5), and write at least 3 characters.';
            header('Location: /Tretto.eg--System/MVC/View/GUI/reviews.php');
            exit;
        }
        $success = $this->model->addReview($prod_ID, $userID, $rating, $comment);
        if ($success) {
            unset($_SESSION['review_error']);
            $_SESSION['review_success'] = 'Thank you! Your review was submitted.';
        } else {
            $_SESSION['review_error'] = $this->model->getLastError() ?: 'Could not save your review. Please try again.';
        }
        header('Location: /Tretto.eg--System/MVC/View/GUI/reviews.php');
        exit;
    }
}
$controller = new ReviewController($conn);
$action = $_GET['action'] ?? 'index';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'store') {
    $controller->store();
} else {
    $controller->index();
}