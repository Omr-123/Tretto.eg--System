<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../db.php";
require_once __DIR__ . "/../Model/reviews.php";

class ReviewController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new ReviewModel($conn);
    }

    public function index()
    {
        $reviews = $this->model->getAllReviews();
        $userID = isset($_SESSION['userID']) ? (int) $_SESSION['userID'] : 0;
        $products = $this->model->getAllProducts($userID);

        if (!$reviews)
            $reviews = [];
        if (!$products)
            $products = [];

        require __DIR__ . "/../View/GUI/reviews.php";
    }

    public function store()
    {
        if (!isset($_SESSION['userID'])) {
            header("Location: /Tretto.eg--System/MVC/View/GUI/login.php");
            exit();
        }

        $prod_ID = isset($_POST['prod_ID']) ? (int) $_POST['prod_ID'] : 0;
        $rating = isset($_POST['rating']) ? (float) $_POST['rating'] : 0;
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        if ($prod_ID === 0 || $rating < 1 || $rating > 5 || strlen($comment) < 3) {
            header("Location: /Tretto.eg--System/MVC/View/GUI/reviews.php");
            exit();
        }

        $userID = (int) $_SESSION['userID'];
        $success = $this->model->addReview($prod_ID, $userID, $rating, $comment);

        header("Location: /Tretto.eg--System/MVC/View/GUI/reviews.php");
        exit();
    }
}

$controller = new ReviewController($conn);
$action = $_GET['action'] ?? 'index';

if ($action === 'store') {
    $controller->store();
} else {
    $controller->index();
}
?>