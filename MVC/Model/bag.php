<?php
require_once(__DIR__ . '/product.php');
require_once(__DIR__ . '/product_variants.php');
require_once(__DIR__ . '/ProductFactory.php');
require_once(__DIR__ . '/product_imgs.php');
require_once(__DIR__ . '/cart.php');

class Bag extends Product {

    public $capacityliters;
    public $numpackets;
    public function __construct($data) {
        parent::__construct($data); // Link basic data + variants
        $this->capacityliters = $data['capacityliters'] ?? null;
        $this->numpackets = $data['numpackets'] ?? null;
    }
     public function getSpecifications() {
        return ['Capacity (Liters)' => $this->capacityliters, 'Number of Packets' => $this->numpackets];

    }
    // Logic to get specific variant image based on user selection
    public function getVariantImage($color) {
        return $this->variants[$color]['image'] ?? $this->getDefaultImage();
    }

    // Logic to get all sizes available for a specific color
    public function getSizesByColor($color) {
        return $this->variants[$color]['sizes'] ?? [];
    }
}