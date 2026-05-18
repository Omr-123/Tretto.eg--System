<?php
require_once __DIR__ . '/../Model/storelocation.php';

function getLocationsForView($conn) {
    $locationModel = new Location($conn);
    return $locationModel->getAllLocations();
}
?>