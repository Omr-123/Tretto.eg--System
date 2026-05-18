<?php
require_once __DIR__ . '/../Model/Favorite.php';

$favModel = new Favorite();
$action = $_GET['action'] ?? '';

function getSessionUserID()
{
    return isset($_SESSION['userID']) ? intval($_SESSION['userID']) : 0;
}

if ($action === 'add') {
    $userID = getSessionUserID();
    $PID = isset($_POST['PID']) ? intval($_POST['PID']) : 0;
    if ($userID > 0 && $PID > 0) {
        $favModel->addFavorite($userID, $PID);
    }
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../View/GUI/favorites.php';
    header("Location: $redirect");
    exit;
}

if ($action === 'remove') {
    $userID = $_GET['User'];
    $favoriteID = isset($_GET['id']) ? $_GET['id'] : 0;
    if ($userID > 0 && $favoriteID > 0) {
        $favModel->removeFavorite($favoriteID);
    }
    header("Location: ../View/GUI/favorite.php");
    exit;
}

function getFavoritesForView($userID)
{
    $favModel = new Favorite();
    return $favModel->getFavorites($userID);
}
function getFav($userID){

    $favModel = new Favorite();
    return $favModel->getFav($userID);
}
?>