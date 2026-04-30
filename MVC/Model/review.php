<?php
require_once('../../db.php');

class Review {
    public $review_ID;
    public $product_ID;
    public $userID;
    public $rating;
    public $comment;
    public $reviewDate;
    public $helpful_count;

}