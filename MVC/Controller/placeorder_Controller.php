<?php
$orderID = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$target = '/Tretto.eg--System/MVC/View/GUI/placeorder.php';
if ($orderID > 0) {
    $target .= '?id=' . $orderID;
}
header('Location: ' . $target);
exit;
